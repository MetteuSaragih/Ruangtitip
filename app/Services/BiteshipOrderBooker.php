<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Satu tempat untuk membuat order pengiriman Biteship (POST /v1/orders) dari
 * ketiga jenis pesanan (Ruang Titip, Toko Packing, Toko Preloved), lengkap
 * dengan retry-bookkeeping. Dipakai oleh:
 *  - BookShipmentOnOrderPaid (listener event OrderPaid) untuk kurir reguler,
 *    dipesan segera setelah pembayaran lunas.
 *  - Command biteship:book-pending (scheduler tiap 5 menit) untuk kurir
 *    instan (dipesan ~45 menit sebelum slot) dan sebagai penyapu percobaan
 *    ulang untuk pesanan yang gagal dipesan sebelumnya.
 */
class BiteshipOrderBooker
{
    public function __construct(private BiteshipService $biteship)
    {
    }

    /** 'instant' | 'regular' | 'none' — 'none' kalau bukan kurir Biteship (mis. rutip_fleet / kosong). */
    public function courierGroup(?string $courierCode): string
    {
        if (! $courierCode) {
            return 'none';
        }
        if (in_array($courierCode, config('biteship.instant_couriers', []), true)) {
            return 'instant';
        }
        if (in_array($courierCode, config('biteship.regular_couriers', []), true)) {
            return 'regular';
        }

        return 'none';
    }

    /** Pesanan ini relevan untuk dipesankan Biteship (sudah lunas, pakai kurir Biteship, belum dipesan, belum di-flag). */
    public function isEligible(TitipanOrder|PackingOrder|Order $order): bool
    {
        if ($order->payment_status !== 'PAID') {
            return false;
        }
        if ($order->biteship_order_id || $order->needs_admin_attention) {
            return false;
        }

        return $this->courierGroup($this->courierCodeOf($order)) !== 'none';
    }

    /** Tanggal+jam penjemputan/pengantaran pesanan ini, atau null kalau belum dijadwalkan. */
    public function pickupDateTime(TitipanOrder|PackingOrder|Order $order): ?Carbon
    {
        $date = $order->pickup_date;
        $time = $order->pickup_time;
        if (! $date || ! $time) {
            return null;
        }

        $dateString = $date instanceof Carbon ? $date->format('Y-m-d') : (string) $date;

        try {
            return Carbon::parse($dateString . ' ' . $time, 'Asia/Jakarta');
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Coba pesan order Biteship untuk satu pesanan. $forceNow=true memaksa
     * delivery_type "now" (dipakai untuk kurir instan yang baru dipesan
     * menjelang slot, walaupun pesanan punya pickup_date/pickup_time).
     * Aman dipanggil berkali-kali: pakai row lock + cek ulang biar tidak
     * dobel pesan kalau listener & command kebetulan jalan bersamaan.
     */
    public function attempt(TitipanOrder|PackingOrder|Order $order, bool $forceNow = false): bool
    {
        return DB::transaction(function () use ($order, $forceNow) {
            /** @var TitipanOrder|PackingOrder|Order $locked */
            $locked = $order::query()->whereKey($order->getKey())->lockForUpdate()->first();
            if (! $locked || ! $this->isEligible($locked)) {
                return (bool) $locked?->biteship_order_id;
            }

            try {
                $result = match (true) {
                    $locked instanceof TitipanOrder => $this->bookTitipan($locked, $forceNow),
                    $locked instanceof PackingOrder => $this->bookPacking($locked, $forceNow),
                    $locked instanceof Order => $this->bookOrder($locked, $forceNow),
                };

                $locked->forceFill([
                    'biteship_order_id' => $result['id'] ?? null,
                    'biteship_tracking_id' => $result['courier']['tracking_id'] ?? $result['courier']['waybill_id'] ?? null,
                    'biteship_status' => $result['status'] ?? 'confirmed',
                    'biteship_last_error' => null,
                ])->save();

                return true;
            } catch (Throwable $e) {
                $attempts = $locked->biteship_attempts + 1;
                $maxAttempts = (int) config('pickup_slots.max_booking_attempts', 5);

                $locked->forceFill([
                    'biteship_attempts' => $attempts,
                    'biteship_last_error' => $e->getMessage(),
                    'needs_admin_attention' => $attempts >= $maxAttempts,
                ])->save();

                Log::error('Gagal booking Biteship (' . class_basename($locked) . ' #' . $locked->getKey() . ', percobaan ke-' . $attempts . '): ' . $e->getMessage());

                return false;
            }
        });
    }

    public function courierCodeOf(TitipanOrder|PackingOrder|Order $order): ?string
    {
        return match (true) {
            $order instanceof TitipanOrder => $order->courier_code,
            $order instanceof PackingOrder => $order->courier,
            $order instanceof Order => $order->courier_code,
        };
    }

    private function scheduleFor(TitipanOrder|PackingOrder|Order $order, bool $forceNow): ?array
    {
        if ($forceNow) {
            return null;
        }

        $dt = $this->pickupDateTime($order);

        return $dt ? ['date' => $dt->format('Y-m-d'), 'time' => $dt->format('H:i')] : null;
    }

    private function warehouseContact(): array
    {
        $warehouse = config('biteship.warehouse');

        return [
            'name' => $warehouse['name'],
            'phone' => $warehouse['phone'],
            'address' => $warehouse['address'],
            'area_id' => $warehouse['area_id'],
            'postal_code' => $warehouse['postal_code'],
        ];
    }

    private function bookTitipan(TitipanOrder $order, bool $forceNow): array
    {
        $fallback = config('item_sizes.fallback');

        $items = collect($order->items ?? [])->map(function ($qty, $code) use ($order, $fallback) {
            $type = str_starts_with((string) $code, 'kp') ? 'koper' : ($code === 'dimensi_lain' ? 'dimensi_lain' : 'kardus');
            $size = $code === 'dimensi_lain'
                ? config('item_sizes.dimensi_lain')
                : config("item_sizes.{$type}.{$code}", $fallback);

            return [
                'name' => $size['label'] ?? 'Barang titipan',
                'value' => 50000,
                'quantity' => (int) $qty,
                'weight' => $size['weight'] ?? $size['weight_default'] ?? $fallback['weight'],
                'length' => $size['length'] ?? $fallback['length'],
                'width' => $size['width'] ?? $fallback['width'],
                'height' => $size['height'] ?? $fallback['height'],
            ];
        })->values()->all();

        return $this->biteship->createOrder(
            origin: [
                'name' => $order->user->name ?? 'Penitip RuTip',
                'phone' => $order->user->phone ?? '0800000000',
                'address' => $order->address,
                'area_id' => $order->address_area_id,
                'postal_code' => $order->address_postal_code,
            ],
            destination: $this->warehouseContact(),
            courier: ['company' => $order->courier_company ?? $order->courier_code, 'type' => $order->courier_service_code],
            items: $items,
            referenceId: $order->code(),
            schedule: $this->scheduleFor($order, $forceNow),
        );
    }

    private function bookPacking(PackingOrder $order, bool $forceNow): array
    {
        return $this->biteship->createOrder(
            origin: $this->warehouseContact(),
            destination: [
                'name' => $order->user->name ?? 'Pelanggan RuTip',
                'phone' => $order->user->phone ?? '0800000000',
                'address' => $order->address,
                'area_id' => $order->address_area_id,
                'postal_code' => $order->address_postal_code,
            ],
            courier: ['company' => $order->courier_company ?? $order->courier, 'type' => $order->courier_service_code],
            items: collect($order->items)->map(fn ($i) => [
                'name' => $i['name'], 'value' => (int) $i['price'], 'quantity' => (int) $i['qty'],
                'weight' => (int) ($i['weight'] ?? 500),
                'length' => (int) ($i['length'] ?? 30), 'width' => (int) ($i['width'] ?? 20), 'height' => (int) ($i['height'] ?? 15),
            ])->all(),
            referenceId: $order->order_code,
            schedule: $this->scheduleFor($order, $forceNow),
        );
    }

    private function bookOrder(Order $order, bool $forceNow): array
    {
        $destination = $order->shipping_address ?? [];

        return $this->biteship->createOrder(
            origin: $this->warehouseContact(),
            destination: [
                'name' => $order->customer_name ?? 'Pelanggan RuTip',
                'phone' => $order->customer_phone ?? '0800000000',
                'address' => $destination['full'] ?? '',
                'note' => $destination['note'] ?? null,
                'area_id' => $destination['area_id'] ?? null,
                'postal_code' => $destination['postal_code'] ?? null,
            ],
            courier: ['company' => $order->courier_code, 'type' => $order->courier_service_code],
            items: collect($order->items)->map(fn ($i) => [
                'name' => $i['name'], 'value' => (int) $i['price'], 'quantity' => (int) $i['qty'],
                'weight' => (int) ($i['weight'] ?? 1000),
                'length' => (int) ($i['length'] ?? 30), 'width' => (int) ($i['width'] ?? 20), 'height' => (int) ($i['height'] ?? 15),
            ])->all(),
            referenceId: $order->order_number,
            schedule: $this->scheduleFor($order, $forceNow),
        );
    }
}
