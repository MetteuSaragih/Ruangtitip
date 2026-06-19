<?php

namespace App\Http\Controllers;

use App\Models\PackingProduct;
use App\Models\PrelovedItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $type = $request->input('type', 'preloved') === 'packing' ? 'packing' : 'preloved';
        $productId = (int) $request->input('product_id');
        $qty = max(1, (int) $request->input('qty', 1));
        $buyNow = $request->boolean('buy_now');

        $product = $this->resolveProduct($type, $productId);
        if (! $product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $key = $type . '_' . $productId;
        $item = $this->toCartItem($type, $product, $qty);

        if ($buyNow) {
            session(['checkout_cart' => [$key => $item]]);

            return response()->json([
                'success' => true,
                'buy_now' => true,
                'redirect' => route('checkout.shipping'),
            ]);
        }

        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $cart[$key]['qty'];
        } else {
            $cart[$key] = $item;
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang',
            'cart_count' => count($cart),
        ]);
    }

    public function update(Request $request)
    {
        $key = (string) $request->input('product_id');
        $qty = (int) $request->input('qty', 1);
        $cart = session('cart', []);

        if ($qty <= 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['qty'] = $qty;
            $cart[$key]['subtotal'] = $cart[$key]['price'] * $qty;
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart_count' => count($cart)]);
    }

    public function remove(Request $request)
    {
        $key = (string) $request->input('product_id');
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart_count' => count($cart)]);
    }

    public function index()
    {
        $cart = session('cart', []);

        return view('preloved.cart', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $data = $request->validate([
            'selected' => 'required|array|min:1',
            'selected.*' => 'string',
        ], [
            'selected.required' => 'Pilih minimal satu produk untuk checkout.',
        ]);

        $cart = session('cart', []);
        $selectedItems = array_intersect_key($cart, array_flip($data['selected']));

        if (empty($selectedItems)) {
            return back()->withErrors(['selected' => 'Produk yang dipilih tidak ditemukan di keranjang.']);
        }

        session(['checkout_cart' => $selectedItems]);

        return redirect()->route('checkout.shipping');
    }

    private function resolveProduct(string $type, int $id)
    {
        if ($type === 'packing') {
            return PackingProduct::active()->find($id);
        }

        return PrelovedItem::where('status', 'Tersedia')->find($id);
    }

    private function toCartItem(string $type, $product, int $qty): array
    {
        if ($type === 'packing') {
            return [
                'type' => 'packing',
                'id' => $product->id,
                'name' => $product->name,
                'price' => (int) $product->price,
                'image' => $product->primary_image,
                'unit' => $product->unit,
                'weight' => 500,
                'qty' => $qty,
                'subtotal' => (int) $product->price * $qty,
            ];
        }

        return [
            'type' => 'preloved',
            'id' => $product->id,
            'name' => $product->name,
            'price' => (int) $product->price,
            'image' => $product->primary_photo,
            'condition_label' => $product->condition . '%',
            'weight' => 1000,
            'qty' => $qty,
            'subtotal' => (int) $product->price * $qty,
        ];
    }
}
