<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemSize;
use App\Models\TitipanOrder;
use App\Models\TitipanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TitipanOrderController extends Controller
{
    public const TABS = [
        'semua' => 'Semua',
        'menunggu_pembayaran' => 'Menunggu bayar',
        'penjadwalan_penjemputan' => 'Penjadwalan jemput',
        'dalam_gudang' => 'Dalam gudang',
        'proses_pengembalian' => 'Pengembalian',
        'selesai' => 'Selesai',
    ];

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'semua');
        if (! array_key_exists($tab, self::TABS)) {
            $tab = 'semua';
        }
        $q = trim((string) $request->query('q', ''));

        $search = function ($query) use ($q) {
            if ($q === '') {
                return $query;
            }
            $codeId = preg_match('/^(?:rtp-?)?0*(\d+)$/i', $q, $m) ? (int) $m[1] : null;
            return $query->where(function ($w) use ($q, $codeId) {
                $w->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', '%' . $q . '%')->orWhere('email', 'like', '%' . $q . '%');
                });
                if ($codeId !== null) {
                    $w->orWhere('id', $codeId);
                }
            });
        };

        $counts = ['semua' => $search(TitipanOrder::query())->count()];
        foreach (array_keys(self::TABS) as $key) {
            if ($key !== 'semua') {
                $counts[$key] = $search(TitipanOrder::where('status', $key))->count();
            }
        }

        $query = TitipanOrder::with(['user', 'storage'])->latest();
        if ($tab !== 'semua') {
            $query->where('status', $tab);
        }
        $orders = $search($query)->paginate(15)->withQueryString();

        return view('admin.titip.index', [
            'orders' => $orders,
            'tab' => $tab,
            'tabs' => self::TABS,
            'counts' => $counts,
            'q' => $q,
        ]);
    }

    public function show(TitipanOrder $order)
    {
        $order->load(['user', 'storage']);

        $requests = collect();
        try {
            if (Schema::hasTable('titipan_requests')) {
                $requests = TitipanRequest::where('titipan_order_id', $order->id)->latest()->get();
            }
        } catch (\Throwable $e) {
            $requests = collect();
        }

        $sizes = [];
        try {
            $sizes = ItemSize::get()->mapWithKeys(fn ($s) => [$s->code => trim(ucfirst((string) $s->type) . ' ' . $s->label)])->all();
        } catch (\Throwable $e) {
            $sizes = [];
        }

        return view('admin.titip.show', [
            'order' => $order,
            'requests' => $requests,
            'sizes' => $sizes,
        ]);
    }
}
