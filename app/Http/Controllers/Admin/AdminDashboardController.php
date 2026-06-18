<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Nanti diganti query database yang sesungguhnya
        $pendapatan     = 15450000;
        $transaksiAktif = 48;
        $kapasitasGudang = 75;

        $trenPesanan = [
            ['day' => 'Sen', 'pesanan' => 8],
            ['day' => 'Sel', 'pesanan' => 14],
            ['day' => 'Rab', 'pesanan' => 11],
            ['day' => 'Kam', 'pesanan' => 19],
            ['day' => 'Jum', 'pesanan' => 16],
            ['day' => 'Sab', 'pesanan' => 22],
            ['day' => 'Min', 'pesanan' => 13],
        ];

        $tugasPrioritas = [
            ['id'=>'#RTP-1092','badge'=>'Jadwal Jemput',        'customer'=>'Ahmad Rizki',   'wa'=>'0812-3456-7890','deadline'=>'Hari ini, 10:00 WIB','href'=>'/admin/ruang-titip'],
            ['id'=>'#RTP-1089','badge'=>'Batas Waktu Habis',    'customer'=>'Siti Rahayu',   'wa'=>'0856-1122-3344','deadline'=>'Hari ini, 11:30 WIB','href'=>'/admin/ruang-titip'],
            ['id'=>'#PKG-0341','badge'=>'Antar/Kirim Ekspedisi','customer'=>'Bima Pratama',  'wa'=>'0877-5566-7788','deadline'=>'Hari ini, 13:00 WIB','href'=>'/admin'],
            ['id'=>'#PL-0229', 'badge'=>'Jadwal Jemput',        'customer'=>'Nadia Kusuma',  'wa'=>'0821-9900-1122','deadline'=>'Hari ini, 14:00 WIB','href'=>'/admin'],
            ['id'=>'#RTP-1095','badge'=>'Antar/Kirim Ekspedisi','customer'=>'Fajar Nugraha', 'wa'=>'0813-4433-2211','deadline'=>'Hari ini, 15:30 WIB','href'=>'/admin/ruang-titip'],
        ];

        return view('admin.dashboard', compact(
            'pendapatan', 'transaksiAktif', 'kapasitasGudang',
            'trenPesanan', 'tugasPrioritas'
        ));
    }
}