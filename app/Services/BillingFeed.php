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
 * menjadi satu bentuk seragam. Hanya baca; keputusan pembayaran (konfirmasi/tolak)
 * dilakukan lewat ManualPayment dari halaman Tagihan admin.
 */
class BillingFeed
{
    public const TYPES = [
        'titip' => 'Titip',
        'preloved' => 'Preloved',
        'packing' => 'Packing',
    ];

    /** Kelompok status: review (perlu diperiksa), wait (menunggu bukti), ok (berhasil), fail (gagal/ditolak/kedaluwarsa). */
    public static function group(?string $raw): string
    {
        return match (ManualPayment::state($raw)) {
            'paid' => 'ok',
            'review' => 'review',
            'rejected', 'failed' => 'fail',
            default => 'wait',
        };
    }

    public static function label(?string $raw): string
    {
        return ManualPayment::label($raw);
    }

    public static function tone(string $group): string
    {
        return match ($group) {
            'ok' => 'ok',
            'review' => 'info',
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
            'method' => strtolower((string) $m->payment_method) === 'qris' ? 'QRIS' : ($m->tripay_payment_method ?: ($m->payment_method ?: '-')),
            'amount' => (int) $m->total,
            'raw' => $raw,
            'group' => $group,
            'label' => self::label($raw),
            'tone' => self::tone($group),
            'created' => $m->created_at,
            'paid' => $group === 'ok' ? ($m->paid_at ?: $m->updated_at) : null,
            'state' => ManualPayment::state($raw),
            'proof_url' => ManualPayment::proofExists($m) ? route('payment.proof.show', [$type, $m->id]) : null,
            'proof_at' => $m->payment_proof_at,
            'reason' => $m->payment_reject_reason,
            'attempts' => (int) ($m->payment_attempts ?? 0),
            'reviewed_at' => $m->payment_reviewed_at,
            'reviewer_id' => $m->payment_reviewed_by,
            'order_status' => (string) $m->status,
            'reference' => $m->tripay_reference,
            'pay_code' => $m->tripay_pay_code,
            'checkout_url' => $m->tripay_checkout_url,
            'breakdown' => $breakdown,
        ];
    }
}
