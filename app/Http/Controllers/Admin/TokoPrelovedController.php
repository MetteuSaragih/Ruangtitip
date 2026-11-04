<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PrelovedItem;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TokoPrelovedController extends Controller
{
    /* Status fulfillment pesanan preloved (Order.status, sesudah payment_status PAID) */
    public array $orderStatusLabels = [
        'paid'       => 'Menunggu Konfirmasi',
        'processing' => 'Diproses',
        'shipped'    => 'Dikirim',
        'delivered'  => 'Selesai',
        'cancelled'  => 'Dibatalkan',
    ];

    /* ── INDEX (katalog) ───────────────────────────────────── */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'semua');
        if (! in_array($tab, ['semua', 'tersedia', 'draft', 'terjual'], true)) {
            $tab = 'semua';
        }
        $q = trim((string) $request->query('q', ''));

        $query = PrelovedItem::query()->latest();
        $map = ['tersedia' => 'Tersedia', 'draft' => 'Draft', 'terjual' => 'Terjual'];
        if (isset($map[$tab])) {
            $query->where('status', $map[$tab]);
        }
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%")
                    ->orWhere('seller', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
                if (Schema::hasColumn('preloved_items', 'source_code')) {
                    $w->orWhere('source_code', 'like', "%{$q}%");
                }
            });
        }

        $counts = [
            'semua'    => PrelovedItem::count(),
            'tersedia' => PrelovedItem::where('status', 'Tersedia')->count(),
            'draft'    => PrelovedItem::where('status', 'Draft')->count(),
            'terjual'  => PrelovedItem::where('status', 'Terjual')->count(),
        ];

        return view('admin.toko-preloved', [
            'tab'        => $tab,
            'q'          => $q,
            'items'      => $query->paginate(15)->withQueryString(),
            'counts'     => $counts,
            'categories' => PrelovedItem::CATEGORIES,
            'conditions' => PrelovedItem::CONDITIONS,
        ]);
    }

    /* ── PESANAN PRELOVED ──────────────────────────────────── */
    public function orders(Request $request)
    {
        $tab = $request->query('tab', 'semua');
        $q = trim((string) $request->query('q', ''));

        // Order yang memuat item preloved (keranjang campuran packing + preloved ikut dihitung).
        $all = Order::latest()
            ->get()
            ->map(function (Order $o) {
                $pre = collect($o->items ?? [])->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved');
                $o->preloved_item_names = $pre->pluck('name')->implode(', ');
                $o->preloved_subtotal   = $pre->sum(fn ($i) => ($i['price'] ?? 0) * ($i['qty'] ?? 1));
                $o->preloved_count      = $pre->count();
                $o->is_paid             = strtoupper((string) $o->payment_status) === 'PAID';

                return $o;
            })
            ->filter(fn (Order $o) => $o->preloved_count > 0)
            ->values();

        $tabFilter = [
            'bayar'    => fn (Order $o) => ! $o->is_paid && $o->status !== 'cancelled',
            'diproses' => fn (Order $o) => $o->is_paid && in_array($o->status, ['paid', 'processing'], true),
            'dikirim'  => fn (Order $o) => $o->is_paid && $o->status === 'shipped',
            'selesai'  => fn (Order $o) => $o->is_paid && $o->status === 'delivered',
        ];
        $counts = ['semua' => $all->count()];
        foreach ($tabFilter as $k => $fn) {
            $counts[$k] = $all->filter($fn)->count();
        }

        $list = isset($tabFilter[$tab]) ? $all->filter($tabFilter[$tab]) : $all;
        if (! isset($tabFilter[$tab])) {
            $tab = 'semua';
        }
        if ($q !== '') {
            $needle = mb_strtolower($q);
            $list = $list->filter(fn (Order $o) => str_contains(mb_strtolower(
                $o->order_number . ' ' . $o->customer_name . ' ' . $o->customer_email . ' ' . $o->preloved_item_names
            ), $needle));
        }
        $list = $list->values();

        $perPage = 15;
        $page = max(1, (int) $request->query('page', 1));
        $orders = new LengthAwarePaginator(
            $list->forPage($page, $perPage)->values(),
            $list->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.preloved-orders', [
            'tab'    => $tab,
            'q'      => $q,
            'orders' => $orders,
            'counts' => $counts,
            'orderStatusLabels' => $this->orderStatusLabels,
        ]);
    }

    /* ── STORE Item ────────────────────────────────────────── */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());

        $count = $this->uploadedImagesCount($request);
        if ($count < 1) {
            return back()->withErrors(['images' => 'Wajib unggah minimal 1 foto barang.'])->withInput();
        }
        if ($count > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 foto untuk setiap barang.'])->withInput();
        }

        unset($data['remove_photos'], $data['images']);

        $photos = $this->storeImages($request);
        $data['photos'] = $photos;
        $data['photo'] = $photos[0] ?? null;
        $data['status'] = 'Tersedia';

        PrelovedItem::create($data);

        return redirect()->route('admin.preloved')
            ->with('success', 'Barang berhasil ditambahkan dan langsung tampil di katalog pelanggan.');
    }

    /* ── UPDATE Item ───────────────────────────────────────── */
    public function update(Request $request, PrelovedItem $item)
    {
        $data = $request->validate($this->rules($item), $this->messages());

        // Foto lama yang dicentang untuk dihapus (hanya yang memang milik barang ini).
        $current = $this->currentPhotos($item);
        $removed = array_values(array_intersect((array) $request->input('remove_photos', []), $current));
        $kept = array_values(array_diff($current, $removed));

        $total = count($kept) + $this->uploadedImagesCount($request);
        if ($total < 1) {
            return back()->withErrors(['images' => 'Barang wajib memiliki minimal 1 foto.'])->withInput();
        }
        if ($total > 10) {
            return back()->withErrors(['images' => 'Maksimal 10 foto untuk setiap barang.'])->withInput();
        }

        unset($data['remove_photos'], $data['images']);

        $photos = array_values(array_merge($kept, $this->storeImages($request)));
        $data['photos'] = $photos;
        $data['photo'] = $photos[0] ?? null;

        $item->update($data);

        if ($removed) {
            Storage::disk('public')->delete($removed);
        }

        return redirect()->route('admin.preloved')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /* ── DESTROY Item ──────────────────────────────────────── */
    public function destroy(PrelovedItem $item)
    {
        $photos = $this->currentPhotos($item);
        $item->delete();

        if ($photos) {
            Storage::disk('public')->delete($photos);
        }

        return redirect()->route('admin.preloved')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /* ── TOGGLE DRAFT ──────────────────────────────────────── */
    public function toggleDraft(PrelovedItem $item)
    {
        if ($item->status === 'Terjual') {
            return back()->with('error', 'Barang yang sudah terjual tidak bisa diubah statusnya.');
        }

        if ($item->status === 'Draft') {
            // Syarat tayang: sama dengan yang ditampilkan di halaman produk pelanggan.
            $missing = [];
            if ((int) $item->price < 1000) {
                $missing[] = 'harga jual';
            }
            if (count($this->currentPhotos($item)) < 1) {
                $missing[] = 'foto';
            }
            if (trim((string) $item->description) === '') {
                $missing[] = 'deskripsi';
            }
            if (trim((string) $item->flaws) === '') {
                $missing[] = 'kekurangan';
            }
            if ($missing) {
                return back()->with('error', 'Lengkapi dulu sebelum dipublikasikan: ' . implode(', ', $missing) . '. Klik Ubah pada barangnya.');
            }
        }

        $item->update(['status' => $item->status === 'Draft' ? 'Tersedia' : 'Draft']);

        return redirect()->route('admin.preloved')
            ->with('success', $item->status === 'Tersedia' ? 'Barang sekarang tampil di katalog pelanggan.' : 'Barang disembunyikan dari katalog pelanggan.');
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

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    /** Aturan isian: sama dengan yang ditampilkan di halaman produk pelanggan. */
    private function rules(?PrelovedItem $item = null): array
    {
        // Kategori lama yang sudah tersimpan tetap boleh dipertahankan saat mengubah barang.
        $categories = array_values(array_filter(array_unique(array_merge(PrelovedItem::CATEGORIES, [$item?->category]))));

        return [
            'name'          => 'required|string|min:3|max:255',
            'category'      => ['required', Rule::in($categories)],
            'condition'     => ['required', 'integer', Rule::in(array_keys(PrelovedItem::CONDITIONS))],
            'price'         => 'required|integer|min:1000',
            'seller'        => 'nullable|string|max:255',
            'description'   => 'required|string|min:10|max:2000',
            'function_note' => 'nullable|string|max:255',
            'completeness'  => 'required|string|max:255',
            'flaws'         => 'required|string|max:255',
            'images'        => 'nullable|array|max:10',
            'images.*'      => 'image|mimes:jpeg,jpg,png,webp|max:5120',
            'remove_photos'   => 'nullable|array',
            'remove_photos.*' => 'string',
            'weight'        => 'required|integer|min:1|max:100000',
            'length'        => 'required|integer|min:1|max:500',
            'width'         => 'required|integer|min:1|max:500',
            'height'        => 'required|integer|min:1|max:500',
        ];
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Isi nama barang.',
            'name.min' => 'Nama barang minimal 3 karakter.',
            'category.in' => 'Pilih kategori dari daftar.',
            'condition.in' => 'Pilih kondisi dari daftar.',
            'price.required' => 'Isi harga jual.',
            'price.min' => 'Harga jual minimal Rp1.000.',
            'description.required' => 'Isi deskripsi barang, ini tampil di halaman produk.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'completeness.required' => 'Isi kelengkapan, misalnya "Unit, kabel, dus".',
            'flaws.required' => 'Isi kekurangan barang, atau tulis "Tidak ada" kalau memang tidak ada.',
            'images.*.image' => 'File harus berupa foto.',
            'images.*.mimes' => 'Foto harus JPG, PNG, atau WEBP.',
            'images.*.max' => 'Ukuran tiap foto maksimal 5 MB.',
            'weight.required' => 'Isi berat barang (gram), dipakai untuk ongkir.',
            'length.required' => 'Isi panjang barang (cm), dipakai untuk ongkir.',
            'width.required' => 'Isi lebar barang (cm), dipakai untuk ongkir.',
            'height.required' => 'Isi tinggi barang (cm), dipakai untuk ongkir.',
        ];
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
        return $request->hasFile('images') ? count($request->file('images')) : 0;
    }

    /** Semua foto barang (kolom photos ditambah kolom photo lama bila belum ada di dalamnya). */
    private function currentPhotos(PrelovedItem $item): array
    {
        return array_values(array_unique(array_filter(array_merge((array) ($item->photos ?? []), [$item->photo]))));
    }
}
