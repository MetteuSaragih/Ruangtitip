<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use App\Support\StorageSizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RuangTitipController extends Controller
{
    /* ── INDEX ─────────────────────────────────────────────── */
    public function index()
    {
        return view('admin.ruang-titip', [
            'rooms' => StorageRoom::orderBy('id')->get(),
            'sizes' => StorageSizes::all(),
        ]);
    }

    /* ── HARGA PER UKURAN (global, per barang per hari) ────── */
    public function updateSizes(Request $request)
    {
        $rules = [];
        $attributes = [];
        foreach (StorageSizes::codes() as $code) {
            $rules["prices.$code"] = 'required|integer|min:1000|max:10000000';
            $attributes["prices.$code"] = 'harga ' . StorageSizes::label($code);
        }

        $messages = [
            'prices.*.required' => ':attribute wajib diisi.',
            'prices.*.integer' => ':attribute harus berupa angka bulat tanpa titik atau koma.',
            'prices.*.min' => ':attribute minimal Rp1.000.',
            'prices.*.max' => ':attribute terlalu besar.',
        ];

        $data = $request->validate($rules, $messages, $attributes);

        StorageSizes::savePrices($data['prices']);

        return redirect()->route('admin.ruang-titip')
            ->with('success', 'Harga per ukuran berhasil disimpan. Harga baru berlaku untuk pesanan berikutnya.');
    }

    /* ── STORE (Tambah Ruangan) ────────────────────────────── */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        if ($this->uploadedImagesCount($request) < 1) {
            return back()->withErrors(['images' => 'Wajib unggah minimal 1 foto ruangan.'])->withInput();
        }
        if ($this->uploadedImagesCount($request) > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 gambar untuk setiap ruangan.'])->withInput();
        }

        $data = $this->prepare($request, $data);
        $data['photos'] = $this->storeImages($request, 'storage-rooms');
        $data['photo']  = collect($data['photos'])->first();

        StorageRoom::create($data);

        return redirect()->route('admin.ruang-titip')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /* ── UPDATE (Edit Ruangan) ─────────────────────────────── */
    public function update(Request $request, StorageRoom $room)
    {
        $data = $request->validate($this->rules(), $this->messages());

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

        $data = $this->prepare($request, $data);

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

    /** Aturan isian form ruang (model ukuran S/M/L/XL, harga global). */
    private function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'title'            => 'nullable|string|max:255',
            'location'         => 'nullable|string|max:255',
            'address'          => 'required|string|max:500',
            'description'      => 'nullable|string|max:500',
            'long_description' => 'nullable|string|max:3000',
            'sizes'            => 'required|array|min:1',
            'sizes.*'          => ['required', 'string', Rule::in(StorageSizes::codes())],
            'facilities'       => 'nullable|string|max:2000',
            'rules'            => 'nullable|string|max:2000',
            'capacity_total'   => 'required|integer|min:1|max:100000',
            'active'           => 'boolean',
            'opening_hours'            => 'nullable|array',
            'opening_hours.weekday'    => 'nullable|string|max:40',
            'opening_hours.saturday'   => 'nullable|string|max:40',
            'opening_hours.sunday'     => 'nullable|string|max:40',
            'remove_photos'    => 'nullable|array',
            'remove_photos.*'  => 'string',
            'images'           => 'nullable|array|max:10',
            'images.*'         => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ];
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Isi nama ruang.',
            'address.required' => 'Isi alamat lengkap gudang.',
            'sizes.required' => 'Pilih minimal satu ukuran yang diterima ruang ini.',
            'sizes.min' => 'Pilih minimal satu ukuran yang diterima ruang ini.',
            'sizes.*.in' => 'Ukuran harus S, M, L, atau XL.',
            'capacity_total.required' => 'Isi kapasitas total.',
            'capacity_total.integer' => 'Kapasitas harus berupa angka bulat.',
            'capacity_total.min' => 'Kapasitas minimal 1.',
            'capacity_total.max' => 'Kapasitas terlalu besar.',
            'description.max' => 'Deskripsi singkat maksimal 500 karakter.',
            'long_description.max' => 'Deskripsi lengkap maksimal 3.000 karakter.',
            'facilities.max' => 'Daftar fitur terlalu panjang.',
            'rules.max' => 'Daftar aturan terlalu panjang.',
            'images.max' => 'Maksimal 10 gambar untuk setiap ruangan.',
            'images.*.image' => 'File harus berupa foto.',
            'images.*.mimes' => 'Foto harus JPG, PNG, atau WEBP.',
            'images.*.max' => 'Ukuran tiap foto maksimal 5 MB.',
        ];
    }

    /** Ubah hasil validasi menjadi data siap simpan (ukuran terurut, fitur dan aturan jadi daftar). */
    private function prepare(Request $request, array $data): array
    {
        $chosen = (array) ($data['sizes'] ?? []);
        $data['sizes'] = array_values(array_filter(StorageSizes::codes(), fn ($code) => in_array($code, $chosen, true)));
        $data['facilities'] = $this->lines($request->input('facilities'));
        $data['rules'] = $this->lines($request->input('rules'));
        $data['active'] = $request->boolean('active', true);
        $data['opening_hours'] = $this->cleanHours($request);
        $data['pricing'] = [];
        unset($data['remove_photos'], $data['images']);

        return $data;
    }

    /** Textarea satu butir per baris menjadi daftar (kosong dibuang, duplikat dihapus). */
    private function lines(?string $text): array
    {
        $out = [];
        foreach (preg_split('/\R/u', (string) $text) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $out[$line] = $line;
            }
        }

        return array_slice(array_values($out), 0, 30);
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
