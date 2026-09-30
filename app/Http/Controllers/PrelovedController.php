<?php

namespace App\Http\Controllers;

use App\Models\PrelovedItem;
use Illuminate\Http\Request;

class PrelovedController extends Controller
{
    private array $conditionLabels = [
        95 => '95% Mulus',
        90 => '90% Mulus',
        85 => '85% Baik',
        80 => '80% Baik',
        75 => '75% Normal',
        70 => '70% Normal',
    ];

    public function index(Request $request)
    {
        $condition = $request->get('kondisi', 'semua');
        $category = $request->get('kategori', 'Semua');

        $categories = PrelovedItem::where('status', 'Tersedia')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->prepend('Semua')
            ->values()
            ->all();

        $items = PrelovedItem::where('status', 'Tersedia')
            ->when($category !== 'Semua', fn ($query) => $query->where('category', $category))
            ->latest()
            ->get();

        if ($condition !== 'semua') {
            $items = $items->filter(function (PrelovedItem $item) use ($condition) {
                if ($condition === '95_mulus') {
                    return $item->condition >= 90;
                }

                if ($condition === '85_baik') {
                    return $item->condition >= 80 && $item->condition < 90;
                }

                if ($condition === '75_pernah_pakai') {
                    return $item->condition < 80;
                }

                return true;
            });
        }

        $products = $items->map(fn (PrelovedItem $item) => $this->toProductArray($item));
        $cartCount = count(session('cart', []));

        return view('preloved.index', compact('products', 'condition', 'category', 'categories', 'cartCount'));
    }

    public function caraJual()
    {
        return view('preloved.cara-jual');
    }

    public function show($id)
    {
        $item = PrelovedItem::where('status', 'Tersedia')->findOrFail($id);
        $product = $this->toProductArray($item);
        $cartCount = count(session('cart', []));

        return view('preloved.show', compact('product', 'cartCount'));
    }

    private function toProductArray(PrelovedItem $item): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'description' => $item->description ?? 'Barang preloved pilihan RUTIP.',
            'price' => (int) $item->price,
            'original_price' => (int) $item->price,
            'discount_percent' => 0,
            'condition' => $this->conditionSlug((int) $item->condition),
            'condition_percent' => (int) $item->condition,
            'condition_label' => $this->conditionLabels[$item->condition] ?? $item->condition . '% Baik',
            'stock' => 1,
            'seller_name' => $item->seller ?: 'RUTIP',
            'seller_rating' => 5,
            'emoji' => null,
            'image' => $item->primary_photo,
            'images' => $item->photos ?? [],
            'weight' => 1000,
            'category' => strtolower($item->category),
        ];
    }

    private function conditionSlug(int $condition): string
    {
        if ($condition >= 90) {
            return '95_mulus';
        }

        if ($condition >= 80) {
            return '85_baik';
        }

        return '75_pernah_pakai';
    }
}
