<?php

namespace App\Http\Controllers;

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

        $uid = Auth::id();

        $titipanBase = TitipanOrder::with('storage')->where('user_id', $uid)->latest();
        $packingBase = PackingOrder::where('user_id', $uid)->latest();

        if ($tab === 'selesai') {
            $orders        = (clone $titipanBase)->where('status', 'selesai')->get();
            $packingOrders = (clone $packingBase)->where('status', 'selesai')->get();
        } else {
            $orders        = (clone $titipanBase)->where('status', '!=', 'selesai')->get();
            $packingOrders = (clone $packingBase)->where('status', '!=', 'selesai')->get();
        }

        $countBerlangsung = TitipanOrder::where('user_id', $uid)->where('status', '!=', 'selesai')->count()
                          + PackingOrder::where('user_id', $uid)->where('status', '!=', 'selesai')->count();
        $countSelesai     = TitipanOrder::where('user_id', $uid)->where('status', 'selesai')->count()
                          + PackingOrder::where('user_id', $uid)->where('status', 'selesai')->count();

        return view('dashboard.pesanan.index', compact('orders', 'packingOrders', 'tab', 'countBerlangsung', 'countSelesai'));
    }

    public function show(TitipanOrder $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load('storage');
        return view('dashboard.pesanan.detail', compact('order'));
    }
}
