<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrelovedController extends Controller
{
    private function getProducts()
    {
        return collect([
            ['id' => 1, 'name' => 'Kipas Angin Miyako', 'description' => 'Kipas angin meja Miyako 16 inci, masih berfungsi normal semua kecepatan. Sedikit bekas abu di jeruji tapi mudah dibersihkan. Cocok untuk kamar kos.', 'price' => 75000, 'original_price' => 180000, 'discount_percent' => 15, 'condition' => '90_mulus', 'condition_label' => '90% Mulus', 'stock' => 1, 'seller_name' => 'Budi S.', 'seller_rating' => 4.8, 'emoji' => '🌀', 'weight' => 2000, 'category' => 'elektronik'],
            ['id' => 2, 'name' => 'Meja Belajar Lipat', 'description' => 'Meja belajar lipat portable, sangat praktis untuk kamar kos. Kondisi baik, tidak ada goresan berarti.', 'price' => 120000, 'original_price' => 120000, 'discount_percent' => 0, 'condition' => '85_baik', 'condition_label' => '85% Baik', 'stock' => 1, 'seller_name' => 'Sari W.', 'seller_rating' => 4.5, 'emoji' => '🪑', 'weight' => 3000, 'category' => 'furnitur'],
            ['id' => 3, 'name' => 'Rice Cooker Mini 0.3L', 'description' => 'Rice cooker mini kapasitas 0.3L, cocok untuk masak nasi 1-2 porsi. Masih normal, lengkap dengan spatula dan wadah kukusan.', 'price' => 65000, 'original_price' => 150000, 'discount_percent' => 10, 'condition' => '90_mulus', 'condition_label' => '90% Mulus', 'stock' => 1, 'seller_name' => 'Dewi A.', 'seller_rating' => 4.9, 'emoji' => '🍚', 'weight' => 1500, 'category' => 'elektronik'],
            ['id' => 4, 'name' => 'Rak Buku 4 Susun', 'description' => 'Rak buku 4 susun dari kayu MDF. Kuat dan kokoh, bisa menampung banyak buku. Warna coklat natural.', 'price' => 95000, 'original_price' => 95000, 'discount_percent' => 0, 'condition' => '85_baik', 'condition_label' => '85% Baik', 'stock' => 1, 'seller_name' => 'Reza M.', 'seller_rating' => 4.3, 'emoji' => '📚', 'weight' => 5000, 'category' => 'furnitur'],
            ['id' => 5, 'name' => 'Setrika Philips', 'description' => 'Setrika Philips GC1418, masih bagus dan panas merata. Ada sedikit kerak di bawahnya tapi fungsi normal.', 'price' => 55000, 'original_price' => 55000, 'discount_percent' => 0, 'condition' => '75_pernah_pakai', 'condition_label' => '75% Pernah Pakai', 'stock' => 2, 'seller_name' => 'Linda P.', 'seller_rating' => 4.1, 'emoji' => '🔥', 'weight' => 1200, 'category' => 'elektronik'],
            ['id' => 6, 'name' => 'Dispenser Air Galon', 'description' => 'Dispenser air galon bawah, hemat listrik. Kondisi sangat baik, baru dipakai 6 bulan.', 'price' => 85000, 'original_price' => 200000, 'discount_percent' => 5, 'condition' => '90_mulus', 'condition_label' => '90% Mulus', 'stock' => 1, 'seller_name' => 'Andi K.', 'seller_rating' => 4.7, 'emoji' => '💧', 'weight' => 4000, 'category' => 'elektronik'],
            ['id' => 7, 'name' => 'Kasur Lipat Busa 180cm', 'description' => 'Kasur lipat busa tebal 10cm ukuran 180x60cm. Nyaman dan empuk, cocok untuk kos. Bebas kutu.', 'price' => 180000, 'original_price' => 450000, 'discount_percent' => 20, 'condition' => '85_baik', 'condition_label' => '85% Baik', 'stock' => 1, 'seller_name' => 'Maya S.', 'seller_rating' => 4.6, 'emoji' => '🛏️', 'weight' => 8000, 'category' => 'furnitur'],
            ['id' => 8, 'name' => 'Cermin Dinding 40×60cm', 'description' => 'Cermin dinding ukuran 40x60cm dengan bingkai hitam minimalis. Tidak ada retak atau goresan.', 'price' => 45000, 'original_price' => 45000, 'discount_percent' => 0, 'condition' => '95_mulus', 'condition_label' => '95% Mulus', 'stock' => 1, 'seller_name' => 'Fitri H.', 'seller_rating' => 4.9, 'emoji' => '🪞', 'weight' => 2000, 'category' => 'dekorasi'],
            ['id' => 9, 'name' => 'Lampu Belajar LED', 'description' => 'Lampu belajar LED dengan 3 mode cahaya. Bisa di-dim. Kondisi sempurna, masih ada garansinya.', 'price' => 35000, 'original_price' => 35000, 'discount_percent' => 0, 'condition' => '90_mulus', 'condition_label' => '90% Mulus', 'stock' => 3, 'seller_name' => 'Bagas R.', 'seller_rating' => 4.8, 'emoji' => '💡', 'weight' => 500, 'category' => 'elektronik'],
            ['id' => 10, 'name' => 'Karpet Kamar 120×160cm', 'description' => 'Karpet bulu lembut ukuran 120x160cm warna abu-abu. Sudah dicuci bersih, kondisi baik.', 'price' => 110000, 'original_price' => 110000, 'discount_percent' => 0, 'condition' => '75_pernah_pakai', 'condition_label' => '75% Pernah Pakai', 'stock' => 1, 'seller_name' => 'Nadia F.', 'seller_rating' => 4.4, 'emoji' => '🏠', 'weight' => 3000, 'category' => 'dekorasi'],
        ]);
    }

    public function index(Request $request)
    {
        $condition = $request->get('kondisi', 'semua');
        $products = $this->getProducts();

        if ($condition !== 'semua') {
            $products = $products->filter(function ($p) use ($condition) {
                if ($condition === '95_mulus') return in_array($p['condition'], ['95_mulus', '90_mulus']);
                if ($condition === '85_baik') return $p['condition'] === '85_baik';
                if ($condition === '75_pernah_pakai') return $p['condition'] === '75_pernah_pakai';
                return true;
            });
        }

        $cartCount = count(session('cart', []));
        return view('preloved.index', compact('products', 'condition', 'cartCount'));
    }

    public function show($id)
    {
        $product = $this->getProducts()->firstWhere('id', (int)$id);
        if (!$product) abort(404);
        $cartCount = count(session('cart', []));
        return view('preloved.show', compact('product', 'cartCount'));
    }
}
