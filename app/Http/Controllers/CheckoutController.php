<?php

namespace App\Http\Controllers;

use App\Services\AdminNotifier;
use App\Models\Order;
use App\Models\PrelovedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| CheckoutController (Toko Preloved)
|--------------------------------------------------------------------------
| Barang selalu diambil sendiri di gudang (tanpa kurir, alamat, atau ongkir):
|  1. payment  ringkasan pesanan dan pilih metode (QRIS)
|  2. process  buat pesanan (UNPAID), pelanggan lanjut mengunggah bukti bayar
|  3. success  halaman pembayaran dan status pesanan
*/
class CheckoutController extends Controller
{
    private const SERVICE_FEE = 1000; // biaya layanan per pesanan (jemput sendiri)

    public function payment()
    {
        $gone = $this->pruneUnavailable();
        $cart = $this->checkoutCart();

        if ($gone) {
            return redirect()->route('preloved.cart.index')->withErrors(['selected' => $gone]);
        }

        if (empty($cart)) {
            return redirect()->route('preloved.cart.index')->withErrors(['selected' => 'Pilih produk dulu sebelum checkout.']);
        }

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $serviceFee = self::SERVICE_FEE;
        $total = $subtotal + $serviceFee;
        $cartCount = count($cart);

        return view('checkout.payment', compact('cart', 'subtotal', 'serviceFee', 'total', 'cartCount'));
    }

    public function process(Request $request)
    {
        if ($gone = $this->pruneUnavailable()) {
            return response()->json(['success' => false, 'message' => $gone], 409);
        }

        $cart = $this->checkoutCart();

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Sesi checkout tidak valid.'], 422);
        }

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $serviceFee = self::SERVICE_FEE;
        $total = $subtotal + $serviceFee;

        $order = Order::create([
            'order_number' => $this->newOrderNumber(),
            'user_id' => Auth::id(),
            'customer_name' => Auth::user()->name ?? 'Pelanggan',
            'customer_email' => Auth::user()->email ?? 'noreply@rutip.test',
            'customer_phone' => Auth::user()->phone ?? null,
            'items' => array_values($cart),
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'shipping_cost' => 0,
            'total' => $total,
            'shipping_method' => 'pickup',
            'shipping_address' => null,
            'courier_code' => null,
            'courier_name' => null,
            'courier_service_code' => null,
            'pickup_date' => null,
            'pickup_time' => null,
            'payment_method' => 'qris',
            'payment_status' => 'UNPAID',
            'status' => Order::STATUS_PENDING,
        ]);

        AdminNotifier::newPreloved($order);

        // Hanya hapus item yang benar-benar di-checkout; sisanya tetap di keranjang.
        $cartSession = session('cart', []);
        foreach (array_keys($cart) as $key) {
            unset($cartSession[$key]);
        }
        session(['cart' => $cartSession]);
        session()->forget(['checkout_cart', 'checkout_shipping', 'checkout_shipping_method']);

        return response()->json([
            'success' => true,
            'redirect' => route('checkout.success', $order->order_number),
        ]);
    }

    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartCount = count(session('cart', []));

        return view('checkout.success', ['order' => $order, 'cartCount' => $cartCount]);
    }

    private function checkoutCart(): array
    {
        return session('checkout_cart', []);
    }

    /**
     * Buang barang preloved yang sudah terjual, di-Draft, dihapus, atau dicadangkan
     * pesanan belum dibayar milik pembeli lain, dari keranjang checkout dan keranjang.
     * Mengembalikan pesan untuk pengguna, atau null bila semua barang masih tersedia.
     */
    private function pruneUnavailable(): ?string
    {
        $checkout = $this->checkoutCart();
        $ids = collect($checkout)
            ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
            ->pluck('id')->map(fn ($id) => (int) $id)->all();
        if (empty($ids)) {
            return null;
        }

        $available = PrelovedItem::whereIn('id', $ids)->where('status', 'Tersedia')->pluck('id')->all();
        $available = array_diff($available, Order::reservedPrelovedIds(Auth::id()));

        $removed = [];
        foreach ($checkout as $key => $item) {
            if (($item['type'] ?? 'preloved') === 'preloved' && ! in_array((int) $item['id'], $available, true)) {
                $removed[] = $item['name'];
                unset($checkout[$key]);
                $cart = session('cart', []);
                unset($cart[$key]);
                session(['cart' => $cart]);
            }
        }

        if (empty($removed)) {
            return null;
        }

        session(['checkout_cart' => $checkout]);

        return implode(', ', $removed) . ' sudah tidak tersedia dan dikeluarkan dari pesananmu.';
    }

    /** Nomor pesanan unik: coba beberapa kali, lalu perpanjang agar tidak pernah bentrok. */
    private function newOrderNumber(): string
    {
        $prefix = 'RT-' . now()->year . '-';
        for ($i = 0; $i < 8; $i++) {
            $code = $prefix . random_int(10000, 99999);
            if (! Order::where('order_number', $code)->exists()) {
                return $code;
            }
        }

        return $prefix . random_int(10000, 99999) . random_int(100, 999);
    }
}
