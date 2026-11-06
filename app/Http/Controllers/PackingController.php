<?php

namespace App\Http\Controllers;

use App\Services\AdminNotifier;
use App\Models\PackingOrder;
use App\Models\PackingProduct;
use App\Models\UserAddress;
use App\Services\BiteshipService;
use App\Services\DistanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackingController extends Controller
{
    public function __construct(private DistanceService $distance)
    {
    }

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

        $related = PackingProduct::active()
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('packing.show', compact('product', 'related'));
    }

    public function buy(Request $request, PackingProduct $product)
    {
        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('error', 'Stok produk habis.');
        }

        $qty = max(1, min((int) $request->input('qty', 1), $product->stock));

        session([
            'packing.checkout' => [
                'items' => [[
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'weight' => $product->weight,
                    'length' => $product->length,
                    'width' => $product->width,
                    'height' => $product->height,
                    'image' => $product->primary_image,
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

        $checkout = $this->guardCheckout();
        if (! $checkout) {
            return redirect()->route('packing.index');
        }
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

        return view('packing.address', [
            'addresses' => $addresses,
            'items' => $checkout['items'] ?? [],
        ]);
    }

    public function saveAddress(Request $request)
    {
        $data = $request->validate([
            'mode' => 'required|in:select,new',
            'address_id' => 'nullable|required_if:mode,select|exists:user_addresses,id',
            'label' => 'nullable|string|max:50',
            'address' => 'nullable|required_if:mode,new|string|max:500',
            'note' => 'nullable|string|max:500',
            'area_id' => 'nullable|required_if:mode,new|string',
            'area_name' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
        ]);

        $checkout = $this->guardCheckout();
        if (! $checkout) {
            return redirect()->route('packing.index');
        }

        $address = $this->resolveAddress($data);
        if (! $address->area_id) {
            return back()->withErrors(['address' => 'Pilih kecamatan/kota tujuan terlebih dahulu.'])->withInput();
        }
        $address = $this->distance->ensureAddressCoords($address);

        $checkout['address'] = $address->address;
        $checkout['address_id'] = $address->id;
        $checkout['address_area_id'] = $address->area_id;
        $checkout['address_postal_code'] = $address->postal_code;
        $checkout['address_lat'] = $address->latitude;
        $checkout['address_lng'] = $address->longitude;
        session(['packing.checkout' => $checkout]);

        return redirect()->route('packing.courier');
    }

    public function courier()
    {
        $checkout = $this->guardCheckout();
        if (! $checkout || empty($checkout['address_area_id'])) {
            return redirect()->route('packing.address');
        }

        return view('packing.courier', [
            'address' => $checkout['address'] ?? '',
            'items' => $checkout['items'] ?? [],
        ]);
    }

    public function courierRates(BiteshipService $biteship)
    {
        $checkout = $this->guardCheckout();
        if (! $checkout || empty($checkout['address_area_id'])) {
            return response()->json(['success' => false, 'message' => 'Alamat belum dipilih.'], 422);
        }

        $result = $biteship->getRates(
            $this->originWarehouse(),
            $this->destinationFromCheckout($checkout),
            $this->itemsToBiteship($checkout['items']),
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

        $checkout = $this->guardCheckout();
        if (! $checkout || empty($checkout['address_area_id'])) {
            return redirect()->route('packing.address');
        }

        $rates = $biteship->getRates(
            $this->originWarehouse(),
            $this->destinationFromCheckout($checkout),
            $this->itemsToBiteship($checkout['items']),
            config('biteship.checkout_couriers')
        );

        $picked = collect($rates['pricing'] ?? [])->first(fn ($p) => $p['courier_code'] === $data['courier_code']
            && ($p['courier_service_code'] ?? null) === $data['service_code']);

        if (! $picked) {
            return back()->withErrors(['courier_code' => 'Tarif kurir tidak valid atau sudah berubah, silakan pilih ulang.']);
        }

        $checkout['courier'] = $picked['courier_code'];
        $checkout['courier_service_code'] = $picked['courier_service_code'] ?? null;
        $checkout['courier_name'] = $picked['courier_name'] ?? $picked['courier_code'];
        $checkout['shipping_cost'] = (int) $picked['price'];
        session(['packing.checkout' => $checkout]);

        return redirect()->route('packing.payment');
    }

    public function payment()
    {
        $checkout = $this->guardCheckout();
        if (! $checkout || ! isset($checkout['logistic'])) {
            return redirect()->route('packing.logistics');
        }
        if ($checkout['logistic'] === 'biteship' && empty($checkout['courier'])) {
            return redirect()->route('packing.address');
        }

        [$subtotal, $shipping, $platformFee, $total, $courier] = $this->calcTotals($checkout);

        return view('packing.payment', [
            'items' => $checkout['items'],
            'logistic' => $checkout['logistic'],
            'courier' => $courier,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'platformFee' => $platformFee,
            'total' => $total,
        ]);
    }

    public function pay(Request $request)
    {
        $data = $request->validate([
            'pickup_date' => 'nullable|date|after_or_equal:today',
            'pickup_time' => 'nullable|string',
        ]);

        $checkout = $this->guardCheckout();
        if (! $checkout) {
            return redirect()->route('packing.index');
        }
        if (! isset($checkout['logistic'])) {
            return redirect()->route('packing.logistics');
        }
        if ($checkout['logistic'] === 'biteship' && (empty($checkout['courier']) || empty($checkout['address_area_id']))) {
            return redirect()->route('packing.address');
        }

        [$subtotal, $shipping, $platformFee, $total] = $this->calcTotals($checkout);

        $order = PackingOrder::create([
            'order_code' => $this->newOrderCode(),
            'user_id' => Auth::id(),
            'items' => $checkout['items'],
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'platform_fee' => $platformFee,
            'total' => $total,
            'logistic' => $checkout['logistic'],
            'courier' => $checkout['courier'] ?? null,
            'courier_service_code' => $checkout['courier_service_code'] ?? null,
            'courier_company' => $checkout['courier'] ?? null,
            'address' => $checkout['address'] ?? null,
            'address_area_id' => $checkout['address_area_id'] ?? null,
            'address_postal_code' => $checkout['address_postal_code'] ?? null,
            'pickup_date' => $data['pickup_date'] ?? null,
            'pickup_time' => $data['pickup_time'] ?? null,
            'payment_method' => 'qris',
            'status' => 'menunggu_pembayaran',
            'payment_status' => 'UNPAID',
        ]);

        AdminNotifier::newPacking($order);

        session()->forget('packing.checkout');

        return redirect()->route('packing.success', $order->order_code);
    }

    public function success(string $orderCode)
    {
        $order = PackingOrder::where('order_code', $orderCode)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('packing.success', ['order' => $order]);
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
                'area_id' => $data['area_id'] ?? null,
                'area_name' => $data['area_name'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
            ]);
        }

        return UserAddress::where('user_id', Auth::id())->findOrFail($data['address_id']);
    }

    private function itemsToBiteship(array $items): array
    {
        return collect($items)->map(fn ($item) => [
            'name' => $item['name'],
            'value' => (int) $item['price'],
            'quantity' => (int) $item['qty'],
            'weight' => (int) ($item['weight'] ?? 500),
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
    private function destinationFromCheckout(array $checkout): array
    {
        return array_filter([
            'area_id' => $checkout['address_area_id'] ?? null,
            'postal_code' => $checkout['address_postal_code'] ?? null,
            'latitude' => $checkout['address_lat'] ?? null,
            'longitude' => $checkout['address_lng'] ?? null,
        ]);
    }

    /** Kode unik: coba beberapa kali, lalu perpanjang agar tidak pernah bentrok. */
    private function newOrderCode(): string
    {
        $prefix = 'TP-' . now()->year . '-';
        for ($i = 0; $i < 8; $i++) {
            $code = $prefix . random_int(10000, 99999);
            if (! PackingOrder::where('order_code', $code)->exists()) {
                return $code;
            }
        }

        return $prefix . random_int(10000, 99999) . random_int(100, 999);
    }

    private function calcTotals(array $checkout): array
    {
        $subtotal = collect($checkout['items'])->sum(fn ($item) => $item['price'] * $item['qty']);
        $courier = null;
        $shipping = 0;

        if (($checkout['logistic'] ?? null) === 'biteship' && ! empty($checkout['courier'])) {
            $shipping = (int) ($checkout['shipping_cost'] ?? 0);
            $courier = ['code' => $checkout['courier'], 'name' => $checkout['courier_name'] ?? $checkout['courier']];
        }

        $platformFee = $this->serviceFeeFor($checkout['logistic'] ?? null);

        return [$subtotal, $shipping, $platformFee, $subtotal + $shipping + $platformFee, $courier];
    }

    /** Biaya layanan platform: Rp 2.000 jika pengiriman pakai Biteship, Rp 1.000 jika tidak. */
    private function serviceFeeFor(?string $logistic): int
    {
        return $logistic === 'biteship' ? 2000 : 1000;
    }
}
