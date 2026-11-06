<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackingOrder;
use App\Models\PackingProduct;
use Illuminate\Http\Request;

class TokoPackingController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'inventaris') === 'pesanan' ? 'pesanan' : 'inventaris';
        $q = trim((string) $request->query('q', ''));
        $cat = (string) $request->query('cat', '');
        $st = (string) $request->query('st', '');

        // Statistik dihitung dari seluruh data (bukan halaman aktif).
        $allItems = PackingProduct::all();
        $lowItems = $allItems->filter(fn ($item) => $item->stock <= $item->low_threshold);

        $paidOrders = PackingOrder::where('payment_status', 'PAID')->get();
        $totalSold  = $paidOrders->sum(fn ($o) => collect($o->items)->sum('qty'));
        $revenue    = $paidOrders->sum('total');

        $items = null;
        $orders = null;
        $orderCounts = [];

        if ($tab === 'pesanan') {
            $orderCounts = [
                'semua' => PackingOrder::count(),
                'pending' => PackingOrder::where(fn ($w) => $w->where('status', 'pending')->orWhereNull('status'))->count(),
                'diproses' => PackingOrder::where('status', 'diproses')->count(),
                'dikirim' => PackingOrder::where('status', 'dikirim')->count(),
                'selesai' => PackingOrder::where('status', 'selesai')->count(),
            ];
            $query = PackingOrder::with('user')->latest();
            if ($st === 'pending') {
                $query->where(fn ($w) => $w->where('status', 'pending')->orWhereNull('status'));
            } elseif (in_array($st, ['diproses', 'dikirim', 'selesai'], true)) {
                $query->where('status', $st);
            } else {
                $st = '';
            }
            if ($q !== '') {
                $query->where(function ($w) use ($q) {
                    $w->where('order_code', 'like', "%{$q}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$q}%"));
                });
            }
            $orders = $query->paginate(15)->withQueryString();
        } else {
            $query = PackingProduct::latest();
            if ($cat !== '') {
                $query->where('category', $cat);
            }
            if ($q !== '') {
                $query->where('name', 'like', "%{$q}%");
            }
            $items = $query->paginate(15)->withQueryString();
        }

        return view('admin.toko-packing', [
            'tab' => $tab, 'q' => $q, 'cat' => $cat, 'st' => $st,
            'items' => $items, 'orders' => $orders, 'orderCounts' => $orderCounts,
            'lowItems' => $lowItems, 'totalSold' => $totalSold, 'revenue' => $revenue,
            'totalProducts' => $allItems->count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        if ($this->uploadedImagesCount($request) < 1) {
            return back()->withErrors(['images' => 'Wajib unggah minimal 1 foto produk.'])->withInput();
        }
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
        if (count($item->images ?? []) + $this->uploadedImagesCount($request) < 1) {
            return back()->withErrors(['images' => 'Produk wajib memiliki minimal 1 foto.'])->withInput();
        }
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

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,diproses,dikirim,selesai']);
        $order = PackingOrder::findOrFail($id);
        if ($request->status !== 'pending' && \App\Services\ManualPayment::state($order->payment_status) !== 'paid') {
            return back()->with('error', 'Pembayaran belum dikonfirmasi. Periksa bukti pembayaran di Tagihan dulu, baru status pesanan bisa dilanjutkan.');
        }
        $order->update(['status' => $request->status]);
        return back()->with('success', "Status pesanan #{$order->order_code} berhasil diperbarui.");
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
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'weight' => ['required', 'integer', 'min:1'],
            'length' => ['required', 'integer', 'min:1'],
            'width' => ['required', 'integer', 'min:1'],
            'height' => ['required', 'integer', 'min:1'],
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
