<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\PackingProduct;
use App\Models\PrelovedItem;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $storages = StorageRoom::active()->latest()->take(3)->get();
        $packing = PackingProduct::active()->latest()->take(4)->get();
        $preloved = PrelovedItem::where('status', 'Tersedia')->latest()->take(4)->get();
        $testimonials = collect([
            ['text' => 'Barang-barang kos saya aman banget di RuangTitip. Pulang ke Surabaya 3 bulan, semua masih mulus dan tersegel.', 'name' => 'Anisa Rahmawati', 'major' => 'Teknik Informatika, UB 2022', 'avatar' => 'AR', 'color' => 'tape'],
            ['text' => 'Proses jemput cepat, admin responsif. Biayanya jauh lebih murah dibanding bayar kos kosong.', 'name' => 'Budi Santoso', 'major' => 'Manajemen, UB 2021', 'avatar' => 'BS', 'color' => 'depot'],
            ['text' => 'Saya pakai layanan preloved juga. Barang bekas kos terjual dalam 2 hari, dapat uang dari jual barang.', 'name' => 'Cahya Putri', 'major' => 'Ilmu Komunikasi, UB 2023', 'avatar' => 'CP', 'color' => 'sand'],
        ]);

        $initials = '';
        if ($user && $user->name) {
            $parts = preg_split('/\s+/', trim($user->name));
            foreach (array_slice($parts, 0, 2) as $p) {
                $initials .= strtoupper(substr($p, 0, 1));
            }
        }

        $notifications = collect();
        if ($user) {
            $titipanMsg = [
                'menunggu_pembayaran' => fn ($o) => "Pesanan #{$o->code()} menunggu pembayaran",
                'penjadwalan_penjemputan' => fn ($o) => "Pesanan #{$o->code()} dalam proses penjemputan",
                'dalam_gudang' => fn ($o) => 'Barangmu aman tersimpan di gudang RuangTitip',
                'proses_pengembalian' => fn ($o) => "Pesanan #{$o->code()} sedang diproses pengembalian",
                'selesai' => fn ($o) => "Pesanan #{$o->code()} telah selesai",
            ];
            foreach (TitipanOrder::where('user_id', $user->id)->latest('updated_at')->take(5)->get() as $o) {
                if ($msg = $titipanMsg[$o->status] ?? null) {
                    $notifications->push(['text' => $msg($o), 'at' => $o->updated_at]);
                }
            }

            foreach (PackingOrder::where('user_id', $user->id)->latest('updated_at')->take(5)->get() as $o) {
                $text = match (true) {
                    $o->payment_status === 'PAID' => "Pembayaran pesanan #{$o->order_code} berhasil dikonfirmasi",
                    in_array($o->payment_status, ['FAILED', 'EXPIRED']) => "Pembayaran pesanan #{$o->order_code} gagal/kedaluwarsa",
                    default => "Pesanan #{$o->order_code} menunggu pembayaran",
                };
                $notifications->push(['text' => $text, 'at' => $o->updated_at]);
            }

            foreach (Order::where('customer_email', $user->email)->latest('updated_at')->take(5)->get() as $o) {
                $text = match (true) {
                    $o->status === 'delivered' => "Pesanan #{$o->order_number} telah selesai",
                    $o->status === 'shipped' => "Pesanan #{$o->order_number} sedang dikirim",
                    $o->status === 'processing' => "Pesanan #{$o->order_number} sedang diproses",
                    $o->payment_status === 'PAID' => "Pembayaran pesanan #{$o->order_number} berhasil dikonfirmasi",
                    in_array($o->payment_status, ['FAILED', 'EXPIRED']) => "Pembayaran pesanan #{$o->order_number} gagal/kedaluwarsa",
                    default => "Pesanan #{$o->order_number} menunggu pembayaran",
                };
                $notifications->push(['text' => $text, 'at' => $o->updated_at]);
            }

            $notifications = $notifications->sortByDesc('at')->take(5)->values();
        }
        $notifUnreadCount = $notifications->filter(fn ($n) => $n['at'] && $n['at']->gt(now()->subDays(2)))->count();

        $cartCount = count(session('cart', []));

        return view('dashboard.index', compact(
            'user', 'storages', 'packing', 'preloved', 'testimonials',
            'initials', 'notifications', 'notifUnreadCount', 'cartCount'
        ));
    }
}
