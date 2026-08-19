<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $prelovedOrders = Order::where('payment_status', 'PAID')
            ->get()
            ->filter(fn (Order $o) => collect($o->items)->contains(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved'));

        $prelovedRevenue = $prelovedOrders->sum(function (Order $o) {
            return collect($o->items)
                ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
                ->sum(fn ($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        });

        $pendapatanBulanIni = $this->pendapatanDalamRentang(now()->startOfMonth(), now(), $prelovedOrders);
        $pendapatanBulanLalu = $this->pendapatanDalamRentang(now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth(), $prelovedOrders);
        $pendapatanGrowth = $pendapatanBulanLalu > 0
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100)
            : ($pendapatanBulanIni > 0 ? 100 : 0);
        $pendapatan = $pendapatanBulanIni;

        $transaksiAktif = TitipanOrder::where('status', '!=', 'selesai')->count()
            + PackingOrder::where('payment_status', 'PAID')->count()
            + $prelovedOrders->where('status', '!=', 'delivered')->count();

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
                    + Order::whereDate('created_at', $dateString)->count(),
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
            'pendapatanGrowth',
            'transaksiAktif',
            'kapasitasGudang',
            'trenPesanan',
            'tugasPrioritas'
        ));
    }

    public function profile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        Auth::user()->update(['name' => $data['name']]);
        return back()->with('success', 'Profil berhasil disimpan!');
    }

    private function pendapatanDalamRentang($from, $to, $prelovedOrders): int
    {
        $prelovedRevenue = $prelovedOrders
            ->whereBetween('created_at', [$from, $to])
            ->sum(function (Order $o) {
                return collect($o->items)
                    ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
                    ->sum(fn ($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
            });

        return (int) (TitipanOrder::where('status', 'selesai')->whereBetween('created_at', [$from, $to])->sum('total')
            + PackingOrder::where('payment_status', 'PAID')->whereBetween('created_at', [$from, $to])->sum('total')
            + $prelovedRevenue);
    }
}
