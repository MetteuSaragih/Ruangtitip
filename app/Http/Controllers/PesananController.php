<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    public function index(Request $r)
    {
        $tab = $r->query('tab', 'berlangsung');
        if (!in_array($tab, ['berlangsung', 'selesai'], true)) {
            $tab = 'berlangsung';
        }

        $type = $r->query('jenis', 'semua');
        if (!in_array($type, ['semua', 'titip', 'packing', 'preloved'], true)) {
            $type = 'semua';
        }

        $uid = Auth::id();

        $titipanBase = TitipanOrder::with('storage')->where('user_id', $uid)->latest();
        $packingBase = PackingOrder::where('user_id', $uid)->latest();
        $orderBase = Order::where('user_id', $uid)->latest();

        if ($tab === 'selesai') {
            $orders        = (clone $titipanBase)->where('status', 'selesai')->get();
            $packingOrders = (clone $packingBase)->where('status', 'selesai')->get();
            $genericOrders = (clone $orderBase)->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED])->get();
        } else {
            $orders        = (clone $titipanBase)->where('status', '!=', 'selesai')->get();
            $packingOrders = (clone $packingBase)->where('status', '!=', 'selesai')->get();
            $genericOrders = (clone $orderBase)->whereNotIn('status', [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED])->get();
        }

        if ($type !== 'semua') {
            if ($type === 'titip') {
                $packingOrders = $packingOrders->take(0);
                $genericOrders = $genericOrders->take(0);
            } elseif ($type === 'packing') {
                $orders = $orders->take(0);
                $genericOrders = $genericOrders->filter(fn (Order $o) => collect($o->items)->contains(fn ($i) => ($i['type'] ?? null) === 'packing'));
            } elseif ($type === 'preloved') {
                $orders = $orders->take(0);
                $packingOrders = $packingOrders->take(0);
                $genericOrders = $genericOrders->filter(fn (Order $o) => collect($o->items)->contains(fn ($i) => ($i['type'] ?? null) === 'preloved'));
            }
        }

        $countBerlangsung = TitipanOrder::where('user_id', $uid)->where('status', '!=', 'selesai')->count()
                          + PackingOrder::where('user_id', $uid)->where('status', '!=', 'selesai')->count()
                          + Order::where('user_id', $uid)->whereNotIn('status', [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED])->count();
        $countSelesai     = TitipanOrder::where('user_id', $uid)->where('status', 'selesai')->count()
                          + PackingOrder::where('user_id', $uid)->where('status', 'selesai')->count()
                          + Order::where('user_id', $uid)->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_CANCELLED])->count();

        $activeTitipan = TitipanOrder::where('user_id', $uid)->where('status', 'dalam_gudang')->get();
        $soonestEnd = $activeTitipan->pluck('date_end')->filter()->sort()->first();
        $pendingPayment = TitipanOrder::where('user_id', $uid)->where('status', '!=', 'selesai')->whereIn('payment_status', ['UNPAID', 'pending', null])->count()
            + PackingOrder::where('user_id', $uid)->where('status', '!=', 'selesai')->whereIn('payment_status', ['UNPAID', 'pending', null])->count()
            + Order::where('user_id', $uid)->where('payment_status', 'UNPAID')->count();

        $overview = [
            'active' => $activeTitipan->count(),
            'soonest_end' => $soonestEnd,
            'soonest_end_days' => $soonestEnd ? max(0, (int) ceil(now()->startOfDay()->diffInDays($soonestEnd->copy()->startOfDay(), false))) : null,
            'pending_payment' => $pendingPayment,
        ];

        return view('dashboard.pesanan.index', compact(
            'orders', 'packingOrders', 'genericOrders', 'tab', 'type', 'countBerlangsung', 'countSelesai', 'overview'
        ));
    }

    public function show(TitipanOrder $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load('storage');
        return view('dashboard.pesanan.detail', compact('order'));
    }
}
