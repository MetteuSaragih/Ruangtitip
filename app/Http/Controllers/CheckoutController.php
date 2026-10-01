<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use App\Services\BiteshipService;
use App\Services\DistanceService;
use App\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(private DistanceService $distance)
    {
    }

    public function shipping()
    {
        $cart = $this->checkoutCart();
        if (empty($cart)) {
            return redirect()->route('preloved.cart.index');
        }
        $cartCount = count($cart);
        $shipping = session('checkout_shipping', []);

        return view('checkout.shipping', compact('cart', 'cartCount', 'shipping'));
    }

    public function chooseShipping(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|in:pickup,biteship',
        ]);

        if ($request->input('shipping_method') === 'pickup') {
            session(['checkout_shipping' => [
                'method' => 'pickup',
                'address' => [
                    'id' => null,
                    'full' => null,
                    'note' => null,
                ],
                'cost' => 0,
                'courier' => null,
                'service' => null,
            ]]);

            return redirect()->route('checkout.payment');
        }

        session()->forget('checkout_shipping');
        session(['checkout_shipping_method' => 'biteship']);

        return redirect()->route('checkout.address');
    }

    public function address()
    {
        $cart = $this->checkoutCart();
        if (empty($cart)) {
            return redirect()->route('preloved.cart.index');
        }

        $shipping = session('checkout_shipping', []);
        if (session('checkout_shipping_method') !== 'biteship' && ($shipping['method'] ?? null) !== 'biteship') {
            return redirect()->route('checkout.shipping');
        }

        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get();

        return view('checkout.address', compact('addresses', 'cart', 'shipping'));
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:select,new',
            'address_id' => 'nullable|required_if:mode,select|exists:user_addresses,id',
            'address.full' => 'nullable|required_if:mode,new|string|max:500',
            'address.note' => 'nullable|string|max:500',
            'address.area_id' => 'nullable|required_if:mode,new|string',
            'address.area_name' => 'nullable|string',
            'address.postal_code' => 'nullable|string',
            'label' => 'nullable|string|max:50',
            'is_primary' => 'nullable|boolean',
        ]);

        $address = null;

        if ($request->input('mode') === 'new') {
            if ($request->boolean('is_primary')) {
                UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
            }

            $address = UserAddress::create([
                'user_id' => Auth::id(),
                'label' => $request->input('label', 'Alamat'),
                'address' => $request->input('address.full'),
                'note' => $request->input('address.note'),
                'is_primary' => $request->boolean('is_primary'),
                'area_id' => $request->input('address.area_id'),
                'area_name' => $request->input('address.area_name'),
                'postal_code' => $request->input('address.postal_code'),
            ]);
        } elseif ($request->filled('address_id')) {
            $address = UserAddress::where('user_id', Auth::id())->findOrFail($request->input('address_id'));
        }

        if (! $address || ! $address->area_id) {
            return back()->withErrors(['address' => 'Pilih kecamatan/kota tujuan terlebih dahulu.'])->withInput();
        }
        $address = $this->distance->ensureAddressCoords($address);

        session(['checkout_shipping' => [
            'method' => 'biteship',
            'address' => [
                'id' => $address->id,
                'full' => $address->address,
                'note' => $address->note,
                'area_id' => $address->area_id,
                'area_name' => $address->area_name,
                'postal_code' => $address->postal_code,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
            ],
        ]]);
        session()->forget('checkout_shipping_method');

        return redirect()->route('checkout.courier');
    }

    public function courier()
    {
        $cart = $this->checkoutCart();
        $shipping = session('checkout_shipping', []);
        if (empty($shipping['address']['area_id'])) {
            return redirect()->route('checkout.address');
        }

        return view('checkout.courier', ['address' => $shipping['address']['full'] ?? '', 'cart' => $cart, 'shipping' => $shipping]);
    }

    public function courierRates(BiteshipService $biteship)
    {
        $cart = $this->checkoutCart();
        $shipping = session('checkout_shipping', []);
        if (empty($cart) || empty($shipping['address']['area_id'])) {
            return response()->json(['success' => false, 'message' => 'Alamat belum dipilih.'], 422);
        }

        $result = $biteship->getRates(
            $this->originWarehouse(),
            $this->destinationFromShipping($shipping),
            $this->cartToItems($cart),
            config('biteship.checkout_couriers')
        );

        return response()->json($result);
    }

    public function chooseCourier(Request $request, BiteshipService $biteship)
    {
        $data = $request->validate([
            'courier_code' => 'required|string',
            'service_code' => 'required|string',
        ]);

        $cart = $this->checkoutCart();
        $shipping = session('checkout_shipping', []);

        $rates = $biteship->getRates(
            $this->originWarehouse(),
            $this->destinationFromShipping($shipping),
            $this->cartToItems($cart),
            config('biteship.checkout_couriers')
        );

        $picked = collect($rates['pricing'] ?? [])->first(fn ($p) => $p['courier_code'] === $data['courier_code']
            && ($p['courier_service_code'] ?? null) === $data['service_code']);

        if (! $picked) {
            return back()->withErrors(['courier_code' => 'Tarif kurir tidak valid atau sudah berubah, silakan pilih ulang.']);
        }

        $shipping['cost'] = (int) $picked['price'];
        $shipping['courier'] = $picked['courier_code'];
        $shipping['courier_name'] = $picked['courier_name'] ?? $picked['courier_code'];
        $shipping['service'] = $picked['courier_service_code'] ?? null;
        $shipping['company'] = $picked['courier_code'];
        session(['checkout_shipping' => $shipping]);

        return redirect()->route('checkout.payment');
    }

    public function payment()
    {
        $cart = $this->checkoutCart();
        $shipping = session('checkout_shipping', []);

        if (empty($cart) || empty($shipping)) {
            return redirect()->route('checkout.shipping');
        }

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $serviceFee = $this->serviceFeeFor($shipping['method'] ?? 'pickup');
        $shippingCost = $shipping['cost'] ?? 0;
        $total = $subtotal + $serviceFee + $shippingCost;

        $cartCount = count($cart);

        return view('checkout.payment', compact('cart', 'shipping', 'subtotal', 'serviceFee', 'shippingCost', 'total', 'cartCount'));
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'payment_method' => 'required|string',
            'pickup_date' => 'nullable|date|after_or_equal:today',
            'pickup_time' => 'nullable|string',
        ]);

        $cart = $this->checkoutCart();
        $shipping = session('checkout_shipping', []);

        if (empty($cart) || empty($shipping)) {
            return response()->json(['success' => false, 'message' => 'Sesi checkout tidak valid.'], 422);
        }

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $serviceFee = $this->serviceFeeFor($shipping['method'] ?? 'pickup');
        $shippingCost = $shipping['cost'] ?? 0;
        $total = $subtotal + $serviceFee + $shippingCost;

        $order = Order::create([
            'order_number' => 'RT-' . now()->year . '-' . random_int(10000, 99999),
            'user_id' => Auth::id(),
            'customer_name' => Auth::user()->name ?? 'Pelanggan',
            'customer_email' => Auth::user()->email ?? 'noreply@rutip.test',
            'customer_phone' => Auth::user()->phone ?? null,
            'items' => array_values($cart),
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'shipping_method' => $shipping['method'],
            'shipping_address' => $shipping['address'] ?? null,
            'courier_code' => $shipping['courier'] ?? null,
            'courier_name' => $shipping['courier_name'] ?? null,
            'courier_service_code' => $shipping['service'] ?? null,
            'pickup_date' => $data['pickup_date'] ?? null,
            'pickup_time' => $data['pickup_time'] ?? null,
            'payment_method' => $data['payment_method'],
            'payment_status' => 'UNPAID',
            'status' => Order::STATUS_PENDING,
        ]);

        $orderItems = collect($cart)->map(fn ($item) => [
            'name' => mb_substr($item['name'], 0, 50),
            'price' => (int) $item['price'],
            'quantity' => (int) $item['qty'],
        ])->values()->all();
        $orderItems[] = ['name' => 'Biaya Layanan dan Platform', 'price' => $serviceFee, 'quantity' => 1];
        if ($shippingCost > 0) {
            $orderItems[] = ['name' => 'Biaya Pengiriman', 'price' => (int) $shippingCost, 'quantity' => 1];
        }

        try {
            $tripay = app(TripayService::class);
            $result = $tripay->createTransaction([
                'method' => $data['payment_method'],
                'merchant_ref' => $order->order_number,
                'amount' => $total,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone ?? '0800000000',
                'order_items' => $orderItems,
                'callback_url' => route('tripay.callback'),
                'return_url' => route('checkout.success', $order->order_number),
            ]);

            $order->update([
                'tripay_reference' => $result['reference'] ?? null,
                'tripay_checkout_url' => $result['checkout_url'] ?? null,
                'tripay_pay_code' => $result['pay_code'] ?? null,
                'tripay_payment_method' => $data['payment_method'],
            ]);
        } catch (\Throwable $e) {
            report($e);
            $order->update(['payment_status' => 'FAILED']);

            return response()->json(['success' => false, 'message' => 'Gagal memulai pembayaran: ' . $e->getMessage()], 500);
        }

        // Hanya hapus item yang benar-benar di-checkout; sisanya tetap di keranjang.
        $cartSession = session('cart', []);
        foreach (array_keys($cart) as $key) {
            unset($cartSession[$key]);
        }
        session(['cart' => $cartSession]);
        session()->forget(['checkout_cart', 'checkout_shipping']);

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

    /** Biaya layanan platform: Rp 2.000 jika pengiriman pakai Biteship, Rp 1.000 jika tidak. */
    private function serviceFeeFor(string $shippingMethod): int
    {
        return $shippingMethod === 'biteship' ? 2000 : 1000;
    }

    private function cartToItems(array $cart): array
    {
        return collect($cart)->map(fn ($item) => [
            'name' => $item['name'],
            'value' => (int) $item['price'],
            'quantity' => (int) $item['qty'],
            'weight' => (int) ($item['weight'] ?? 1000),
            'length' => (int) ($item['length'] ?? 30),
            'width' => (int) ($item['width'] ?? 20),
            'height' => (int) ($item['height'] ?? 15),
        ])->values()->all();
    }

    /** Titik asal Biteship: gudang pusat RuTip, lengkap dengan koordinat. */
    private function originWarehouse(): array
    {
        $warehouse = $this->distance->warehouseCoords();

        return array_filter([
            'area_id' => config('biteship.warehouse.area_id'),
            'postal_code' => config('biteship.warehouse.postal_code'),
            'latitude' => $warehouse['lat'] ?? null,
            'longitude' => $warehouse['lng'] ?? null,
        ]);
    }

    /** Titik tujuan Biteship: alamat pelanggan, lengkap dengan koordinat kalau sudah ter-geocode. */
    private function destinationFromShipping(array $shipping): array
    {
        $address = $shipping['address'] ?? [];

        return array_filter([
            'area_id' => $address['area_id'] ?? null,
            'postal_code' => $address['postal_code'] ?? null,
            'latitude' => $address['latitude'] ?? null,
            'longitude' => $address['longitude'] ?? null,
        ]);
    }
}
