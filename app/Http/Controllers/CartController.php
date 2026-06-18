<?php

namespace App\Http\Controllers;

use App\Models\PrelovedItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = (int) $request->input('product_id');
        $qty = max(1, (int) $request->input('qty', 1));
        $buyNow = $request->boolean('buy_now');

        $item = PrelovedItem::where('status', 'Tersedia')->find($productId);
        if (! $item) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $product = $this->toCartProduct($item, $qty);
        $cart = session('cart', []);

        if ($buyNow) {
            session(['cart' => [$productId => $product]]);

            return response()->json([
                'success' => true,
                'buy_now' => true,
                'redirect' => route('checkout.shipping'),
                'cart_count' => 1,
            ]);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $qty;
            $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $cart[$productId]['qty'];
        } else {
            $cart[$productId] = $product;
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
        $productId = $request->input('product_id');
        $qty = (int) $request->input('qty', 1);
        $cart = session('cart', []);

        if ($qty <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $qty;
            $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $qty;
        }

        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart_count' => count($cart)]);
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        return response()->json(['success' => true, 'cart_count' => count($cart)]);
    }

    public function index()
    {
        $cart = session('cart', []);

        return view('preloved.cart', compact('cart'));
    }

    private function toCartProduct(PrelovedItem $item, int $qty): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'price' => (int) $item->price,
            'emoji' => null,
            'image' => $item->primary_photo,
            'condition_label' => $item->condition . '%',
            'weight' => 1000,
            'qty' => $qty,
            'subtotal' => (int) $item->price * $qty,
        ];
    }
}
