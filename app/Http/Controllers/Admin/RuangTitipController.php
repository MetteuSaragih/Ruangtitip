<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'pricing.*'      => 'nullable',
            'opening_hours'            => 'nullable|array',
            'opening_hours.weekday'    => 'nullable|string|max:40',
            'opening_hours.saturday'   => 'nullable|string|max:40',
            'opening_hours.sunday'     => 'nullable|string|max:40',
            'remove_photos'            => 'nullable|array',
            'remove_photos.*'          => 'string',
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
        $data['opening_hours'] = $this->cleanHours($request);
        unset($data['remove_photos']);
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
            'pricing.*'      => 'nullable',
            'opening_hours'            => 'nullable|array',
            'opening_hours.weekday'    => 'nullable|string|max:40',
            'opening_hours.saturday'   => 'nullable|string|max:40',
            'opening_hours.sunday'     => 'nullable|string|max:40',
            'remove_photos'            => 'nullable|array',
            'remove_photos.*'          => 'string',
            'images'         => 'nullable|array|max:10',
            'images.*'       => 'image|max:5120',
        ]);

        // Foto lama yang dicentang untuk dihapus (hanya yang memang milik ruang ini).
        $current = array_values($room->photos ?? []);
        $removed = array_values(array_intersect((array) $request->input('remove_photos', []), $current));
        $kept = array_values(array_diff($current, $removed));

        $total = count($kept) + $this->uploadedImagesCount($request);
        if ($total < 1) {
            return back()->withErrors(['images' => 'Ruangan wajib memiliki minimal 1 foto.'])->withInput();
        }
        if ($total > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap ruangan.'])->withInput();
        }

        $data['facilities'] = $request->input('facilities', []);
        $data['active']     = $request->boolean('active', true);
        $data['opening_hours'] = $this->cleanHours($request);
        unset($data['remove_photos']);

        $newPhotos = $this->storeImages($request, 'storage-rooms');
        $data['photos'] = array_values(array_merge($kept, $newPhotos));
        $data['photo'] = collect($data['photos'])->first();

        $room->update($data);

        if ($removed) {
            Storage::disk('public')->delete($removed);
        }

        return redirect()->route('admin.ruang-titip')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /* ── DESTROY (Hapus Ruangan) ───────────────────────────── */
    public function destroy(StorageRoom $room)
    {
        // Pesanan yang masih berjalan menunjuk ke ruang ini, jadi ruang tidak boleh dihapus.
        $running = TitipanOrder::where('storage_id', $room->id)->where('status', '!=', 'selesai')->count();
        if ($running > 0) {
            return back()->with('error', "{$room->name} masih punya {$running} pesanan yang berjalan. Sembunyikan ruang ini dulu, hapus setelah semua pesanan selesai.");
        }

        $photos = array_values($room->photos ?? []);
        $room->delete();

        if ($photos) {
            Storage::disk('public')->delete($photos);
        }

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

    /** Jam operasional dari form; kosong semua = pakai jam bawaan. */
    private function cleanHours(Request $request): ?array
    {
        $hours = [];
        foreach (['weekday', 'saturday', 'sunday'] as $key) {
            $value = trim((string) $request->input("opening_hours.$key", ''));
            if ($value !== '') {
                $hours[$key] = $value;
            }
        }

        return $hours ?: null;
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
