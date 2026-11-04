<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\PackingProduct;
use App\Models\PrelovedSubmission;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use App\Models\TitipanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    /** Jalankan closure; kembalikan nilai bawaan bila gagal (mis. tabel belum dimigrasi). */
    private function safe(callable $fn, $default = 0)
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public function index()
    {
        $safe = fn (callable $fn, $default = 0) => $this->safe($fn, $default);

        // KPI
        $barangDiGudang = (int) $safe(fn () => TitipanOrder::where('status', 'dalam_gudang')->get(['id', 'items'])->sum(fn (TitipanOrder $o) => $o->totalItems()));

        $totalCapacity = (int) $safe(fn () => StorageRoom::sum('capacity_total'));
        $usedCapacity = (int) $safe(fn () => StorageRoom::sum('capacity_used'));
        $okupansi = $totalCapacity > 0 ? (int) round(($usedCapacity / $totalCapacity) * 100) : 0;

        $monthStart = now()->startOfMonth();
        $pendapatanTitip = (int) $safe(fn () => TitipanOrder::where('payment_status', 'PAID')->whereBetween('created_at', [$monthStart, now()])->sum('total'));
        $pendapatanPreloved = (int) $safe(fn () => $this->prelovedRevenue(Order::where('payment_status', 'PAID')->whereBetween('created_at', [$monthStart, now()])->get()));
        $pendapatan = $pendapatanTitip + $pendapatanPreloved;

        $unpaid = ['UNPAID', 'pending'];
        $tagihanJumlah = (int) $safe(fn () => TitipanOrder::where('status', 'menunggu_pembayaran')->count()
            + Order::whereIn('payment_status', $unpaid)->count()
            + PackingOrder::whereIn('payment_status', $unpaid)->count());
        $tagihanNilai = (int) $safe(fn () => TitipanOrder::where('status', 'menunggu_pembayaran')->sum('total')
            + Order::whereIn('payment_status', $unpaid)->sum('total')
            + PackingOrder::whereIn('payment_status', $unpaid)->sum('total'));

        // Perlu dikerjakan
        $tugas = [];
        $addTask = function (int $n, string $title, string $hint, string $route, array $params = []) use (&$tugas) {
            if ($n > 0) {
                $tugas[] = [
                    'n' => $n,
                    'title' => $title,
                    'hint' => $hint,
                    'href' => Route::has($route) ? route($route, $params) : null,
                ];
            }
        };
        $addTask((int) $safe(fn () => TitipanOrder::where('status', 'menunggu_pembayaran')->count()), 'Pesanan menunggu pembayaran', 'Pantau pembayaran pelanggan', 'admin.titip.index', ['tab' => 'menunggu_pembayaran']);
        $addTask((int) $safe(fn () => TitipanOrder::where('status', 'penjadwalan_penjemputan')->count()), 'Penjemputan perlu dijadwalkan', 'Barang menunggu dijemput atau diantar', 'admin.titip.index', ['tab' => 'penjadwalan_penjemputan']);
        $addTask((int) $safe(fn () => TitipanOrder::where('needs_admin_attention', true)->count()), 'Pesanan perlu tindakan admin', 'Pemesanan kurir gagal atau butuh pengecekan', 'admin.titip.index');
        $addTask((int) $safe(fn () => Schema::hasTable('titipan_requests') ? TitipanRequest::whereIn('status', ['menunggu', 'diproses'])->count() : 0), 'Permintaan titipan terbuka', 'Perpanjang, kirim, atau ambil barang', 'admin.permintaan.index');
        $addTask((int) $safe(fn () => Schema::hasTable('preloved_submissions') ? PrelovedSubmission::where('status', 'seleksi')->count() : 0), 'Pengajuan jual menunggu seleksi', 'Barang preloved dari pelanggan', 'admin.jual.index');
        $addTask((int) $safe(fn () => PackingProduct::whereColumn('stock', '<=', 'low_threshold')->count()), 'Stok packing menipis', 'Produk di bawah batas minimum', 'admin.packing.index');

        // Pendapatan 14 hari terakhir
        $start = now()->subDays(13)->startOfDay();
        $titipRows = $safe(fn () => TitipanOrder::where('payment_status', 'PAID')->where('created_at', '>=', $start)->get(['id', 'total', 'created_at']), collect());
        $prelovedRows = $safe(fn () => Order::where('payment_status', 'PAID')->where('created_at', '>=', $start)->get(), collect());
        $chart = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $key = $d->toDateString();
            $chart[] = [
                'label' => $d->format('d'),
                'titip' => (int) $titipRows->filter(fn ($o) => $o->created_at->toDateString() === $key)->sum('total'),
                'preloved' => $this->prelovedRevenue($prelovedRows->filter(fn ($o) => $o->created_at->toDateString() === $key)),
            ];
        }
        $chartMax = max(1, collect($chart)->max(fn ($c) => max($c['titip'], $c['preloved'])));

        // Kapasitas per ruang
        $rooms = $safe(fn () => StorageRoom::orderBy('id')->get(), collect());

        // Aktivitas terbaru
        $activity = collect();
        $safe(function () use (&$activity) {
            TitipanOrder::with('user')->latest()->limit(6)->get()->each(function (TitipanOrder $o) use (&$activity) {
                $activity->push([
                    'at' => $o->created_at,
                    'text' => 'Pesanan ' . $o->code() . ' dari ' . ($o->user?->name ?? 'pelanggan'),
                    'sub' => $o->statusMeta()['label'],
                    'href' => route('admin.titip.show', $o),
                ]);
            });
            if (Schema::hasTable('titipan_requests')) {
                TitipanRequest::with(['user', 'order'])->latest()->limit(6)->get()->each(function (TitipanRequest $r) use (&$activity) {
                    $activity->push([
                        'at' => $r->created_at,
                        'text' => $r->typeLabel() . ' ' . ($r->order?->code() ?? '-') . ' oleh ' . ($r->user?->name ?? 'pelanggan'),
                        'sub' => $r->statusLabel(),
                        'href' => $r->order ? route('admin.titip.show', $r->order) : null,
                    ]);
                });
            }
        });
        $activity = $activity->sortByDesc('at')->take(8)->values();

        return view('admin.dashboard', compact(
            'barangDiGudang', 'okupansi', 'pendapatan', 'tagihanJumlah', 'tagihanNilai',
            'tugas', 'chart', 'chartMax', 'rooms', 'activity'
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

    private function prelovedRevenue($orders): int
    {
        return (int) collect($orders)->sum(function (Order $o) {
            return collect($o->items)
                ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
                ->sum(fn ($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
        });
    }
}
