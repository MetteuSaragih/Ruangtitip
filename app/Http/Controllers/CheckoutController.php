<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function shipping()
    {
        $cart = $this->checkoutCart();
        if (empty($cart)) {
            return redirect()->route('preloved.cart.index');
        }
        $cartCount = count($cart);

        return view('checkout.shipping', compact('cart', 'cartCount'));
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
        $couriers = Courier::where('group', 'instant')->orderBy('name')->get();

        return view('checkout.address', compact('addresses', 'couriers'));
    }

    public function saveShipping(Request $request)
    {
        $request->validate([
            'shipping_method' => 'required|in:biteship',
            'mode' => 'required|in:select,new',
            'address_id' => 'nullable|required_if:mode,select|exists:user_addresses,id',
            'address.full' => 'nullable|required_if:mode,new|string|max:500',
            'address.note' => 'nullable|string|max:500',
            'label' => 'nullable|string|max:50',
            'is_primary' => 'nullable|boolean',
            'courier' => 'required|exists:couriers,code',
            'service' => 'nullable|string|max:100',
        ]);

        $method = $request->input('shipping_method');
        $courier = Courier::where('group', 'instant')
            ->where('code', $request->input('courier'))
            ->firstOrFail();
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
            ]);
        } elseif ($request->filled('address_id')) {
            $address = UserAddress::where('user_id', Auth::id())->findOrFail($request->input('address_id'));
        }

        session(['checkout_shipping' => [
            'method' => $method,
            'address' => [
                'id' => $address?->id,
                'full' => $address?->address,
                'note' => $address?->note,
            ],
            'cost' => (int) $courier->price,
            'courier' => $courier->code,
            'service' => $request->input('service', $courier->service),
        ]]);

        session()->forget('checkout_shipping_method');

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'redirect' => route('checkout.payment')]);
        }

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
        $serviceFee = 1000;
        $shippingCost = $shipping['cost'] ?? 0;
        $total = $subtotal + $serviceFee + $shippingCost;

        $cartCount = count($cart);

        return view('checkout.payment', compact('cart', 'shipping', 'subtotal', 'serviceFee', 'shippingCost', 'total', 'cartCount'));
    }

    public function process(Request $request)
    {
        $cart = $this->checkoutCart();
        $shipping = session('checkout_shipping', []);
        $paymentMethod = $request->input('payment_method');

        $subtotal = array_sum(array_column($cart, 'subtotal'));
        $serviceFee = 1000;
        $shippingCost = $shipping['cost'] ?? 0;
        $total = $subtotal + $serviceFee + $shippingCost;

        $orderId = 'TP-' . date('Y') . '-' . rand(10000, 99999);

        // Store order in session for success page
        session([
            'last_order' => [
                'order_id' => $orderId,
                'cart' => $cart,
                'shipping' => $shipping,
                'payment_method' => $paymentMethod,
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'shipping_cost' => $shippingCost,
                'total' => $total,
            ]
        ]);

        // For Midtrans, return snap token. If Midtrans is not configured yet,
        // keep the local checkout flow usable by falling back to success.
        if ($paymentMethod !== 'pickup') {
            try {
                $snapToken = $this->createMidtransToken($orderId, $total, $cart);
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'order_id' => $orderId,
                ]);
            } catch (\Throwable $e) {
                // Fallback: simulate success
                return response()->json([
                    'success' => true,
                    'redirect' => route('checkout.success', $orderId),
                    'order_id' => $orderId,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'redirect' => route('checkout.success', $orderId),
        ]);
    }

    public function success($orderId)
    {
        $order = session('last_order');
        if (!$order) {
            return redirect()->route('preloved.cart.index');
        }

        // Only drop the items that were actually checked out; anything the
        // customer left unselected in the cart stays there for later.
        $cart = session('cart', []);
        foreach (array_keys($order['cart'] ?? []) as $key) {
            unset($cart[$key]);
        }
        session(['cart' => $cart]);
        session()->forget(['checkout_cart', 'checkout_shipping']);

        $cartCount = count($cart);
        return view('checkout.success', compact('order', 'orderId', 'cartCount'));
    }

    private function checkoutCart(): array
    {
        return session('checkout_cart', []);
    }

    private function createMidtransToken($orderId, $total, $cart)
    {
        $serverKey = config('services.midtrans.server_key');
        if (empty($serverKey)) {
            throw new \RuntimeException('Midtrans server key is not configured.');
        }

        $isProduction = config('services.midtrans.is_production', false);
        $baseUrl = $isProduction ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'id' => 'item-' . $item['id'],
                'price' => $item['price'],
                'quantity' => $item['qty'],
                'name' => substr($item['name'], 0, 50),
            ];
        }
        $items[] = ['id' => 'service-fee', 'price' => 1000, 'quantity' => 1, 'name' => 'Biaya Layanan dan Platform'];

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => 'Ahmad',
                'last_name' => 'Rizki',
                'email' => 'ahmad.rizki@student.ub.ac.id',
                'phone' => '081234567890',
            ],
        ];

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
            ->post($baseUrl, $payload);

        if ($response->successful()) {
            return $response->json()['token'];
        }

        throw new \Exception('Midtrans token creation failed: ' . $response->body());
    }
}
