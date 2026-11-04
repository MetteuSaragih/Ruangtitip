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
    public function index()
    {
        return view('admin.ruang-titip', [
            'rooms'          => StorageRoom::orderBy('id')->get(),
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
            'images'         => 'nullable|array|max:10',
            'images.*'       => 'image|max:5120',
        ]);

        if ($this->uploadedImagesCount($request) < 1) {
            return back()->withErrors(['images' => 'Wajib unggah minimal 1 foto ruangan.'])->withInput();
        }
        if ($this->uploadedImagesCount($request) > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap ruangan.'])->withInput();
        }

        $data['facilities'] = $request->input('facilities', []);
        $data['active']     = $request->boolean('active', true);
        $data['photos']     = $this->storeImages($request, 'storage-rooms');
        $data['photo']      = collect($data['photos'])->first();

        StorageRoom::create($data);

        return redirect()->route('admin.ruang-titip')
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
            'images'         => 'nullable|array|max:10',
            'images.*'       => 'image|max:5120',
        ]);

        $existingCount = count($room->photos ?? []);
        if ($existingCount + $this->uploadedImagesCount($request) < 1) {
            return back()->withErrors(['images' => 'Ruangan wajib memiliki minimal 1 foto.'])->withInput();
        }
        if ($existingCount + $this->uploadedImagesCount($request) > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap ruangan.'])->withInput();
        }

        $data['facilities'] = $request->input('facilities', []);
        $data['active']     = $request->boolean('active', true);
        $newPhotos = $this->storeImages($request, 'storage-rooms');
        if (! empty($newPhotos)) {
            $data['photos'] = array_values(array_merge($room->photos ?? [], $newPhotos));
            $data['photo'] = collect($data['photos'])->first();
        }

        $room->update($data);

        return redirect()->route('admin.ruang-titip')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /* ── DESTROY (Hapus Ruangan) ───────────────────────────── */
    public function destroy(StorageRoom $room)
    {
        $room->delete();

        return redirect()->route('admin.ruang-titip')
            ->with('success', 'Ruangan berhasil dihapus.');
    }

    /* ── TOGGLE ACTIVE ─────────────────────────────────────── */
    public function toggleActive(StorageRoom $room)
    {
        $room->update(['active' => ! $room->active]);

        if (request()->expectsJson()) {
            return response()->json(['active' => $room->active]);
        }

        return back()->with('success', $room->active ? 'Ruangan sekarang tayang.' : 'Ruangan disembunyikan.');
    }

    /* ── UPLOAD BUKTI (Operasional) ────────────────────────── */
    public function uploadProof(Request $request, TitipanOrder $order)
    {
        $data = $request->validate([
            'proof_photo' => 'required|image|max:5120',
        ]);

        $order->update([
            'proof_photo' => $request->file('proof_photo')->store('titipan-proofs', 'public'),
        ]);

        return back()
            ->with('success', 'Bukti penitipan berhasil diunggah.');
    }

    /* ── UPDATE STATUS (Operasional) ───────────────────────── */
    public function updateStatus(Request $request, TitipanOrder $order)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(TitipanOrder::FLOW)),
        ]);

        $order->update(['status' => $data['status']]);

        return back()
            ->with('success', 'Status pesanan berhasil diperbarui.');
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

    private function uploadedImagesCount(Request $request): int
    {
        return $request->hasFile('images') ? count($request->file('images')) : 0;
    }
}
