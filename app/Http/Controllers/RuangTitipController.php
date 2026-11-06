<?php

namespace App\Http\Controllers;

use App\Services\AdminNotifier;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use App\Support\StorageSizes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| RuangTitipController
|--------------------------------------------------------------------------
| Alur pemesanan titip (barang SELALU diantar sendiri ke gudang):
|  1. index       daftar gudang
|  2. show        detail gudang (ukuran dan paket harga)
|  3. detailForm  periode titip + ukuran dan jumlah barang
|  4. checkout    ringkasan, pilih tanggal dan jam antar (dari jam operasional gudang), setuju, buat pesanan
|  5. success     halaman pembayaran QRIS (unggah bukti)
|
| Biaya = harga paket termurah per barang untuk lama titip (lihat StorageSizes::cost) + biaya layanan.
| Tidak ada ongkir, jasa packing, atau jemputan.
*/

class RuangTitipController extends Controller
{
    private const PLATFORM_FEE = 1000; // biaya layanan per pesanan (antar sendiri, tanpa Biteship)

    /* ─── Helper session ─── */
    private function state(Request $r): array
    {
        return $r->session()->get('titip', []);
    }

    private function setState(Request $r, array $data): void
    {
        $r->session()->put('titip', array_merge($this->state($r), $data));
    }

    /* ─── Perhitungan biaya ─── */
    private function calc(array $s): array
    {
        $items = $s['items'] ?? [];

        // Lama titip dalam hari: hari mulai dan hari selesai ikut dihitung, minimal 1.
        $days = 1;
        if (! empty($s['date_start']) && ! empty($s['date_end'])) {
            $diff = (strtotime($s['date_end']) - strtotime($s['date_start'])) / 86400;
            $days = max(1, (int) round($diff) + 1);
        }

        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $sizes = $this->itemSizesFromStorage($storage)->keyBy('code');

        $valid = [];
        $totalItems = 0;
        $units = 0;
        foreach ($items as $code => $qty) {
            $qty = (int) $qty;
            if ($qty > 0 && isset($sizes[$code])) {
                $valid[$code] = $qty;
                $totalItems += $qty;
                $units += $qty * StorageSizes::unit($code);
            }
        }

        $quote = StorageSizes::quote($valid, $days);
        $itemSubtotal = $quote['total'];
        $platformFee = self::PLATFORM_FEE;

        return [
            'days'         => $days,
            'perDay'       => $quote['per_day'],
            'units'        => $units,
            'totalItems'   => $totalItems,
            'quote'        => $quote['items'],
            'itemSubtotal' => $itemSubtotal,
            'courierCost'  => 0,
            'packingCost'  => 0,
            'platform_fee' => $platformFee,
            'total'        => $itemSubtotal + $platformFee,
        ];
    }

    /* ═══ Daftar gudang ═══ */
    public function index()
    {
        $storages = StorageRoom::active()->latest()->get();

        return view('dashboard.ruang-titip.index', compact('storages'));
    }

    /* ═══ Detail gudang ═══ */
    public function show(Request $r, StorageRoom $storage)
    {
        abort_unless($storage->active, 404);

        // Ganti gudang berarti harga dan kapasitas berbeda: mulai pesanan dari awal.
        if (($this->state($r)['storage_id'] ?? null) !== $storage->id) {
            $r->session()->forget('titip');
        }
        $this->setState($r, ['storage_id' => $storage->id]);

        return view('dashboard.ruang-titip.detail', compact('storage'));
    }

    /* ═══ Periode + barang (GET) ═══ */
    public function detailForm(Request $r)
    {
        $storage = $this->bookableStorage($this->state($r));
        if (! $storage) {
            return redirect()->route('ruang-titip.index')->with('error', 'Pilih ruang titip yang tersedia dulu.');
        }
        $sizes = $this->itemSizesFromStorage($storage);
        $s = $this->state($r);
        $selectedItems = $s['items'] ?? [];
        $remainingUnits = $this->remainingUnits($storage);
        $calc = $this->calc($s);

        return view('dashboard.ruang-titip.detail-item', compact('sizes', 's', 'selectedItems', 'storage', 'calc', 'remainingUnits'));
    }

    /* Periode + barang (POST) */
    public function detailStore(Request $r)
    {
        $data = $r->validate([
            'items'      => 'required|array',
            'items.*'    => 'nullable|integer|min:0|max:99',
            'date_start' => 'required|date|after_or_equal:today',
            'date_end'   => 'required|date|after:date_start',
        ], [
            'items.*.integer' => 'Jumlah barang harus berupa angka.',
            'items.*.max' => 'Jumlah barang per ukuran maksimal 99.',
            'date_start.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini.',
            'date_end.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $maxDays = (int) config('item_sizes.plans.max_days', 730);
        $days = (int) round((strtotime($data['date_end']) - strtotime($data['date_start'])) / 86400) + 1;
        if ($days > $maxDays) {
            return back()->withErrors(['date_end' => "Lama titip maksimal {$maxDays} hari. Untuk waktu lebih lama, hubungi admin."])->withInput();
        }

        $items = array_map('intval', $data['items']);
        $items = array_filter($items, fn ($q) => $q > 0);
        if (empty($items)) {
            return back()->withErrors(['items' => 'Pilih minimal satu barang.'])->withInput();
        }

        $storage = $this->bookableStorage($this->state($r));
        if (! $storage) {
            return redirect()->route('ruang-titip.index')->with('error', 'Ruang titip ini tidak tersedia. Pilih ruang lain.');
        }

        // Hanya ukuran yang diterima ruang ini; kode lain (termasuk kode lama) ditolak.
        $accepted = $storage->accepted_sizes;
        $rejected = array_values(array_diff(array_map('strval', array_keys($items)), $accepted));
        if ($rejected) {
            return back()->withErrors(['items' => 'Ukuran ' . implode(', ', $rejected) . ' tidak diterima di ruang ini. Pilih ukuran ' . implode(', ', $accepted) . '.'])->withInput();
        }
        // Urutkan sesuai urutan ukuran (S, M, L, XL, XXL).
        $ordered = [];
        foreach ($accepted as $code) {
            if (isset($items[$code])) {
                $ordered[$code] = $items[$code];
            }
        }
        $items = $ordered;

        $units = $this->unitsOf($items);
        $remaining = $this->remainingUnits($storage);
        if ($units > $remaining) {
            return back()->withErrors(['items' => $remaining > 0
                ? "Barangmu butuh {$units} setara ukuran S, sedangkan kapasitas ruang ini tinggal {$remaining} setara ukuran S. Kurangi jumlah barang atau pilih ruang lain."
                : 'Ruang ini sudah penuh. Pilih ruang lain.'])->withInput();
        }

        $this->setState($r, [
            'item_type'  => 'ukuran',
            'items'      => $items,
            'date_start' => $data['date_start'],
            'date_end'   => $data['date_end'],
            'logistic'   => 'self',
        ]);

        return redirect()->route('ruang-titip.checkout');
    }

    /* ═══ Checkout (GET) ═══ */
    public function checkout(Request $r)
    {
        $s = $this->state($r);
        if ($redirect = $this->resumeStep($s)) {
            return $redirect;
        }

        $calc = $this->calc($s);
        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $drop = $this->dropoffInfo($s, $storage);

        return view('dashboard.ruang-titip.checkout', compact('s', 'calc', 'storage', 'drop'));
    }

    /* Checkout (POST) — simpan pesanan; pembayaran lewat QRIS dengan bukti yang diperiksa admin */
    public function place(Request $r)
    {
        $s = $this->state($r);
        if ($redirect = $this->resumeStep($s)) {
            return $redirect;
        }

        $storage = $this->bookableStorage($s);
        if (! $storage) {
            return redirect()->route('ruang-titip.index')->with('error', 'Ruang titip ini tidak tersedia. Pilih ruang lain.');
        }

        // Buang barang yang tidak ada lagi di daftar harga ruang (harga atau ukuran bisa diubah admin).
        $validSizes = $this->itemSizesFromStorage($storage)->keyBy('code');
        $s['items'] = array_filter($s['items'], fn ($qty, $code) => isset($validSizes[$code]) && (int) $qty > 0, ARRAY_FILTER_USE_BOTH);
        if (empty($s['items'])) {
            return redirect()->route('ruang-titip.detail-item')->with('error', 'Pilihan barang sudah tidak tersedia. Pilih ulang barangmu.');
        }
        $remaining = $this->remainingUnits($storage);
        if ($this->unitsOf($s['items']) > $remaining) {
            return redirect()->route('ruang-titip.detail-item')->with('error', "Kapasitas ruang ini tidak cukup lagi (tinggal {$remaining} setara ukuran S). Kurangi jumlah barang atau pilih ruang lain.");
        }
        $s['item_type'] = 'ukuran';

        $drop = $this->dropoffInfo($s, $storage);

        $validator = Validator::make($r->all(), [
            'agree'       => 'accepted',
            'pickup_date' => ['required', 'date_format:Y-m-d'],
            'pickup_time' => ['required', 'date_format:H:i'],
        ], [
            'agree.accepted' => 'Kamu harus menyetujui Syarat & Ketentuan.',
            'pickup_date.required' => 'Pilih tanggal antar barang.',
            'pickup_date.date_format' => 'Tanggal antar tidak valid.',
            'pickup_time.required' => 'Pilih jam antar barang.',
            'pickup_time.date_format' => 'Jam antar tidak valid.',
        ]);

        $validator->after(function ($validator) use ($r, $storage, $drop) {
            if ($validator->errors()->has('pickup_date') || $validator->errors()->has('pickup_time')) {
                return;
            }

            $date = Carbon::createFromFormat('Y-m-d', $r->input('pickup_date'), 'Asia/Jakarta')->startOfDay();
            if ($date->lt($drop['min']) || $date->gt($drop['max'])) {
                $validator->errors()->add('pickup_date', 'Tanggal antar harus antara ' . $drop['min']->translatedFormat('j M Y') . ' dan ' . $drop['max']->translatedFormat('j M Y') . '.');

                return;
            }

            $slots = $storage->dropoffSlots(StorageRoom::dayTypeOf($date));
            if (! $slots) {
                $validator->errors()->add('pickup_date', 'Gudang tutup pada tanggal itu. Pilih tanggal lain.');

                return;
            }
            if (! in_array($r->input('pickup_time'), $slots, true)) {
                $validator->errors()->add('pickup_time', 'Jam antar harus dalam jam operasional gudang. Pilih salah satu jam yang tersedia.');

                return;
            }

            $arrive = Carbon::parse($r->input('pickup_date') . ' ' . $r->input('pickup_time'), 'Asia/Jakarta');
            $minAhead = (int) config('pickup_slots.dropoff_min_minutes_ahead', 60);
            if ($arrive->lt(now('Asia/Jakarta')->addMinutes($minAhead))) {
                $validator->errors()->add('pickup_time', "Jam antar minimal {$minAhead} menit dari sekarang. Pilih jam yang lebih lambat atau tanggal lain.");
            }
        });

        $validator->validate();

        $calc = $this->calc($s);

        $order = TitipanOrder::create([
            'user_id'        => Auth::id(),
            'storage_id'     => $storage->id,
            'item_type'      => $s['item_type'],
            'items'          => $s['items'],
            'date_start'     => $s['date_start'] ?? null,
            'date_end'       => $s['date_end'] ?? null,
            'pickup_date'    => $r->input('pickup_date'),
            'pickup_time'    => $r->input('pickup_time'),
            'pickup_time_end' => null,
            'logistic'       => 'self',
            'address'        => null,
            'note'           => null,
            'courier_code'   => null,
            'courier_service_code' => null,
            'courier_company' => null,
            'address_area_id' => null,
            'address_postal_code' => null,
            'packing'        => null,
            'payment_method' => 'qris',
            'item_subtotal'  => $calc['itemSubtotal'],
            'courier_cost'   => 0,
            'packing_cost'   => 0,
            'platform_fee'   => $calc['platform_fee'],
            'total'          => $calc['total'],
            'status'         => 'menunggu_pembayaran',
            'payment_status' => 'UNPAID',
        ]);

        AdminNotifier::newTitip($order);

        $r->session()->forget('titip');

        return redirect()->route('ruang-titip.success', $order);
    }

    public function success(TitipanOrder $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        return view('dashboard.ruang-titip.success', compact('order'));
    }

    /**
     * Batas tanggal antar dan data jam operasional untuk halaman checkout.
     * Tanggal antar: paling cepat hari ini (atau 14 hari sebelum tanggal mulai), paling lambat tanggal mulai.
     */
    private function dropoffInfo(array $s, ?StorageRoom $storage): array
    {
        $today = now('Asia/Jakarta')->startOfDay();
        $start = Carbon::parse($s['date_start'], 'Asia/Jakarta')->startOfDay();
        $min = $start->copy()->subDays((int) config('pickup_slots.dropoff_days_before_start', 14));
        if ($min->lt($today)) {
            $min = $today->copy();
        }
        $max = $start->lt($min) ? $min->copy() : $start->copy();

        return [
            'min' => $min,
            'max' => $max,
            'hours' => $storage ? $storage->hours : StorageRoom::DEFAULT_HOURS,
            'slots' => $storage ? $storage->dropoffSlotsByDay() : ['weekday' => [], 'saturday' => [], 'sunday' => []],
            'now' => now('Asia/Jakarta')->format('Y-m-d H:i'),
            'min_ahead' => (int) config('pickup_slots.dropoff_min_minutes_ahead', 60),
        ];
    }

    /** Ruang yang dipilih di sesi, hanya bila masih aktif dan masih punya sisa kapasitas. */
    private function bookableStorage(array $s): ?StorageRoom
    {
        $storage = StorageRoom::find($s['storage_id'] ?? null);
        if (! $storage || ! $storage->active) {
            return null;
        }

        return $this->remainingUnits($storage) > 0 ? $storage : null;
    }

    /** Kirim pengguna kembali ke langkah yang belum lengkap sebelum checkout. */
    private function resumeStep(array $s): ?\Illuminate\Http\RedirectResponse
    {
        if (empty($s['storage_id'])) {
            return redirect()->route('ruang-titip.index');
        }
        if (empty($s['items']) || empty($s['date_start']) || empty($s['date_end'])) {
            return redirect()->route('ruang-titip.detail-item');
        }
        // Sesi lama: tanggal mulai sudah lewat, minta atur periode lagi.
        if (Carbon::parse($s['date_start'], 'Asia/Jakarta')->startOfDay()->lt(now('Asia/Jakarta')->startOfDay())) {
            return redirect()->route('ruang-titip.detail-item')->with('error', 'Tanggal mulai titip sudah lewat. Atur periode titipmu lagi.');
        }

        return null;
    }

    /** Sisa kapasitas ruang dalam satuan "setara ukuran S". */
    private function remainingUnits(StorageRoom $storage): int
    {
        return max(0, (int) $storage->capacity_total - (int) $storage->capacity_used);
    }

    /** Kebutuhan ruang dalam satuan "setara ukuran S": sum(qty x unit ukuran). */
    private function unitsOf(array $items): int
    {
        $units = 0;
        foreach ($items as $code => $qty) {
            $units += (int) $qty * StorageSizes::unit((string) $code);
        }

        return $units;
    }

    /**
     * Ukuran yang diterima ruang beserta spesifikasi dan harga terkini
     * (kode, label, price harian, monthly, tiers, unit, side, sum, kg, dims, fits).
     */
    private function itemSizesFromStorage(?StorageRoom $storage)
    {
        if (! $storage) {
            return collect();
        }

        return collect($storage->size_rows)->map(fn (array $row) => (object) $row)->values();
    }
}
