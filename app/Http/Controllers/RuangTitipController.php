<?php

namespace App\Http\Controllers;

use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use App\Models\UserAddress;
use App\Services\BiteshipService;
use App\Services\DistanceService;
use App\Support\StorageSizes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| RuangTitipController (REVISI)
|--------------------------------------------------------------------------
| Alur baru:
|  1. index        — daftar gudang
|  2. show         — detail gudang
|  3. detailForm   — GABUNGAN item + rentang tanggal (Detail Penitipan)
|  4. logistikForm — "Opsi Logistik" (dulu Screen 5)
|     - self     -> langsung checkout
|     - rutip    -> alamat -> checkout (kurir otomatis Kurir RuTip, tanpa pilih)
|     - instant  -> alamat -> pilih kurir -> checkout
|  5. alamat      — daftar alamat tersimpan + tambah baru
|  6. kurir       — hanya untuk instant
|  7. checkout    — rincian + bayar
|
| Tahap "Packing" terpisah DIHAPUS (sudah include di logistik rutip).
|
| Jarak km untuk kurir RuTip dihitung otomatis dari koordinat alamat
| customer (hasil geocoding saat alamat disimpan) ke koordinat gudang,
| lihat DistanceService. DEFAULT_KM cuma fallback kalau geocoding gagal.
*/

class RuangTitipController extends Controller
{
    private const PLATFORM_FEE          = 1000;   // biaya layanan jika logistik tidak pakai Biteship
    private const PLATFORM_FEE_BITESHIP = 2000;   // biaya layanan jika logistik pakai Biteship
    private const PICKUP_PER_KM  = 4000;   // biaya antar-jemput per km
    private const PICKUP_PER_BOX = 2000;   // biaya antar-jemput per kardus
    private const PACKING_PER_BOX = 3000;  // jasa packing per kardus
    private const DEFAULT_KM       = 5;      // fallback kalau koordinat alamat/gudang tidak tersedia

    public function __construct(private DistanceService $distance)
    {
    }

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

        // durasi dalam hari: tanggal selesai - tanggal mulai + 1 (hari mulai dan hari selesai ikut dihitung), minimal 1
        $days = 1;
        if (!empty($s['date_start']) && !empty($s['date_end'])) {
            $diff = (strtotime($s['date_end']) - strtotime($s['date_start'])) / 86400;
            $days = max(1, (int) round($diff) + 1);
        }

        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $sizes = $this->itemSizesFromStorage($storage)->keyBy('code');
        $perDay = 0;
        $totalItems = 0;
        $units = 0;
        foreach ($items as $code => $qty) {
            $qty = (int) $qty;
            if ($qty > 0 && isset($sizes[$code])) {
                $totalItems += $qty;
                $units += $qty * StorageSizes::unit($code);
                $perDay += $qty * StorageSizes::price($code);
            }
        }
        $itemSubtotal = $perDay * $days;

        $logistic     = $s['logistic'] ?? 'self';
        $courierCost  = 0;
        $packingCost  = 0;
        $kmCost       = 0;
        $pickupBoxCost = 0;
        $km           = self::DEFAULT_KM;

        if ($logistic === 'rutip') {
            $km = $this->resolveKm($s);

            // Total Anjem = (harga/km × jarak) + (harga/kardus × jumlah item) + (jasa packing/kardus × jumlah item)
            $kmCost        = self::PICKUP_PER_KM * $km;
            $pickupBoxCost = self::PICKUP_PER_BOX * $totalItems;
            $packingCost   = self::PACKING_PER_BOX * $totalItems;
            $courierCost   = $kmCost + $pickupBoxCost + $packingCost;
        } elseif ($logistic === 'instant' && !empty($s['courier_code'])) {
            $courierCost = (int) ($s['courier_cost'] ?? 0);
        }

        $platformFee = $logistic === 'instant' ? self::PLATFORM_FEE_BITESHIP : self::PLATFORM_FEE;
        $total = $itemSubtotal + $courierCost + $platformFee;

        return [
            'days'          => $days,
            'perDay'        => $perDay,
            'units'         => $units,
            'totalItems'    => $totalItems,
            'itemSubtotal'  => $itemSubtotal,
            'courierCost'   => $courierCost,
            'packingCost'   => $packingCost,
            'kmCost'        => $kmCost,
            'pickupBoxCost' => $pickupBoxCost,
            'km'            => $km,
            'platform_fee'  => $platformFee,
            'total'         => $total,
        ];
    }

    /** Jarak (km, dibulatkan ke atas, minimal 1) dari alamat customer ke gudang RuTip. */
    private function resolveKm(array $s): int
    {
        $lat = $s['address_lat'] ?? null;
        $lng = $s['address_lng'] ?? null;
        $warehouse = $this->distance->warehouseCoords();

        if (! $lat || ! $lng || ! $warehouse) {
            return self::DEFAULT_KM;
        }

        $km = $this->distance->distanceKm((float) $lat, (float) $lng, $warehouse['lat'], $warehouse['lng']);

        return max(1, (int) ceil($km));
    }

    /* ═══ SCREEN 1 — Daftar gudang ═══ */
    public function index()
    {
        $storages = StorageRoom::active()->latest()->get();
        return view('dashboard.ruang-titip.index', compact('storages'));
    }

    /* ═══ SCREEN 2 — Detail gudang ═══ */
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

    /* ═══ SCREEN 3 — GABUNGAN: item + rentang tanggal (GET) ═══ */
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

    /* SCREEN 3 (POST) — simpan item + tanggal sekaligus */
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

        $items = array_map('intval', $data['items']);
        $items = array_filter($items, fn ($q) => $q > 0);
        if (empty($items)) {
            return back()->withErrors(['items' => 'Pilih minimal satu barang.'])->withInput();
        }

        $storage = $this->bookableStorage($this->state($r));
        if (! $storage) {
            return redirect()->route('ruang-titip.index')->with('error', 'Ruang titip ini tidak tersedia. Pilih ruang lain.');
        }

        // Hanya ukuran yang diterima ruang ini (S/M/L/XL); kode lain (termasuk kode lama) ditolak.
        $accepted = $storage->accepted_sizes;
        $rejected = array_values(array_diff(array_map('strval', array_keys($items)), $accepted));
        if ($rejected) {
            return back()->withErrors(['items' => 'Ukuran ' . implode(', ', $rejected) . ' tidak diterima di ruang ini. Pilih ukuran ' . implode(', ', $accepted) . '.'])->withInput();
        }
        // Urutkan sesuai urutan ukuran (S, M, L, XL).
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

        // Isi barang berubah, jadi tarif kurir instan yang lama tidak berlaku lagi.
        if (($this->state($r)['logistic'] ?? null) === 'instant') {
            $this->setState($r, ['courier_code' => null, 'courier_service_code' => null, 'courier_name' => null, 'courier_cost' => 0]);
        }

        $this->setState($r, [
            'item_type'  => 'ukuran',
            'items'      => $items,
            'date_start' => $data['date_start'],
            'date_end'   => $data['date_end'],
        ]);

        return redirect()->route('ruang-titip.logistik');
    }

    /* ═══ SCREEN 4 — Opsi Logistik (GET) ═══ */
    public function logistikForm(Request $r)
    {
        $s = $this->state($r);
        if (empty($s['items'])) return redirect()->route('ruang-titip.detail-item');
        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $calc = $this->calc($s);
        return view('dashboard.ruang-titip.logistik', compact('s', 'storage', 'calc'));
    }

    /* SCREEN 4 (POST) */
    public function logistikStore(Request $r)
    {
        $data = $r->validate(['logistic' => 'required|in:self,rutip,instant']);
        $logistic = $data['logistic'];

        // Reset pilihan kurir lama
        $this->setState($r, [
            'logistic' => $logistic,
            'courier_code' => null,
            'courier_service_code' => null,
            'courier_name' => null,
            'courier_cost' => 0,
        ]);

        if ($logistic === 'self') {
            return redirect()->route('ruang-titip.checkout');
        }
        if ($logistic === 'rutip') {
            // Kurir otomatis Kurir RuTip, tanpa pilih kurir
            $this->setState($r, ['courier_code' => 'rutip_fleet']);
        }
        return redirect()->route('ruang-titip.alamat');
    }

    /* ═══ SCREEN 5 — Alamat (GET) ═══ */
    public function alamatForm(Request $r)
    {
        if (empty($this->state($r)['logistic'])) return redirect()->route('ruang-titip.logistik');

        $addresses = UserAddress::where('user_id', Auth::id())
            ->orderByDesc('is_primary')->orderByDesc('id')->get();

        $s = $this->state($r);
        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $calc = $this->calc($s);
        return view('dashboard.ruang-titip.alamat', compact('addresses', 's', 'storage', 'calc'));
    }

    /* SCREEN 5 (POST) — pilih alamat tersimpan ATAU tambah baru */
    public function alamatStore(Request $r)
    {
        $mode = $r->input('mode', 'select');

        if ($mode === 'new') {
            $data = $r->validate([
                'label'      => 'nullable|string|max:50',
                'address'    => 'required|string',
                'note'       => 'nullable|string',
                'area_id'    => 'required|string',
                'area_name'  => 'nullable|string',
                'postal_code' => 'nullable|string',
                'is_primary' => 'nullable|boolean',
            ]);
            // jika dijadikan utama, reset utama lama
            if (!empty($data['is_primary'])) {
                UserAddress::where('user_id', Auth::id())->update(['is_primary' => false]);
            }
            $addr = UserAddress::create([
                'user_id'    => Auth::id(),
                'label'      => $data['label'] ?? 'Alamat',
                'address'    => $data['address'],
                'note'       => $data['note'] ?? null,
                'is_primary' => (bool) ($data['is_primary'] ?? false),
                'area_id'    => $data['area_id'],
                'area_name'  => $data['area_name'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
            ]);
            $addressId = $addr->id;
        } else {
            $data = $r->validate(['address_id' => 'required|exists:user_addresses,id']);
            $addressId = (int) $data['address_id'];
        }

        // pastikan alamat milik user
        $addr = UserAddress::where('user_id', Auth::id())->findOrFail($addressId);
        if (! $addr->area_id) {
            return back()->withErrors(['address' => 'Alamat ini belum punya kecamatan tersimpan, pilih/tambah alamat baru dengan kecamatan.']);
        }

        $addr = $this->distance->ensureAddressCoords($addr);

        $this->setState($r, [
            'address_id' => $addr->id,
            'address' => $addr->address,
            'note' => $addr->note,
            'address_area_id' => $addr->area_id,
            'address_postal_code' => $addr->postal_code,
            'address_lat' => $addr->latitude,
            'address_lng' => $addr->longitude,
        ]);

        // instant -> pilih kurir; rutip -> langsung checkout
        return ($this->state($r)['logistic'] ?? '') === 'instant'
            ? redirect()->route('ruang-titip.kurir')
            : redirect()->route('ruang-titip.checkout');
    }

    /* ═══ SCREEN 6 — Pilih kurir (GET) — HANYA instant ═══ */
    public function kurirForm(Request $r)
    {
        $s = $this->state($r);
        if (($s['logistic'] ?? '') !== 'instant' || empty($s['address'])) {
            return redirect()->route('ruang-titip.logistik');
        }

        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $calc = $this->calc($s);
        return view('dashboard.ruang-titip.kurir', compact('s', 'storage', 'calc'));
    }

    /* AJAX — hitung ongkir live Biteship dari alamat pelanggan ke gudang RUTIP */
    public function kurirRates(Request $r, BiteshipService $biteship)
    {
        $s = $this->state($r);
        if (empty($s['address_area_id'])) {
            return response()->json(['success' => false, 'message' => 'Alamat belum dipilih.'], 422);
        }

        $result = $biteship->getRates(
            $this->originFromAddress($s),
            $this->destinationWarehouse(),
            $this->itemsToBiteship($s),
            config('biteship.checkout_couriers')
        );

        return response()->json($result);
    }

    /* SCREEN 6 (POST) */
    public function kurirStore(Request $r, BiteshipService $biteship)
    {
        $data = $r->validate([
            'courier_code' => 'required|string',
            'service_code' => 'required|string',
        ]);

        $s = $this->state($r);
        $rates = $biteship->getRates(
            $this->originFromAddress($s),
            $this->destinationWarehouse(),
            $this->itemsToBiteship($s),
            config('biteship.checkout_couriers')
        );

        $picked = collect($rates['pricing'] ?? [])->first(fn ($p) => $p['courier_code'] === $data['courier_code']
            && ($p['courier_service_code'] ?? null) === $data['service_code']);

        if (! $picked) {
            return back()->withErrors(['courier_code' => 'Tarif kurir tidak valid atau sudah berubah, silakan pilih ulang.']);
        }

        $this->setState($r, [
            'courier_code' => $picked['courier_code'],
            'courier_service_code' => $picked['courier_service_code'] ?? null,
            'courier_name' => $picked['courier_name'] ?? $picked['courier_code'],
            'courier_cost' => (int) $picked['price'],
        ]);

        return redirect()->route('ruang-titip.checkout');
    }

    /**
     * Item asli yang dipilih pelanggan (ukuran + jumlah), dengan berat dan
     * dimensi per ukuran dari StorageSizes::shipping() — dipakai untuk
     * menghitung ongkir Biteship secara akurat (bukan nilai pukul rata).
     */
    private function itemsToBiteship(array $s): array
    {
        $result = [];
        foreach (($s['items'] ?? []) as $code => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }

            $size = StorageSizes::shipping((string) $code);

            $result[] = [
                'name' => StorageSizes::label((string) $code),
                'value' => 50000,
                'quantity' => $qty,
                'weight' => $size['weight'],
                'length' => $size['length'],
                'width' => $size['width'],
                'height' => $size['height'],
            ];
        }

        return $result ?: [['name' => 'Barang titipan', 'value' => 50000, 'quantity' => 1] + config('item_sizes.fallback')];
    }

    /** Titik asal Biteship: alamat pelanggan, lengkap dengan koordinat kalau sudah ter-geocode. */
    private function originFromAddress(array $s): array
    {
        return array_filter([
            'area_id' => $s['address_area_id'] ?? null,
            'postal_code' => $s['address_postal_code'] ?? null,
            'latitude' => $s['address_lat'] ?? null,
            'longitude' => $s['address_lng'] ?? null,
        ]);
    }

    /** Titik tujuan Biteship: gudang pusat RuTip, lengkap dengan koordinat. */
    private function destinationWarehouse(): array
    {
        $warehouse = $this->distance->warehouseCoords();

        return array_filter([
            'area_id' => config('biteship.warehouse.area_id'),
            'postal_code' => config('biteship.warehouse.postal_code'),
            'latitude' => $warehouse['lat'] ?? null,
            'longitude' => $warehouse['lng'] ?? null,
        ]);
    }

    /* ═══ SCREEN 7 — Checkout (GET) ═══ */
    public function checkout(Request $r)
    {
        $s = $this->state($r);
        if ($redirect = $this->resumeStep($s)) {
            return $redirect;
        }

        $calc    = $this->calc($s);
        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $courier = ($s['logistic'] ?? null) === 'instant' && !empty($s['courier_code'])
            ? (object) ['name' => $s['courier_name'] ?? $s['courier_code'], 'service' => $s['courier_service_code'] ?? '']
            : null;

        return view('dashboard.ruang-titip.checkout', compact('s', 'calc', 'storage', 'courier'));
    }

    /* SCREEN 7 (POST) — simpan pesanan; pembayaran lewat QRIS dengan bukti yang diperiksa admin */
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

        // Buang barang yang tidak ada lagi di daftar harga ruang (harga bisa diubah admin).
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

        $requiresSchedule = ($s['logistic'] ?? null) !== 'self';
        $minHours = (float) config('pickup_slots.min_hours_ahead');
        $maxDays = (int) config('pickup_slots.max_days_ahead');
        $slotMap = collect(config('pickup_slots.slots'))->keyBy('start');

        $validator = \Illuminate\Support\Facades\Validator::make($r->all(), [
            'agree'          => 'accepted',
            'pickup_date'    => [
                $requiresSchedule ? 'required' : 'nullable', 'date', 'after_or_equal:today',
                'before_or_equal:' . now('Asia/Jakarta')->addDays($maxDays)->toDateString(),
            ],
            'pickup_time'    => [$requiresSchedule ? 'required' : 'nullable', \Illuminate\Validation\Rule::in($slotMap->keys())],
        ], [
            'agree.accepted' => 'Kamu harus menyetujui Syarat & Ketentuan.',
            'pickup_date.required' => 'Pilih tanggal penjemputan.',
            'pickup_time.required' => 'Pilih jam penjemputan.',
            'pickup_time.in' => 'Jam penjemputan tidak valid, pilih salah satu slot yang tersedia.',
        ]);

        $validator->after(function ($validator) use ($r, $requiresSchedule, $minHours) {
            if (! $requiresSchedule || $validator->errors()->has('pickup_date') || $validator->errors()->has('pickup_time')) {
                return;
            }

            try {
                $dt = \Carbon\Carbon::parse($r->input('pickup_date') . ' ' . $r->input('pickup_time'), 'Asia/Jakarta');
            } catch (\Throwable) {
                $validator->errors()->add('pickup_time', 'Jam penjemputan tidak valid.');

                return;
            }

            if ($dt->lt(now('Asia/Jakarta')->addMinutes((int) round($minHours * 60)))) {
                $validator->errors()->add('pickup_time', "Slot jam minimal {$minHours} jam dari sekarang, pilih slot lain.");
            }
        });

        $validator->validate();

        $pickupTimeEnd = $requiresSchedule ? ($slotMap[$r->input('pickup_time')]['end'] ?? null) : null;

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
            'pickup_time_end' => $pickupTimeEnd,
            'logistic'       => $s['logistic'],
            'address'        => $s['logistic'] === 'self' ? null : ($s['address'] ?? null),
            'note'           => $s['logistic'] === 'self' ? null : ($s['note'] ?? null),
            'courier_code'   => $s['courier_code'] ?? null,
            'courier_service_code' => $s['courier_service_code'] ?? null,
            'courier_company' => $s['courier_code'] ?? null,
            'address_area_id' => $s['logistic'] === 'self' ? null : ($s['address_area_id'] ?? null),
            'address_postal_code' => $s['logistic'] === 'self' ? null : ($s['address_postal_code'] ?? null),
            'packing'        => $s['logistic'] === 'rutip' ? 'buy' : null,
            'payment_method' => 'qris',
            'item_subtotal'  => $calc['itemSubtotal'],
            'courier_cost'   => $calc['courierCost'],
            'packing_cost'   => $calc['packingCost'],
            'platform_fee'   => $calc['platform_fee'],
            'total'          => $calc['total'],
            'status'         => 'menunggu_pembayaran',
            'payment_status' => 'UNPAID',
        ]);

        $r->session()->forget('titip');
        return redirect()->route('ruang-titip.success', $order);
    }

    public function success(TitipanOrder $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        return view('dashboard.ruang-titip.success', compact('order'));
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
        if (empty($s['logistic'])) {
            return redirect()->route('ruang-titip.logistik');
        }
        if ($s['logistic'] !== 'self' && empty($s['address'])) {
            return redirect()->route('ruang-titip.alamat');
        }
        if ($s['logistic'] === 'instant' && empty($s['courier_service_code'])) {
            return redirect()->route('ruang-titip.kurir');
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
     * Ukuran yang diterima ruang beserta spesifikasi dan harga terkini per hari
     * (kode, label, price, unit, side, kg, dims, box, vol, koper, fits).
     */
    private function itemSizesFromStorage(?StorageRoom $storage)
    {
        if (! $storage) {
            return collect();
        }

        return collect($storage->size_rows)->map(fn (array $row) => (object) $row)->values();
    }
}
