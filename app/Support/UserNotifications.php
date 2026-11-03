<?php

namespace App\Support;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use Illuminate\Support\Collection;

/**
 * Menyusun daftar notifikasi pengguna dari status pesanan Ruang Titip,
 * Toko Packing, dan Toko Preloved. Dipakai oleh shell (lonceng) dan Beranda.
 */
class UserNotifications
{
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
                $items->push(['text' => $msg($o), 'at' => $o->updated_at, 'link' => route('pesanan.index')]);
            }
        }

        foreach (PackingOrder::where('user_id', $user->id)->latest('updated_at')->take($limit)->get() as $o) {
            $text = match (true) {
                $o->payment_status === 'PAID' => "Pembayaran pesanan #{$o->order_code} berhasil dikonfirmasi",
                in_array($o->payment_status, ['FAILED', 'EXPIRED']) => "Pembayaran pesanan #{$o->order_code} gagal/kedaluwarsa",
                default => "Pesanan #{$o->order_code} menunggu pembayaran",
            };
            $items->push(['text' => $text, 'at' => $o->updated_at, 'link' => route('pesanan.index')]);
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
            $items->push(['text' => $text, 'at' => $o->updated_at, 'link' => route('pesanan.index')]);
        }

        return $items->sortByDesc('at')->take($limit)->values();
    }

    /** Jumlah notifikasi yang diperbarui dalam 2 hari terakhir. */
    public static function unreadCount(Collection $notifications): int
    {
        return $notifications->filter(fn ($n) => $n['at'] && $n['at']->gt(now()->subDays(2)))->count();
    }
}
