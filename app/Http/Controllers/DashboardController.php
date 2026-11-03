<?php

namespace App\Http\Controllers;

use App\Models\PackingProduct;
use App\Models\PrelovedItem;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use App\Support\UserNotifications;
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
            ['text' => 'Barang-barang kos saya aman banget di RuangTitip. Pulang ke Surabaya 3 bulan, semua masih mulus dan tersegel.', 'name' => 'Anisa Rahmawati', 'major' => 'Teknik Informatika, UB 2022', 'avatar' => 'AR'],
            ['text' => 'Proses jemput cepat, admin responsif. Biayanya jauh lebih murah dibanding bayar kos kosong.', 'name' => 'Budi Santoso', 'major' => 'Manajemen, UB 2021', 'avatar' => 'BS'],
            ['text' => 'Saya pakai layanan preloved juga. Barang bekas kos terjual dalam 2 hari, dapat uang dari jual barang.', 'name' => 'Cahya Putri', 'major' => 'Ilmu Komunikasi, UB 2023', 'avatar' => 'CP'],
        ]);

        $notifications = UserNotifications::for($user);

        // Titipan yang sedang berjalan: yang sudah di gudang dan paling cepat berakhir.
        $activeOrder = null;
        $pendingOrders = collect();

        if ($user) {
            $activeOrder = TitipanOrder::with('storage')
                ->where('user_id', $user->id)
                ->where('status', 'dalam_gudang')
                ->orderBy('date_end')
                ->first();

            $pendingOrders = TitipanOrder::with('storage')
                ->where('user_id', $user->id)
                ->whereIn('status', ['menunggu_pembayaran', 'penjadwalan_penjemputan'])
                ->latest('updated_at')
                ->take(4)
                ->get();
        }

        $warehouseOrders = $user
            ? TitipanOrder::with('storage')
                ->where('user_id', $user->id)
                ->where('status', 'dalam_gudang')
                ->orderBy('date_end')
                ->take(5)
                ->get()
            : collect();

        $profileIncomplete = $user && (empty($user->name) || empty($user->phone));

        return view('dashboard.index', compact(
            'user', 'storages', 'packing', 'preloved', 'testimonials',
            'notifications', 'activeOrder', 'pendingOrders', 'warehouseOrders', 'profileIncomplete'
        ));
    }
}
