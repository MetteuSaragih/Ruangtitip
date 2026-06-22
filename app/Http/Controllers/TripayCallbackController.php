<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\PrelovedItem;
use App\Models\TitipanOrder;
use App\Services\BiteshipService;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TripayCallbackController extends Controller
{
    /**
     * Webhook server-to-server dari Tripay setiap status transaksi berubah.
     * Ini SUMBER KEBENARAN status pembayaran, bukan redirect return_url di browser.
     *
     * Daftarkan URL ini di dashboard Tripay: Merchant > Pengaturan > Callback URL,
     * contoh: https://domainmu.com/tripay/callback
     *
     * Route HARUS bebas dari middleware 'auth' dan dikecualikan dari CSRF.
     */
    public function handle(Request $request, TripayService $tripay, BiteshipService $biteship)
    {
        $rawBody = $request->getContent();
        $signature = $request->header('X-Callback-Signature');

        if (! $tripay->verifyCallbackSignature($rawBody, $signature)) {
            Log::warning('Tripay callback signature tidak valid', ['body' => $rawBody]);

            return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
        }

        $payload = json_decode($rawBody, true) ?? [];
        $merchantRef = $payload['merchant_ref'] ?? null;
        $status = $payload['status'] ?? null; // PAID|UNPAID|EXPIRED|FAILED|REFUND

        if (! $merchantRef || ! $status) {
            return response()->json(['success' => false, 'message' => 'Payload tidak lengkap'], 422);
        }

        Log::info("Tripay callback {$merchantRef}: {$status}");

        if (str_starts_with($merchantRef, 'TP-')) {
            $order = PackingOrder::where('order_code', $merchantRef)->first();
            if ($order) {
                $this->applyPackingStatus($order, $status, $biteship);
            }
        } elseif (str_starts_with($merchantRef, 'RT-')) {
            $order = Order::where('order_number', $merchantRef)->first();
            if ($order) {
                $this->applyOrderStatus($order, $status, $biteship);
            }
        } elseif (str_starts_with($merchantRef, 'RTP-')) {
            $id = (int) ltrim(substr($merchantRef, 4), '0');
            $order = TitipanOrder::find($id);
            if ($order) {
                $this->applyTitipanStatus($order, $status, $biteship);
            }
        }

        return response()->json(['success' => true]);
    }

    private function applyPackingStatus(PackingOrder $order, string $status, BiteshipService $biteship): void
    {
        $order->payment_status = $status;
        if ($status === 'PAID') {
            $order->status = 'diproses';
        }
        $order->save();

        if ($status === 'PAID' && $order->logistic === 'biteship' && ! $order->biteship_order_id) {
            $this->bookPackingShipment($order, $biteship);
        }
    }

    private function applyOrderStatus(Order $order, string $status, BiteshipService $biteship): void
    {
        $order->payment_status = $status;
        if ($status === 'PAID') {
            $order->status = Order::STATUS_PAID;
        }
        $order->save();

        if ($status === 'PAID') {
            $this->markPrelovedItemsSold($order);
        }

        if ($status === 'PAID' && $order->shipping_method === 'biteship' && ! $order->biteship_order_id) {
            $this->bookOrderShipment($order, $biteship);
        }
    }

    private function markPrelovedItemsSold(Order $order): void
    {
        $prelovedIds = collect($order->items)
            ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
            ->pluck('id')
            ->filter()
            ->all();

        if (! empty($prelovedIds)) {
            PrelovedItem::whereIn('id', $prelovedIds)->update(['status' => 'Terjual']);
        }
    }

    private function applyTitipanStatus(TitipanOrder $order, string $status, BiteshipService $biteship): void
    {
        $order->payment_status = $status;
        if ($status === 'PAID') {
            $order->status = 'penjadwalan_penjemputan';
        }
        $order->save();

        if ($status === 'PAID' && $order->logistic === 'instant' && ! $order->biteship_order_id) {
            $this->bookPickupShipment($order, $biteship);
        }
    }

    private function bookPackingShipment(PackingOrder $order, BiteshipService $biteship): void
    {
        try {
            $warehouse = config('biteship.warehouse');
            $result = $biteship->createOrder(
                origin: [
                    'name' => $warehouse['name'],
                    'phone' => $warehouse['phone'],
                    'address' => $warehouse['address'],
                    'area_id' => $warehouse['area_id'],
                    'postal_code' => $warehouse['postal_code'],
                ],
                destination: [
                    'name' => $order->user->name ?? 'Pelanggan RuTip',
                    'phone' => $order->user->phone ?? '0800000000',
                    'address' => $order->address,
                    'area_id' => $order->address_area_id,
                    'postal_code' => $order->address_postal_code,
                ],
                courier: ['company' => $order->courier_company ?? $order->courier, 'type' => $order->courier_service_code],
                items: collect($order->items)->map(fn ($i) => [
                    'name' => $i['name'], 'value' => (int) $i['price'], 'quantity' => (int) $i['qty'], 'weight' => (int) ($i['weight'] ?? 500),
                ])->all(),
                referenceId: $order->order_code,
                schedule: $order->pickup_date ? ['date' => $order->pickup_date, 'time' => $order->pickup_time] : null,
            );

            $order->update([
                'biteship_order_id' => $result['id'] ?? null,
                'biteship_tracking_id' => $result['courier']['tracking_id'] ?? $result['courier']['waybill_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal booking Biteship untuk PackingOrder ' . $order->order_code . ': ' . $e->getMessage());
        }
    }

    private function bookOrderShipment(Order $order, BiteshipService $biteship): void
    {
        try {
            $warehouse = config('biteship.warehouse');
            $destination = $order->shipping_address ?? [];

            $result = $biteship->createOrder(
                origin: [
                    'name' => $warehouse['name'],
                    'phone' => $warehouse['phone'],
                    'address' => $warehouse['address'],
                    'area_id' => $warehouse['area_id'],
                    'postal_code' => $warehouse['postal_code'],
                ],
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
                    'name' => $i['name'], 'value' => (int) $i['price'], 'quantity' => (int) $i['qty'], 'weight' => (int) ($i['weight'] ?? 1000),
                ])->all(),
                referenceId: $order->order_number,
                schedule: $order->pickup_date ? ['date' => $order->pickup_date, 'time' => $order->pickup_time] : null,
            );

            $order->update([
                'biteship_order_id' => $result['id'] ?? null,
                'biteship_tracking_id' => $result['courier']['tracking_id'] ?? $result['courier']['waybill_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal booking Biteship untuk Order ' . $order->order_number . ': ' . $e->getMessage());
        }
    }

    private function bookPickupShipment(TitipanOrder $order, BiteshipService $biteship): void
    {
        try {
            $warehouse = config('biteship.warehouse');
            $result = $biteship->createOrder(
                origin: [
                    'name' => $order->user->name ?? 'Penitip RuTip',
                    'phone' => $order->user->phone ?? '0800000000',
                    'address' => $order->address,
                    'area_id' => $order->address_area_id,
                    'postal_code' => $order->address_postal_code,
                ],
                destination: [
                    'name' => $warehouse['name'],
                    'phone' => $warehouse['phone'],
                    'address' => $warehouse['address'],
                    'area_id' => $warehouse['area_id'],
                    'postal_code' => $warehouse['postal_code'],
                ],
                courier: ['company' => $order->courier_company ?? $order->courier_code, 'type' => $order->courier_service_code],
                items: collect($order->items)->map(fn ($qty, $code) => [
                    'name' => 'Barang titipan', 'value' => 50000, 'quantity' => (int) $qty, 'weight' => 3000,
                ])->values()->all(),
                referenceId: $order->code(),
                schedule: $order->pickup_date ? ['date' => $order->pickup_date->format('Y-m-d'), 'time' => $order->pickup_time] : null,
            );

            $order->update([
                'biteship_order_id' => $result['id'] ?? null,
                'biteship_tracking_id' => $result['courier']['tracking_id'] ?? $result['courier']['waybill_id'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal booking Biteship untuk Titipan ' . $order->id . ': ' . $e->getMessage());
        }
    }
}
