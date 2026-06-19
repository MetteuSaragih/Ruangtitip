<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingProduct;
use Illuminate\Http\Request;

class TokoPackingController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'inventaris');
        
        // Ambil data produk toko packing utama, urutkan dari yang terbaru
        $items = PackingProduct::latest()->get();
        
        // Data pesanan (dikosongkan sementara untuk tab pesanan)
        $orders = collect([]); 

        // Hitung statistik untuk Scorecard
        $lowItems = $items->filter(fn($item) => $item->stock <= $item->low_threshold);
        $mostLow = $lowItems->sortBy('stock')->first();
        
        // Pendapatan & Terjual (karena tabel orders kosong, kita set 0)
        $totalSold = 0; 
        $revenue = 0;   

        return view('admin.toko-packing', compact(
            'items', 'orders', 'tab', 'lowItems', 'mostLow', 'totalSold', 'revenue'
        ));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        if ($this->uploadedImagesCount($request) > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap produk packing.'])->withInput();
        }

        $data['is_active'] = true;
        $data['images'] = $this->storeImages($request);

        PackingProduct::create($data);
        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $item = PackingProduct::findOrFail($id);
        
        // Cek apakah ini update spesifik untuk stok cepat (dari tombol + / -)
        if ($request->has('quick_stock')) {
            $request->validate(['stock' => ['required', 'integer', 'min:0']]);

            $item->update(['stock' => $request->integer('stock')]);
            return back()->with('save_toast', $item->name);
        }

        // Jika update full dari form modal
        $data = $this->validatedData($request);
        if (count($item->images ?? []) + $this->uploadedImagesCount($request) > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap produk packing.'])->withInput();
        }

        $images = $this->storeImages($request);
        if (! empty($images)) {
            $data['images'] = array_values(array_merge($item->images ?? [], $images));
        }

        $item->update($data);
        return back()->with('success', 'Perubahan produk berhasil disimpan!');
    }

    public function destroy($id)
    {
        PackingProduct::findOrFail($id)->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:32'],
            'price' => ['required', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'low_threshold' => ['required', 'integer', 'min:1'],
            'discount' => ['nullable', 'integer', 'min:0', 'max:100'],
            'emoji' => ['nullable', 'string', 'max:16'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'max:5120'],
        ]);
    }

    private function storeImages(Request $request): array
    {
        if (! $request->hasFile('images')) {
            return [];
        }

        return collect($request->file('images'))
            ->map(fn ($file) => $file->store('packing-products', 'public'))
            ->values()
            ->all();
    }

    private function uploadedImagesCount(Request $request): int
    {
        return $request->hasFile('images') ? count($request->file('images')) : 0;
    }
}
