<?php

namespace App\Support;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use Illuminate\Support\Collection;

/**
 * Data ringkas untuk shell dan Beranda pengguna: notifikasi (diturunkan dari
 * status pesanan) dan daftar "Perlu tindakanmu".
 */
class UserNotifications
{
    private static array $needsCache = [];

    /**
     * @return Collection<int, array{text: string, at: \Illuminate\Support\Carbon|null, link: string}>
     */
    public static function for($user, int $limit = 5): Collection
    {
        $items = collect();

        if (! $user) {
            return $items;
        }

        $titipanMsg = [
            'menunggu_pembayaran' => fn ($o) => "Pesanan #{$o->code()} menunggu pembayaran",
            'penjadwalan_penjemputan' => fn ($o) => "Pesanan #{$o->code()} dalam proses penjemputan",
            'dalam_gudang' => fn ($o) => 'Barangmu aman tersimpan di gudang RuangTitip',
            'proses_pengembalian' => fn ($o) => "Pesanan #{$o->code()} sedang diproses pengembalian",
            'selesai' => fn ($o) => "Pesanan #{$o->code()} telah selesai",
        ];

        foreach (TitipanOrder::where('user_id', $user->id)->latest('updated_at')->take($limit)->get() as $o) {
            if ($msg = $titipanMsg[$o->status] ?? null) {
                $items->push(['text' => $msg($o), 'at' => $o->updated_at, 'link' => route('pesanan.detail', $o)]);
            }
        }

        foreach (PackingOrder::where('user_id', $user->id)->latest('updated_at')->take($limit)->get() as $o) {
            $text = match (true) {
                $o->payment_status === 'PAID' => "Pembayaran pesanan #{$o->order_code} berhasil dikonfirmasi",
                in_array($o->payment_status, ['FAILED', 'EXPIRED']) => "Pembayaran pesanan #{$o->order_code} gagal/kedaluwarsa",
                default => "Pesanan #{$o->order_code} menunggu pembayaran",
            };
            $items->push(['text' => $text, 'at' => $o->updated_at, 'link' => route('packing.success', $o->order_code)]);
        }

        foreach (Order::where('customer_email', $user->email)->latest('updated_at')->take($limit)->get() as $o) {
            $text = match (true) {
                $o->status === 'delivered' => "Pesanan #{$o->order_number} telah selesai",
                $o->status === 'shipped' => "Pesanan #{$o->order_number} sedang dikirim",
                $o->status === 'processing' => "Pesanan #{$o->order_number} sedang diproses",
                $o->payment_status === 'PAID' => "Pembayaran pesanan #{$o->order_number} berhasil dikonfirmasi",
                in_array($o->payment_status, ['FAILED', 'EXPIRED']) => "Pembayaran pesanan #{$o->order_number} gagal/kedaluwarsa",
                default => "Pesanan #{$o->order_number} menunggu pembayaran",
            };
            $items->push(['text' => $text, 'at' => $o->updated_at, 'link' => route('checkout.success', $o->order_number)]);
        }

        return $items->sortByDesc('at')->take($limit)->values();
    }

    /** Notifikasi belum dibaca: diperbarui dalam 2 hari terakhir dan setelah "tandai semua dibaca". */
    public static function unreadCount(Collection $notifications): int
    {
        return $notifications->filter(fn ($n) => self::isUnread($n))->count();
    }

    public static function isUnread(array $n): bool
    {
        if (empty($n['at']) || $n['at']->lte(now()->subDays(2))) {
            return false;
        }

        $readAt = session('notif_read_at');

        return ! $readAt || $n['at']->gt(\Illuminate\Support\Carbon::parse($readAt));
    }

    /**
     * Daftar "Perlu tindakanmu": tagihan yang belum dibayar, penjemputan yang
     * menunggu, dan titipan yang masa titipnya hampir habis. Yang paling
     * mendesak (bayar) di atas.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function needs($user): Collection
    {
        if (! $user) {
            return collect();
        }

        return self::$needsCache[$user->id] ??= self::buildNeeds($user);
    }

    public static function actionCount($user): int
    {
        return self::needs($user)->count();
    }

    private static function rp($n): string
    {
        return 'Rp' . number_format((float) $n, 0, ',', '.');
    }

    private static function buildNeeds($user): Collection
    {
        $rows = collect();

        $titipan = TitipanOrder::with('storage')
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu_pembayaran', 'penjadwalan_penjemputan'])
            ->latest('updated_at')
            ->get();

        foreach ($titipan as $o) {
            $pay = $o->status === 'menunggu_pembayaran';
            $rows->push([
                'sort' => $pay ? 0 : 2,
                'code' => $o->code(),
                'label' => $o->statusMeta()['label'],
                'tone' => $pay ? 'warn' : 'info',
                'href' => route('pesanan.detail', $o),
                'cta' => $pay ? 'Bayar ' . self::rp($o->total) : 'Lihat detail',
                'pay' => $pay,
                'what' => trim(($o->storage?->name ?? 'Penitipan') . ', ' . $o->totalItems() . ' barang'),
            ]);
        }

        $packing = PackingOrder::where('user_id', $user->id)
            ->where('status', '!=', 'selesai')
            ->where(fn ($q) => $q->whereNull('payment_status')->orWhereIn('payment_status', ['UNPAID', 'pending']))
            ->latest('updated_at')
            ->get();

        foreach ($packing as $o) {
            $rows->push([
                'sort' => 0,
                'code' => $o->order_code,
                'label' => 'Menunggu Pembayaran',
                'tone' => 'warn',
                'href' => route('packing.success', $o->order_code),
                'cta' => 'Bayar ' . self::rp($o->total),
                'pay' => true,
                'what' => 'Toko Packing, ' . collect($o->items ?? [])->sum('qty') . ' barang',
            ]);
        }

        $orders = Order::where('customer_email', $user->email)
            ->where('payment_status', 'UNPAID')
            ->where('status', Order::STATUS_PENDING)
            ->latest('updated_at')
            ->get();

        foreach ($orders as $o) {
            $rows->push([
                'sort' => 0,
                'code' => $o->order_number,
                'label' => 'Menunggu Pembayaran',
                'tone' => 'warn',
                'href' => route('checkout.success', $o->order_number),
                'cta' => 'Bayar ' . self::rp($o->total),
                'pay' => true,
                'what' => 'Toko, ' . collect($o->items ?? [])->pluck('name')->take(2)->implode(', '),
            ]);
        }

        $soon = TitipanOrder::with('storage')
            ->where('user_id', $user->id)
            ->where('status', 'dalam_gudang')
            ->whereNotNull('date_end')
            ->get();

        foreach ($soon as $o) {
            $left = (int) now()->startOfDay()->diffInDays($o->date_end->copy()->startOfDay(), false);
            if ($left >= 0 && $left <= 3) {
                $rows->push([
                    'sort' => 1,
                    'code' => $o->code(),
                    'label' => $left === 0 ? 'Masa titip habis hari ini' : "Masa titip habis {$left} hari lagi",
                    'tone' => 'warn',
                    'href' => route('pesanan.detail', $o),
                    'cta' => 'Pilih tindakan',
                    'pay' => false,
                    'what' => 'Hubungi admin untuk perpanjang atau ambil barangmu',
                ]);
            }
        }

        return $rows->sortBy('sort')->values();
    }
}
