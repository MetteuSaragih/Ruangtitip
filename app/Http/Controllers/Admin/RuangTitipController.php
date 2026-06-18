<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use Illuminate\Http\Request;

class RuangTitipController extends Controller
{
    private array $defaultPricing = [
        'kardus' => [
            ['id' => 'ks',  'label' => 'S',  'dims' => '20×15×10 cm', 'price' => 5000],
            ['id' => 'km',  'label' => 'M',  'dims' => '30×20×15 cm', 'price' => 8000],
            ['id' => 'kl',  'label' => 'L',  'dims' => '40×30×20 cm', 'price' => 12000],
            ['id' => 'kxl', 'label' => 'XL', 'dims' => '50×40×30 cm', 'price' => 18000],
        ],
        'koper' => [
            ['id' => 'kpc',  'label' => 'Cabin',  'dims' => '35×23×15 cm', 'price' => 15000],
            ['id' => 'kpm',  'label' => 'Medium', 'dims' => '50×33×20 cm', 'price' => 22000],
            ['id' => 'kpl',  'label' => 'Large',  'dims' => '65×43×25 cm', 'price' => 30000],
            ['id' => 'kpxl', 'label' => 'XL',     'dims' => '75×50×30 cm', 'price' => 40000],
        ],
        'dimensiLain' => 50000,
    ];

    private array $allFacilities = [
        'Bebas Banjir', 'CCTV 24 Jam', 'Kelembapan Terjaga',
        'Akses 24 Jam', 'Lift Barang', 'Penjaga Malam', 'Forklift', 'Asuransi Barang',
    ];

    /* ── INDEX ─────────────────────────────────────────────── */
    public function index(Request $request)
    {
        $tab   = $request->query('tab', 'operasional');
        $oTab  = $request->query('otab', 'baru');
        $rooms = StorageRoom::orderBy('id')->get();
        $orderData = $this->orderData();

        return view('admin.ruang-titip', [
            'pageTab'        => $tab,
            'orderTab'       => $oTab,
            'orderData'      => $orderData,
            'rooms'          => $rooms,
            'defaultPricing' => $this->defaultPricing,
            'allFacilities'  => $this->allFacilities,
        ]);
    }

    /* ── STORE (Tambah Ruangan) ────────────────────────────── */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'address'        => 'required|string',
            'description'    => 'nullable|string',
            'capacity_total' => 'required|integer|min:1',
            'active'         => 'boolean',
            'facilities'     => 'nullable|array',
            'pricing'        => 'required|array',
            'images'         => 'nullable|array',
            'images.*'       => 'image|max:5120',
        ]);

        $data['facilities'] = $request->input('facilities', []);
        $data['active']     = $request->boolean('active', true);
        $data['photos']     = $this->storeImages($request, 'storage-rooms');
        $data['photo']      = collect($data['photos'])->first();

        StorageRoom::create($data);

        return redirect()->route('admin.ruang-titip', ['tab' => 'ruangan'])
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /* ── UPDATE (Edit Ruangan) ─────────────────────────────── */
    public function update(Request $request, StorageRoom $room)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'location'       => 'nullable|string|max:255',
            'address'        => 'required|string',
            'description'    => 'nullable|string',
            'capacity_total' => 'required|integer|min:1',
            'active'         => 'boolean',
            'facilities'     => 'nullable|array',
            'pricing'        => 'required|array',
            'images'         => 'nullable|array',
            'images.*'       => 'image|max:5120',
        ]);

        $data['facilities'] = $request->input('facilities', []);
        $data['active']     = $request->boolean('active', true);
        $newPhotos = $this->storeImages($request, 'storage-rooms');
        if (! empty($newPhotos)) {
            $data['photos'] = array_values(array_merge($room->photos ?? [], $newPhotos));
            $data['photo'] = collect($data['photos'])->first();
        }

        $room->update($data);

        return redirect()->route('admin.ruang-titip', ['tab' => 'ruangan'])
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /* ── DESTROY (Hapus Ruangan) ───────────────────────────── */
    public function destroy(StorageRoom $room)
    {
        $room->delete();

        return redirect()->route('admin.ruang-titip', ['tab' => 'ruangan'])
            ->with('success', 'Ruangan berhasil dihapus.');
    }

    /* ── TOGGLE ACTIVE ─────────────────────────────────────── */
    public function toggleActive(StorageRoom $room)
    {
        $room->update(['active' => ! $room->active]);

        return response()->json(['active' => $room->active]);
    }

    private function storeImages(Request $request, string $dir): array
    {
        if (! $request->hasFile('images')) {
            return [];
        }

        return collect($request->file('images'))
            ->map(fn ($file) => $file->store($dir, 'public'))
            ->values()
            ->all();
    }

    private function orderData(): array
    {
        $blank = ['baru' => [], 'inspeksi' => [], 'gudang' => [], 'keluar' => []];

        return TitipanOrder::with('user')->latest()->get()
            ->map(function (TitipanOrder $order) {
                return [
                    'id' => $order->code(),
                    'customer' => $order->user?->name ?? 'Pelanggan',
                    'wa' => $order->user?->phone ?? '-',
                    'items' => collect($order->items ?? [])->map(fn ($qty, $code) => strtoupper($code) . ' x ' . $qty)->implode(', '),
                    'qty' => $order->totalItems(),
                    'duration' => optional($order->date_start)->diffInMonths($order->date_end, false) ?: 1,
                    'deadline' => optional($order->date_end)->format('d M Y') ?? '-',
                    'status' => $order->statusMeta()['label'],
                    'hasProof' => false,
                    'returnMode' => null,
                    'rackCode' => null,
                    'group' => match ($order->status) {
                        'penjadwalan_penjemputan' => 'inspeksi',
                        'dalam_gudang' => 'gudang',
                        'proses_pengembalian', 'selesai' => 'keluar',
                        default => 'baru',
                    },
                ];
            })
            ->groupBy('group')
            ->reduce(function ($carry, $items, $group) {
                $carry[$group] = $items->values()->all();
                return $carry;
            }, $blank);
    }
}
