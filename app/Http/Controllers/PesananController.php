<?php

namespace App\Http\Controllers;

use App\Models\TitipanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| PesananController — halaman "Pesanan Saya"
|--------------------------------------------------------------------------
| Dua tab:
|   - berlangsung : semua pesanan dgn status != 'selesai'
|   - selesai     : status == 'selesai'
|
| Saat ini hanya pesanan Penitipan (titipan_orders) yang punya data.
| Toko Packing & Preloved disiapkan strukturnya, ditampilkan kosong dulu.
*/

class PesananController extends Controller
{
    public function index(Request $r)
    {
        $tab = $r->query('tab', 'berlangsung'); // berlangsung | selesai
        if (!in_array($tab, ['berlangsung', 'selesai'], true)) {
            $tab = 'berlangsung';
        }

        $base = TitipanOrder::with('storage')
            ->where('user_id', Auth::id())
            ->latest();

        if ($tab === 'selesai') {
            $orders = (clone $base)->where('status', 'selesai')->get();
        } else {
            $orders = (clone $base)->where('status', '!=', 'selesai')->get();
        }

        // hitung jumlah untuk badge tab
        $countBerlangsung = TitipanOrder::where('user_id', Auth::id())->where('status', '!=', 'selesai')->count();
        $countSelesai     = TitipanOrder::where('user_id', Auth::id())->where('status', 'selesai')->count();

        return view('dashboard.pesanan.index', compact('orders', 'tab', 'countBerlangsung', 'countSelesai'));
    }

    public function show(TitipanOrder $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load('storage');
        return view('dashboard.pesanan.detail', compact('order'));
    }
}
