<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\PackingOrder;
use App\Models\PackingProduct;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class PackingController extends Controller
{
    private array $payMethods = [
        ['id' => 'qris', 'label' => 'QRIS', 'desc' => 'Semua e-wallet', 'color' => '#7c3aed'],
        ['id' => 'va', 'label' => 'Virtual Account', 'desc' => 'BCA, BRI, Mandiri', 'color' => '#2563eb'],
        ['id' => 'ew', 'label' => 'E-Wallet', 'desc' => 'GoPay, OVO, Dana', 'color' => '#7c3aed'],
    ];

    public function index(Request $request)
    {
        $category = $request->query('kategori', 'Semua');
        $categories = PackingProduct::active()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->prepend('Semua')
            ->values()
            ->all();

        $products = PackingProduct::active()
            ->when($category !== 'Semua', fn ($query) => $query->where('category', $category))
            ->orderBy('id')
            ->get();

        return view('packing.index', compact('products', 'categories', 'category'));
    }

    public function show(PackingProduct $product)
    {
        abort_unless($product->is_active, 404);

        return view('packing.show', compact('product'));
    }

    public function buy(Request $request, PackingProduct $product)
    {
        abort_unless($product->is_active, 404);

        $qty = max(1, min((int) $request->input('qty', 1), $product->stock));

        session([
            'packing.checkout' => [
                'items' => [[
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $qty,
                ]],
            ],
        ]);

        return redirect()->route('packing.logistics');
    }

    public function logistics()
    {
        $checkout = $this->guardCheckout();
        if (! $checkout) {
            return redirect()->route('packing.index');
        }

        return view('packing.logistics', ['items' => $checkout['items']]);
    }

    public function chooseLogistics(Request $request)
    {
        $data = $request->validate([
            'logistic' => 'required|in:pickup,biteship',
        ]);

        $checkout = session('packing.checkout', []);
        $checkout['logistic'] = $data['logistic'];
        session(['packing.checkout' => $checkout]);

        return $data['logistic'] === 'pickup'
            ? redirect()->route('packing.payment')
            : redirect()->route('packing.address');
    }

    public function address()
    {
        $checkout = $this->guardCheckout();
        if (! $checkout || ($checkout['logistic'] ?? null) !== 'biteship') {
            return redirect()->route('packing.logistics');
        }

        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get();
        $couriers = Courier::where('group', 'instant')->orderBy('name')->get();

        return view('packing.address', compact('addresses', 'couriers'));
    }

    public function chooseAddress(Request $request)
    {
        $data = $request->validate([
            'mode' => 'required|in:select,new',
            'address_id' => 'nullable|required_if:mode,select|exists:user_addresses,id',
            'label' => 'nullable|string|max:50',
            'address' => 'nullable|required_if:mode,new|string|max:500',
            'note' => 'nullable|string|max:500',
            'is_primary' => 'nullable|boolean',
            'courier' => 'required|exists:couriers,code',
        ]);

        $courier = Courier::where('group', 'instant')->where('code', $data['courier'])->firstOrFail();
        $address = $this->resolveAddress($data);

        $checkout = session('packing.checkout', []);
        $checkout['address'] = $address->address;
        $checkout['address_id'] = $address->id;
        $checkout['courier'] = $courier->code;
        session(['packing.checkout' => $checkout]);

        return redirect()->route('packing.payment');
    }

    public function payment()
    {
        $checkout = $this->guardCheckout();
        if (! $checkout || ! isset($checkout['logistic'])) {
            return redirect()->route('packing.logistics');
        }

        [$subtotal, $shipping, $total, $courier] = $this->calcTotals($checkout);

        return view('packing.payment', [
            'items' => $checkout['items'],
            'logistic' => $checkout['logistic'],
            'courier' => $courier,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'payMethods' => $this->payMethods,
        ]);
    }

    public function pay(Request $request)
    {
        $data = $request->validate([
            'payment_method' => 'required|in:qris,va,ew,snap',
        ]);

        $checkout = $this->guardCheckout();
        if (! $checkout) {
            return redirect()->route('packing.index');
        }

        [$subtotal, $shipping, $total] = $this->calcTotals($checkout);

        $order = PackingOrder::create([
            'order_code' => 'TP-' . now()->year . '-' . random_int(10000, 99999),
            'user_id' => Auth::id(),
            'items' => $checkout['items'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $total,
            'logistic' => $checkout['logistic'],
            'courier' => $checkout['courier'] ?? null,
            'address' => $checkout['address'] ?? null,
            'payment_method' => $data['payment_method'],
            'status' => 'menunggu_pembayaran',
            'payment_status' => 'pending',
        ]);

        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds = config('midtrans.is_3ds');
        MidtransConfig::$curlOptions = [CURLOPT_SSL_VERIFYPEER => false];

        $itemDetails = collect($checkout['items'])->map(fn ($item) => [
            'id' => (string) ($item['product_id'] ?? 'item'),
            'price' => (int) $item['price'],
            'quantity' => (int) $item['qty'],
            'name' => mb_substr($item['name'], 0, 50),
        ])->values()->all();

        if ($shipping > 0) {
            $itemDetails[] = [
                'id' => 'shipping',
                'price' => (int) $shipping,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $total,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => Auth::user()->name ?? 'Pelanggan',
                    'email' => Auth::user()->email ?? 'noreply@rutip.test',
                ],
            ]);
            $order->update(['snap_token' => $snapToken]);
        } catch (\Throwable $e) {
            report($e);
            $order->update(['payment_status' => 'failure']);

            return redirect()->route('packing.payment')
                ->with('error', 'Gagal memulai pembayaran: ' . $e->getMessage());
        }

        session()->forget('packing.checkout');

        return redirect()->route('packing.success', $order->order_code);
    }

    public function success(string $orderCode)
    {
        $order = PackingOrder::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('packing.success', [
            'order' => $order,
            'clientKey' => config('midtrans.client_key'),
            'isProduction' => config('midtrans.is_production'),
        ]);
    }

    private function guardCheckout(): ?array
    {
        $checkout = session('packing.checkout');

        return (is_array($checkout) && ! empty($checkout['items'])) ? $checkout : null;
    }

    private function resolveAddress(array $data): UserAddress
    {
        if ($data['mode'] === 'new') {
            if (! empty($data['is_primary'])) {
                UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
            }

            return UserAddress::create([
                'user_id' => Auth::id(),
                'label' => $data['label'] ?? 'Alamat',
                'address' => $data['address'],
                'note' => $data['note'] ?? null,
                'is_primary' => (bool) ($data['is_primary'] ?? false),
            ]);
        }

        return UserAddress::where('user_id', Auth::id())->findOrFail($data['address_id']);
    }

    private function calcTotals(array $checkout): array
    {
        $subtotal = collect($checkout['items'])->sum(fn ($item) => $item['price'] * $item['qty']);
        $courier = null;
        $shipping = 0;

        if (($checkout['logistic'] ?? null) === 'biteship' && ! empty($checkout['courier'])) {
            $courier = Courier::where('group', 'instant')
                ->where('code', $checkout['courier'])
                ->first();
            $shipping = $courier?->price ?? 0;
        }

        return [$subtotal, $shipping, $subtotal + $shipping, $courier];
    }
}
