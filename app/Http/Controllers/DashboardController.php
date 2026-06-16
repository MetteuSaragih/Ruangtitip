<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Halaman dashboard (Beranda) — meniru DashboardPage.tsx dari Figma.
     * Data masih dummy; nanti diganti query ke model Titipan/Produk dll.
     */
    public function index()
    {
        $user = Auth::user();

        $storages = [
            ['name' => 'Gudang Utama Soekarno-Hatta', 'address' => 'Jl. Soekarno-Hatta No. 12, Lowokwaru', 'rating' => 4.9, 'filled' => 72, 'price' => 35000, 'tags' => ['AC', 'CCTV', '24 Jam', 'WiFi'], 'emoji' => '🏢'],
            ['name' => 'Gudang Veteran', 'address' => 'Jl. Veteran No. 3, Ketawanggede', 'rating' => 4.8, 'filled' => 52, 'price' => 32000, 'tags' => ['CCTV', '24 Jam', 'Penjaga'], 'emoji' => '🏪'],
            ['name' => 'Gudang Dieng', 'address' => 'Jl. MT. Haryono No. 165, Dinoyo', 'rating' => 4.7, 'filled' => 88, 'price' => 30000, 'tags' => ['CCTV', 'Ventilasi'], 'emoji' => '🏬'],
        ];

        $packing = [
            ['name' => 'Kardus Single Wall', 'price' => 8500, 'stock' => 50, 'emoji' => '📦'],
            ['name' => 'Kardus Double Wall', 'price' => 15000, 'stock' => 30, 'emoji' => '🗃️'],
            ['name' => 'Lakban Coklat 5cm', 'price' => 12000, 'stock' => 100, 'emoji' => '🟫'],
            ['name' => 'Bubble Wrap 50m', 'price' => 75000, 'stock' => 20, 'emoji' => '🫧'],
        ];

        $preloved = [
            ['name' => 'Meja Belajar Lipat', 'price' => 150000, 'condition' => 'Seperti Baru', 'cColor' => '#059669', 'cBg' => 'rgba(5,150,105,0.12)', 'emoji' => '🪑'],
            ['name' => 'Rice Cooker Mini', 'price' => 85000, 'condition' => 'Bekas - Baik', 'cColor' => '#d97706', 'cBg' => 'rgba(217,119,6,0.12)', 'emoji' => '🍚'],
            ['name' => 'Rak Buku 4 Susun', 'price' => 200000, 'condition' => 'Seperti Baru', 'cColor' => '#059669', 'cBg' => 'rgba(5,150,105,0.12)', 'emoji' => '📚'],
            ['name' => 'Kipas Angin Meja', 'price' => 95000, 'condition' => 'Bekas - Baik', 'cColor' => '#d97706', 'cBg' => 'rgba(217,119,6,0.12)', 'emoji' => '🌀'],
        ];

        $testimonials = [
            ['name' => 'Aulia Rahmadani', 'major' => "Teknik Informatika '22", 'avatar' => 'AR', 'color' => '#7c3aed', 'text' => 'RUTIP beneran ngebantu banget! Waktu magang di Jakarta 3 bulan, barang aku aman tersimpan. Hemat hampir Rp 3 juta dari biaya kos kosong.'],
            ['name' => 'Bima Pratama', 'major' => "Manajemen Bisnis '21", 'avatar' => 'BP', 'color' => '#059669', 'text' => 'Prosesnya gampang banget. Jemput tepat waktu, barang dikemas rapi, dan bisa lihat foto barang yang sudah masuk gudang. Keren!'],
            ['name' => 'Citra Dewi Lestari', 'major' => "Psikologi '23", 'avatar' => 'CD', 'color' => '#6d28d9', 'text' => 'Customer service-nya fast response dan barang aku aman semua. Sangat merekomendasikan untuk teman-teman yang mau KKN!'],
        ];

        return view('dashboard.index', compact('user', 'storages', 'packing', 'preloved', 'testimonials'));
    }
}
