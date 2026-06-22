<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PrelovedItem;
use Illuminate\Http\Request;

class TokoPrelovedController extends Controller
{
    private array $categories = [
        'Koper', 'Furnitur', 'Aksesori', 'Elektronik',
        'Dekorasi', 'Buku', 'Olahraga', 'Lainnya',
    ];

    private array $conditions = [95, 90, 85, 80, 75, 70];

    private array $conditionLabels = [
        95 => ['label' => '95% Mulus',  'color' => '#34d399', 'bg' => 'rgba(52,211,153,0.12)'],
        90 => ['label' => '90% Mulus',  'color' => '#34d399', 'bg' => 'rgba(52,211,153,0.10)'],
        85 => ['label' => '85% Baik',   'color' => '#a3e635', 'bg' => 'rgba(163,230,53,0.10)'],
        80 => ['label' => '80% Baik',   'color' => '#fbbf24', 'bg' => 'rgba(251,191,36,0.12)'],
        75 => ['label' => '75% Normal', 'color' => '#fb923c', 'bg' => 'rgba(251,146,60,0.12)'],
        70 => ['label' => '70% Normal', 'color' => '#fb923c', 'bg' => 'rgba(251,146,60,0.10)'],
    ];

    /* Status fulfillment pesanan preloved (Order.status, sesudah payment_status PAID) */
    public array $orderStatusLabels = [
        'paid'       => 'Menunggu Konfirmasi',
        'processing' => 'Diproses',
        'shipped'    => 'Dikirim',
        'delivered'  => 'Selesai',
        'cancelled'  => 'Dibatalkan',
    ];

    /* ── INDEX ─────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $tab      = $request->query('tab', 'katalog');
        $filter   = $request->query('filter', 'Semua');
        $items    = PrelovedItem::latest()->get();

        // Pesanan asli yang sudah lunas via Tripay, hanya yang mengandung item preloved
        // (keranjang campuran packing + preloved tetap disimpan dalam satu Order yang sama).
        $orders = Order::where('payment_status', 'PAID')
            ->latest()
            ->get()
            ->map(function (Order $o) {
                $prelovedItems = collect($o->items)->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved');
                $o->preloved_item_names = $prelovedItems->pluck('name')->implode(', ');
                $o->preloved_subtotal   = $prelovedItems->sum(fn ($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
                $o->preloved_count      = $prelovedItems->count();

                return $o;
            })
            ->filter(fn (Order $o) => $o->preloved_count > 0)
            ->values();

        $filteredOrders = $filter === 'Semua'
            ? $orders
            : $orders->where('status', array_search($filter, $this->orderStatusLabels, true));

        $available = $items->where('status', 'Tersedia')->count();
        $sold      = $items->where('status', 'Terjual')->count();
        $revenue   = $orders->where('status', 'delivered')->sum('preloved_subtotal');

        return view('admin.toko-preloved', [
            'pageTab'          => $tab,
            'filter'           => $filter,
            'items'            => $items,
            'orders'           => $filteredOrders,
            'available'        => $available,
            'sold'             => $sold,
            'revenue'          => $revenue,
            'categories'       => $this->categories,
            'conditions'       => $this->conditions,
            'conditionLabels'  => $this->conditionLabels,
            'orderStatusLabels' => $this->orderStatusLabels,
        ]);
    }

    /* ── STORE Item ────────────────────────────────────────── */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|string',
            'condition' => 'required|integer|in:70,75,80,85,90,95',
            'price'     => 'required|integer|min:1000',
            'seller'    => 'nullable|string|max:255',
            'photo'     => 'nullable|image|max:5120',
            'images'    => 'nullable|array|max:10',
            'images.*'  => 'image|max:5120',
        ]);

        if ($this->uploadedImagesCount($request) > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap produk preloved.'])->withInput();
        }

        $photos = $this->storeImages($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('preloved', 'public');
        }
        if (! empty($photos)) {
            $data['photos'] = $photos;
            $data['photo'] = $data['photo'] ?? collect($photos)->first();
        }

        $data['status'] = 'Tersedia';
        PrelovedItem::create($data);

        return redirect()->route('admin.preloved', ['tab' => 'katalog'])
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    /* ── UPDATE Item ───────────────────────────────────────── */
    public function update(Request $request, PrelovedItem $item)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|string',
            'condition' => 'required|integer|in:70,75,80,85,90,95',
            'price'     => 'required|integer|min:1000',
            'seller'    => 'nullable|string|max:255',
            'photo'     => 'nullable|image|max:5120',
            'images'    => 'nullable|array|max:10',
            'images.*'  => 'image|max:5120',
        ]);

        if ($this->existingPhotosCount($item) + $this->uploadedImagesCount($request) > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap produk preloved.'])->withInput();
        }

        $photos = $this->storeImages($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('preloved', 'public');
        } else {
            unset($data['photo']);
        }
        if (! empty($photos)) {
            $data['photos'] = array_values(array_merge($item->photos ?? [], $photos));
            $data['photo'] = $data['photo'] ?? collect($data['photos'])->first();
        }

        $item->update($data);

        return redirect()->route('admin.preloved', ['tab' => 'katalog'])
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /* ── DESTROY Item ──────────────────────────────────────── */
    public function destroy(PrelovedItem $item)
    {
        $item->delete();

        return redirect()->route('admin.preloved', ['tab' => 'katalog'])
            ->with('success', 'Barang berhasil dihapus.');
    }

    /* ── TOGGLE DRAFT ──────────────────────────────────────── */
    public function toggleDraft(PrelovedItem $item)
    {
        $newStatus = $item->status === 'Draft' ? 'Tersedia' : 'Draft';
        $item->update(['status' => $newStatus]);

        return redirect()->route('admin.preloved', ['tab' => 'katalog'])
            ->with('success', 'Status barang diperbarui.');
    }

    /* ── ADVANCE ORDER STATUS ──────────────────────────────── */
    public function advanceOrder(Order $order)
    {
        $next = match ($order->status) {
            Order::STATUS_PAID => 'processing',
            'processing'       => $order->shipping_method === 'biteship' ? 'shipped' : 'delivered',
            'shipped'          => 'delivered',
            default            => null,
        };

        if ($next) {
            $order->update(['status' => $next]);
        }

        return redirect()->route('admin.preloved', ['tab' => 'pesanan'])
            ->with('success', 'Status pesanan diperbarui.');
    }

    private function storeImages(Request $request): array
    {
        if (! $request->hasFile('images')) {
            return [];
        }

        return collect($request->file('images'))
            ->map(fn ($file) => $file->store('preloved', 'public'))
            ->values()
            ->all();
    }

    private function uploadedImagesCount(Request $request): int
    {
        $count = $request->hasFile('images') ? count($request->file('images')) : 0;

        return $count + ($request->hasFile('photo') ? 1 : 0);
    }

    private function existingPhotosCount(PrelovedItem $item): int
    {
        $photos = $item->photos ?? [];
        if ($item->photo && ! in_array($item->photo, $photos, true)) {
            $photos[] = $item->photo;
        }

        return count($photos);
    }
}
