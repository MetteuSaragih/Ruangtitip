<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Menyatukan tagihan dari tiga sumber (TitipanOrder, Order Preloved, PackingOrder)
 * menjadi satu bentuk seragam. Hanya baca; status uang datang dari callback Tripay.
 */
class BillingFeed
{
    public const TYPES = [
        'titip' => 'Titip',
        'preloved' => 'Preloved',
        'packing' => 'Packing',
    ];

    /** Kelompok status: wait (menunggu), ok (berhasil), fail (gagal/kedaluwarsa). */
    public static function group(?string $raw): string
    {
        $s = strtoupper(trim((string) $raw));
        if (in_array($s, ['PAID', 'SUCCESS', 'SETTLEMENT', 'SETTLED', 'CAPTURE'], true)) {
            return 'ok';
        }
        if (in_array($s, ['FAILED', 'EXPIRED', 'CANCEL', 'CANCELLED', 'DENY', 'REFUND'], true)) {
            return 'fail';
        }

        return 'wait';
    }

    public static function label(?string $raw): string
    {
        return match (strtoupper(trim((string) $raw))) {
            'PAID', 'SUCCESS', 'SETTLEMENT', 'SETTLED', 'CAPTURE' => 'Berhasil',
            'FAILED', 'DENY' => 'Gagal',
            'EXPIRED' => 'Kedaluwarsa',
            'CANCEL', 'CANCELLED' => 'Dibatalkan',
            'REFUND' => 'Dikembalikan',
            default => 'Menunggu',
        };
    }

    public static function tone(string $group): string
    {
        return match ($group) {
            'ok' => 'ok',
            'fail' => 'err',
            default => 'warn',
        };
    }

    /**
     * Ambil tagihan terbaru dari ketiga sumber.
     *
     * @param  Carbon|null  $since  hanya yang dibuat atau diubah sejak waktu ini
     * @param  string|null  $only   batasi ke satu jenis (titip|preloved|packing)
     */
    public static function records(int $limit = 200, ?Carbon $since = null, ?string $only = null): Collection
    {
        $all = collect();

        foreach (array_keys(self::TYPES) as $type) {
            if ($only && $only !== $type) {
                continue;
            }
            try {
                $all = $all->concat(self::fetch($type, $limit, $since));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return $all;
    }

    private static function fetch(string $type, int $limit, ?Carbon $since): Collection
    {
        $model = match ($type) {
            'titip' => TitipanOrder::query(),
            'preloved' => Order::query(),
            default => PackingOrder::query(),
        };
        $table = $model->getModel()->getTable();
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'payment_status')) {
            return collect();
        }
        if ($type !== 'preloved') {
            $model->with('user:id,name,email');
        }
        if ($since) {
            $model->where(function ($q) use ($since) {
                $q->where('created_at', '>=', $since)->orWhere('updated_at', '>=', $since);
            });
        }

        return $model->orderByDesc('created_at')->limit($limit)->get()
            ->map(fn ($m) => self::normalize($type, $m));
    }

    public static function find(string $type, int $id): ?array
    {
        $model = match ($type) {
            'titip' => TitipanOrder::query()->with('user:id,name,email'),
            'preloved' => Order::query(),
            'packing' => PackingOrder::query()->with('user:id,name,email'),
            default => null,
        };
        if (! $model) {
            return null;
        }
        $m = $model->find($id);

        return $m ? self::normalize($type, $m) : null;
    }

    public static function normalize(string $type, $m): array
    {
        $raw = (string) ($m->payment_status ?? '');
        $group = self::group($raw);
        $customer = '-';
        $email = null;
        $code = '#' . $m->id;
        $breakdown = [];

        if ($type === 'titip') {
            $code = $m->code();
            $customer = $m->user->name ?? 'Pelanggan';
            $email = $m->user->email ?? null;
            $breakdown = [
                ['Biaya penitipan', (int) $m->item_subtotal],
                ['Ongkos kirim', (int) $m->courier_cost],
                ['Packing', (int) $m->packing_cost],
                ['Biaya platform', (int) $m->platform_fee],
            ];
        } elseif ($type === 'preloved') {
            $code = (string) ($m->order_number ?: $code);
            $customer = $m->customer_name ?: 'Pelanggan';
            $email = $m->customer_email;
            $breakdown = [
                ['Subtotal barang', (int) $m->subtotal],
                ['Biaya layanan', (int) $m->service_fee],
                ['Ongkos kirim', (int) $m->shipping_cost],
            ];
        } else {
            $code = (string) ($m->order_code ?: $code);
            $customer = $m->user->name ?? 'Pelanggan';
            $email = $m->user->email ?? null;
            $breakdown = [
                ['Subtotal barang', (int) $m->subtotal],
                ['Ongkos kirim', (int) $m->shipping_cost],
                ['Biaya platform', (int) $m->platform_fee],
            ];
        }

        return [
            'type' => $type,
            'type_label' => self::TYPES[$type],
            'id' => $m->id,
            'code' => $code,
            'customer' => $customer,
            'email' => $email,
            'method' => $m->tripay_payment_method ?: ($m->payment_method ?: '-'),
            'amount' => (int) $m->total,
            'raw' => $raw,
            'group' => $group,
            'label' => self::label($raw),
            'tone' => self::tone($group),
            'created' => $m->created_at,
            // Tidak ada kolom tanggal bayar; untuk status berhasil dipakai updated_at (waktu callback).
            'paid' => $group === 'ok' ? $m->updated_at : null,
            'reference' => $m->tripay_reference,
            'pay_code' => $m->tripay_pay_code,
            'checkout_url' => $m->tripay_checkout_url,
            'breakdown' => $breakdown,
        ];
    }
}
