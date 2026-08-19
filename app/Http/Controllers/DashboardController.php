<?php

namespace App\Http\Controllers;

use App\Models\PackingProduct;
use App\Models\PrelovedItem;
use App\Models\StorageRoom;
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
            ['text' => 'Barang-barang kos saya aman banget di RUTIP. Pas pulang ke Surabaya 3 bulan, semua masih mulus dan tersegel. Rekomen banget!', 'name' => 'Anisa Rahmawati', 'major' => 'Teknik Informatika, UB 2022', 'avatar' => 'AR', 'color' => '#7c3aed'],
            ['text' => 'Proses jemput cepat, admin responsif. Biayanya jauh lebih murah dibanding bayar kos kosong. RUTIP solusi yang tepat buat mahasiswa!', 'name' => 'Budi Santoso', 'major' => 'Manajemen, UB 2021', 'avatar' => 'BS', 'color' => '#0284c7'],
            ['text' => 'Saya pakai layanan preloved juga, barang bekas kos terjual dalam 2 hari. Untung double — ga bayar kos kosong dan dapat uang dari jual barang!', 'name' => 'Cahya Putri', 'major' => 'Ilmu Komunikasi, UB 2023', 'avatar' => 'CP', 'color' => '#059669'],
        ]);

        return view('dashboard.index', compact('user', 'storages', 'packing', 'preloved', 'testimonials'));
    }
}
