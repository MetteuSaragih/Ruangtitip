<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingOrder;
use App\Models\PrelovedOrder;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use Carbon\CarbonPeriod;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendapatan = TitipanOrder::where('status', 'selesai')->sum('total')
            + PackingOrder::where('status', 'selesai')->sum('total')
            + PrelovedOrder::where('status', 'Selesai')->sum('price');

        $transaksiAktif = TitipanOrder::where('status', '!=', 'selesai')->count()
            + PackingOrder::where('status', '!=', 'selesai')->count()
            + PrelovedOrder::where('status', '!=', 'Selesai')->count();

        $totalCapacity = StorageRoom::sum('capacity_total');
        $usedCapacity = StorageRoom::sum('capacity_used');
        $kapasitasGudang = $totalCapacity > 0 ? (int) round(($usedCapacity / $totalCapacity) * 100) : 0;

        $period = CarbonPeriod::create(now()->subDays(6)->startOfDay(), now()->startOfDay());
        $trenPesanan = collect($period)->map(function ($date) {
            $dateString = $date->toDateString();

            return [
                'day' => $date->translatedFormat('D'),
                'pesanan' => TitipanOrder::whereDate('created_at', $dateString)->count()
                    + PackingOrder::whereDate('created_at', $dateString)->count()
                    + PrelovedOrder::whereDate('created_at', $dateString)->count(),
            ];
        })->values()->all();

        $tugasPrioritas = TitipanOrder::with('user')
            ->whereIn('status', ['menunggu_pembayaran', 'penjadwalan_penjemputan', 'proses_pengembalian'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (TitipanOrder $order) => [
                'id' => $order->code(),
                'badge' => $order->statusMeta()['label'],
                'customer' => $order->user?->name ?? 'Pelanggan',
                'wa' => $order->user?->phone ?? '-',
                'deadline' => optional($order->date_end)->format('d M Y') ?? '-',
                'href' => route('admin.ruang-titip'),
            ])
            ->all();

        return view('admin.dashboard', compact(
            'pendapatan',
            'transaksiAktif',
            'kapasitasGudang',
            'trenPesanan',
            'tugasPrioritas'
        ));
    }
}
