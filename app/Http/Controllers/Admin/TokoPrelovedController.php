<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrelovedItem;
use App\Models\PrelovedOrder;
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

    /* ── INDEX ─────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $tab      = $request->query('tab', 'katalog');
        $filter   = $request->query('filter', 'Semua');
        $items    = PrelovedItem::latest()->get();
        $orders   = PrelovedOrder::with('item')->latest()->get();

        $filteredOrders = $filter === 'Semua'
            ? $orders
            : $orders->where('status', $filter);

        $available = $items->where('status', 'Tersedia')->count();
        $sold      = $items->where('status', 'Terjual')->count();
        $revenue   = $orders->where('status', 'Selesai')->sum('price');

        return view('admin.toko-preloved', [
            'pageTab'         => $tab,
            'filter'          => $filter,
            'items'           => $items,
            'orders'          => $filteredOrders,
            'available'       => $available,
            'sold'            => $sold,
            'revenue'         => $revenue,
            'categories'      => $this->categories,
            'conditions'      => $this->conditions,
            'conditionLabels' => $this->conditionLabels,
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
            'images'    => 'nullable|array',
            'images.*'  => 'image|max:5120',
        ]);

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
            'images'    => 'nullable|array',
            'images.*'  => 'image|max:5120',
        ]);

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
    public function advanceOrder(PrelovedOrder $order)
    {
        $next = match($order->status) {
            'Menunggu Konfirmasi' => 'Siap Dikirim',
            'Siap Dikirim'        => 'Selesai',
            'Siap Diambil'        => 'Selesai',
            default               => null,
        };

        if ($next) {
            $order->update(['status' => $next]);
            if ($next === 'Selesai') {
                $order->item?->update(['status' => 'Terjual']);
            }
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
}
