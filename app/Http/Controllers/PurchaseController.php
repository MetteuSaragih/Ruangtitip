<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

/**
 * Halaman "Pembelian Saya": pesanan Toko Preloved milik pengguna.
 * Data diambil dari tabel orders (hasil checkout keranjang).
 */
class PurchaseController extends Controller
{
    /** Label dan warna badge per status, sama dengan prototipe (OS). */
    public const STATUS = [
        'bayar' => ['Menunggu Pembayaran', 'warn'],
        'serah' => ['Terjual - Menunggu Penyerahan', 'info'],
        'kirim' => ['Dalam Pengiriman', 'info'],
        'selesai' => ['Selesai', 'mute'],
        'exp' => ['Kedaluwarsa', 'mute'],
    ];

    public function index()
    {
        $orders = $this->mine()->map(fn (Order $order) => $this->present($order));

        return view('purchases.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 404);
        abort_unless($this->hasPreloved($order), 404);

        return view('purchases.show', ['o' => $this->present($order), 'order' => $order]);
    }

    private function mine()
    {
        return Order::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->filter(fn (Order $order) => $this->hasPreloved($order))
            ->values();
    }

    private function hasPreloved(Order $order): bool
    {
        return collect($order->items ?? [])->contains(fn ($item) => ($item['type'] ?? 'preloved') === 'preloved');
    }

    private function statusKey(Order $order): string
    {
        $pay = strtoupper((string) $order->payment_status);

        if (in_array($pay, ['EXPIRED', 'FAILED', 'REFUND'], true) || $order->status === Order::STATUS_CANCELLED) {
            return 'exp';
        }

        if ($pay !== 'PAID' && $pay !== 'SUCCESS') {
            return 'bayar';
        }

        return match ($order->status) {
            Order::STATUS_SHIPPED => 'kirim',
            Order::STATUS_DELIVERED => 'selesai',
            default => 'serah',
        };
    }

    private function present(Order $order): array
    {
        $key = $this->statusKey($order);
        $items = collect($order->items ?? [])->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')->values();
        $kirim = ($order->shipping_method ?? 'pickup') !== 'pickup';

        return [
            'id' => $order->id,
            'number' => $order->order_number,
            'key' => $key,
            'status_label' => self::STATUS[$key][0],
            'status_tone' => self::STATUS[$key][1],
            'kirim' => $kirim,
            'method' => $kirim ? 'Dikirim Ruang Titip' : 'Ambil sendiri',
            'items' => $items->all(),
            'first' => $items->first(),
            'total' => (int) $order->total,
            'created' => $order->created_at,
            'pay_url' => $key === 'bayar' ? $order->tripay_checkout_url : null,
            'tracking' => $order->biteship_tracking_id,
            'courier' => $order->courier_name,
            'address' => $order->shipping_address['full'] ?? null,
        ];
    }
}
