<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $qty = $request->input('qty', 1);
        $buyNow = $request->input('buy_now', false);

        $products = $this->getAllProducts();
        $product = collect($products)->firstWhere('id', (int)$productId);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }

        $cart = session('cart', []);

        if ($buyNow) {
            // For buy now, create a fresh cart with just this item
            $cart = [
                $productId => [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'emoji' => $product['emoji'],
                    'condition_label' => $product['condition_label'],
                    'weight' => $product['weight'],
                    'qty' => (int)$qty,
                    'subtotal' => $product['price'] * (int)$qty,
                ]
            ];
            session(['cart' => $cart]);
            return response()->json([
                'success' => true,
                'buy_now' => true,
                'redirect' => route('checkout.shipping'),
                'cart_count' => count($cart)
            ]);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += (int)$qty;
            $cart[$productId]['subtotal'] = $product['price'] * $cart[$productId]['qty'];
        } else {
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'emoji' => $product['emoji'],
                'condition_label' => $product['condition_label'],
                'weight' => $product['weight'],
                'qty' => (int)$qty,
                'subtotal' => $product['price'] * (int)$qty,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'message' => 'Produk ditambahkan ke keranjang',
            'cart_count' => count($cart)
        ]);
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $qty = (int)$request->input('qty', 1);

        $cart = session('cart', []);

        if ($qty <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $qty;
            $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $qty;
        }

        session(['cart' => $cart]);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart)
        ]);
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

    private function getAllProducts()
    {
        return [
            ['id' => 1, 'name' => 'Kipas Angin Miyako', 'price' => 75000, 'emoji' => '🌀', 'condition_label' => '90% Mulus', 'weight' => 2000],
            ['id' => 2, 'name' => 'Meja Belajar Lipat', 'price' => 120000, 'emoji' => '🪑', 'condition_label' => '85% Baik', 'weight' => 3000],
            ['id' => 3, 'name' => 'Rice Cooker Mini 0.3L', 'price' => 65000, 'emoji' => '🍚', 'condition_label' => '90% Mulus', 'weight' => 1500],
            ['id' => 4, 'name' => 'Rak Buku 4 Susun', 'price' => 95000, 'emoji' => '📚', 'condition_label' => '85% Baik', 'weight' => 5000],
            ['id' => 5, 'name' => 'Setrika Philips', 'price' => 55000, 'emoji' => '🔥', 'condition_label' => '75% Pernah Pakai', 'weight' => 1200],
            ['id' => 6, 'name' => 'Dispenser Air Galon', 'price' => 85000, 'emoji' => '💧', 'condition_label' => '90% Mulus', 'weight' => 4000],
            ['id' => 7, 'name' => 'Kasur Lipat Busa 180cm', 'price' => 180000, 'emoji' => '🛏️', 'condition_label' => '85% Baik', 'weight' => 8000],
            ['id' => 8, 'name' => 'Cermin Dinding 40×60cm', 'price' => 45000, 'emoji' => '🪞', 'condition_label' => '95% Mulus', 'weight' => 2000],
            ['id' => 9, 'name' => 'Lampu Belajar LED', 'price' => 35000, 'emoji' => '💡', 'condition_label' => '90% Mulus', 'weight' => 500],
            ['id' => 10, 'name' => 'Karpet Kamar 120×160cm', 'price' => 110000, 'emoji' => '🏠', 'condition_label' => '75% Pernah Pakai', 'weight' => 3000],
        ];
    }
}
