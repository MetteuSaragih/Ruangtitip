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

        $pendapatanBulanIni = $this->pendapatanDalamRentang(now()->startOfMonth(), now());
        $pendapatanBulanLalu = $this->pendapatanDalamRentang(now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth());
        $pendapatanGrowth = $pendapatanBulanLalu > 0
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100)
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        return view('admin.ruang-titip', [
            'pageTab'             => $tab,
            'orderTab'            => $oTab,
            'orderData'           => $orderData,
            'rooms'               => $rooms,
            'defaultPricing'      => $this->defaultPricing,
            'allFacilities'       => $this->allFacilities,
            'pendapatanBulanIni'  => $pendapatanBulanIni,
            'pendapatanGrowth'    => $pendapatanGrowth,
        ]);
    }

    private function pendapatanDalamRentang($from, $to): int
    {
        return (int) TitipanOrder::where('payment_status', 'PAID')
            ->whereBetween('created_at', [$from, $to])
            ->sum('total');
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

    /* ── UPLOAD BUKTI (Operasional) ────────────────────────── */
    public function uploadProof(Request $request, TitipanOrder $order)
    {
        $data = $request->validate([
            'proof_photo' => 'required|image|max:5120',
        ]);

        $order->update([
            'proof_photo' => $request->file('proof_photo')->store('titipan-proofs', 'public'),
        ]);

        return redirect()->route('admin.ruang-titip', ['tab' => 'operasional'])
            ->with('success', 'Bukti penitipan berhasil diunggah.');
    }

    /* ── UPDATE STATUS (Operasional) ───────────────────────── */
    public function updateStatus(Request $request, TitipanOrder $order)
    {
        $data = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(TitipanOrder::FLOW)),
        ]);

        $order->update(['status' => $data['status']]);

        return redirect()->route('admin.ruang-titip', ['tab' => 'operasional'])
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

    /** Normalisasi nomor HP ke format internasional (62...) untuk link wa.me. */
    private function waNumber(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);
        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }
        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        return '62' . $digits;
    }

    /** Daftar template pesan WA siap pakai untuk satu pesanan Ruang Titip. */
    private function waTemplates(string $customer, TitipanOrder $order): array
    {
        $code = $order->code();
        $endDate = optional($order->date_end)->format('d M Y') ?? '-';
        $daysLeft = $order->date_end ? max(0, now()->startOfDay()->diffInDays($order->date_end->startOfDay(), false)) : null;
        $daysLeftText = $daysLeft !== null ? $daysLeft . ' hari' : 'beberapa hari';

        return [
            [
                'key' => 'sampai',
                'label' => 'Barang Sudah Sampai di Gudang',
                'text' => "Halo {$customer}, barang penitipanmu (kode {$code}) sudah sampai dan tersimpan aman di gudang RUTIP. Terima kasih telah menggunakan layanan kami! 📦",
            ],
            [
                'key' => 'bukti',
                'label' => 'Bukti Foto Sudah Diunggah',
                'text' => "Halo {$customer}, bukti foto kondisi barang penitipanmu (kode {$code}) sudah kami unggah. Silakan cek di halaman Pesanan Saya ya. 📸",
            ],
            [
                'key' => 'durasi',
                'label' => 'Sisa Durasi Penitipan',
                'text' => "Halo {$customer}, masa penitipan barangmu (kode {$code}) tersisa {$daysLeftText} lagi (berakhir {$endDate}). Jangan lupa untuk perpanjang atau ambil barangmu ya! ⏰",
            ],
            [
                'key' => 'proses_keluar',
                'label' => 'Barang Sedang Diproses Keluar',
                'text' => "Halo {$customer}, barang penitipanmu (kode {$code}) sedang kami proses untuk pengembalian/pengiriman. Mohon ditunggu ya! 🚚",
            ],
            [
                'key' => 'selesai',
                'label' => 'Barang Berhasil Dikirim/Diterima',
                'text' => "Halo {$customer}, barang penitipanmu (kode {$code}) sudah berhasil dikirim/diterima. Terima kasih telah menggunakan RUTIP! 🙏",
            ],
        ];
    }

    private function orderData(): array
    {
        $blank = ['baru' => [], 'inspeksi' => [], 'gudang' => [], 'keluar' => []];
        $returnModeByLogistic = [
            'self' => 'Ambil Sendiri',
            'rutip' => 'Minta Diantar',
            'instant' => 'Ekspedisi Biteship',
        ];

        return TitipanOrder::with('user')->latest()->get()
            ->map(function (TitipanOrder $order) use ($returnModeByLogistic) {
                $customer = $order->user?->name ?? 'Pelanggan';
                $waNumber = $this->waNumber($order->user?->phone);

                return [
                    'orderId' => $order->id,
                    'id' => $order->code(),
                    'customer' => $customer,
                    'wa' => $order->user?->phone ?? '-',
                    'waNumber' => $waNumber,
                    'items' => collect($order->items ?? [])->map(fn ($qty, $code) => strtoupper($code) . ' x ' . $qty)->implode(', '),
                    'qty' => $order->totalItems(),
                    'duration' => optional($order->date_start)->diffInMonths($order->date_end, false) ?: 1,
                    'deadline' => optional($order->date_end)->format('d M Y') ?? '-',
                    'status' => $order->statusMeta()['label'],
                    'statusKey' => $order->status,
                    'hasProof' => ! empty($order->proof_photo),
                    'proofUrl' => $order->proof_photo ? asset('storage/' . $order->proof_photo) : null,
                    'returnMode' => $returnModeByLogistic[$order->logistic] ?? null,
                    'trackingId' => $order->biteship_tracking_id,
                    'rackCode' => null,
                    'waTemplates' => $waNumber ? $this->waTemplates($customer, $order) : [],
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
