<?php

namespace App\Http\Controllers;

use App\Models\ItemSize;
use App\Models\StorageRoom;
use App\Models\TitipanOrder;
use App\Models\UserAddress;
use App\Services\BiteshipService;
use App\Services\DistanceService;
use App\Services\TripayService;
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

        // durasi bulan dari tanggal
        $months = 1;
        if (!empty($s['date_start']) && !empty($s['date_end'])) {
            $days = (strtotime($s['date_end']) - strtotime($s['date_start'])) / 86400;
            $months = max(1, (int) ceil($days / 30));
        }

        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $sizes = $this->itemSizesFromStorage($storage)->keyBy('code');
        $itemSubtotal = 0;
        $totalItems = 0;
        foreach ($items as $code => $qty) {
            $qty = (int) $qty;
            if (isset($sizes[$code])) {
                $totalItems += $qty;
                $itemSubtotal += $qty * $sizes[$code]->price * $months;
            }
        }

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
            'months'        => $months,
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
        $warehouse = $this->warehouseCoords();

        if (! $lat || ! $lng || ! $warehouse) {
            return self::DEFAULT_KM;
        }

        $km = $this->distance->distanceKm((float) $lat, (float) $lng, $warehouse['lat'], $warehouse['lng']);

        return max(1, (int) ceil($km));
    }

    /** Koordinat gudang RuTip: pakai config manual kalau ada, kalau tidak geocode dari alamatnya. */
    private function warehouseCoords(): ?array
    {
        $lat = config('biteship.warehouse.latitude');
        $lng = config('biteship.warehouse.longitude');
        if ($lat && $lng) {
            return ['lat' => (float) $lat, 'lng' => (float) $lng];
        }

        $address = config('biteship.warehouse.address');
        if (! $address) {
            return null;
        }

        $geo = $this->distance->geocode($address);

        return $geo ? ['lat' => $geo['lat'], 'lng' => $geo['lng']] : null;
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
        $this->setState($r, ['storage_id' => $storage->id]);
        return view('dashboard.ruang-titip.detail', compact('storage'));
    }

    /* ═══ SCREEN 3 — GABUNGAN: item + rentang tanggal (GET) ═══ */
    public function detailForm(Request $r)
    {
        if (empty($this->state($r)['storage_id'])) {
            return redirect()->route('ruang-titip.index');
        }
        $storage = StorageRoom::find($this->state($r)['storage_id']);
        $sizes = $this->itemSizesFromStorage($storage);
        $kardus = $sizes->where('type', 'kardus')->values();
        $koper = $sizes->where('type', 'koper')->values();
        $dimensi = $sizes->where('type', 'dimensi')->values();
        $s = $this->state($r);
        $selectedItems = $s['items'] ?? [];
        $calc = $this->calc($s);
        return view('dashboard.ruang-titip.detail-item', compact('kardus', 'koper', 'dimensi', 's', 'selectedItems', 'storage', 'calc'));
    }

    /* SCREEN 3 (POST) — simpan item + tanggal sekaligus */
    public function detailStore(Request $r)
    {
        $data = $r->validate([
            'items'      => 'required|array',
            'items.*'    => 'nullable|integer|min:0',
            'date_start' => 'required|date',
            'date_end'   => 'required|date|after:date_start',
        ], [
            'date_end.after' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $items = array_map('intval', $data['items']);
        $items = array_filter($items, fn ($q) => $q > 0);
        if (empty($items)) {
            return back()->withErrors(['items' => 'Pilih minimal satu barang.'])->withInput();
        }

        $storage = StorageRoom::find($this->state($r)['storage_id'] ?? null);
        $sizes = $this->itemSizesFromStorage($storage)->keyBy('code');
        $items = array_filter(
            $items,
            fn ($qty, $code) => isset($sizes[$code]),
            ARRAY_FILTER_USE_BOTH
        );
        if (empty($items)) {
            return back()->withErrors(['items' => 'Pilihan barang tidak valid.'])->withInput();
        }

        $types = $sizes->only(array_keys($items))->pluck('type')->unique()->values();
        $itemType = $types->count() === 1 ? $types->first() : 'campuran';

        $this->setState($r, [
            'item_type'  => $itemType,
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
        $this->setState($r, ['logistic' => $logistic, 'courier_code' => null]);

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
            ['area_id' => $s['address_area_id'], 'postal_code' => $s['address_postal_code'] ?? null],
            ['area_id' => config('biteship.warehouse.area_id'), 'postal_code' => config('biteship.warehouse.postal_code')],
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
            ['area_id' => $s['address_area_id'] ?? null, 'postal_code' => $s['address_postal_code'] ?? null],
            ['area_id' => config('biteship.warehouse.area_id'), 'postal_code' => config('biteship.warehouse.postal_code')],
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

    /** Estimasi berat barang titipan untuk kebutuhan kalkulasi ongkir Biteship. */
    private function itemsToBiteship(array $s): array
    {
        $weights = ['kardus' => 3000, 'koper' => 5000, 'dimensi' => 6000];
        $items = $s['items'] ?? [];
        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $sizes = $this->itemSizesFromStorage($storage)->keyBy('code');

        $result = [];
        foreach ($items as $code => $qty) {
            $qty = (int) $qty;
            if ($qty <= 0) {
                continue;
            }
            $type = $sizes[$code]->type ?? 'kardus';
            $result[] = [
                'name' => $sizes[$code]->label ?? 'Barang titipan',
                'value' => 50000,
                'quantity' => $qty,
                'weight' => $weights[$type] ?? 3000,
            ];
        }

        return $result ?: [['name' => 'Barang titipan', 'value' => 50000, 'quantity' => 1, 'weight' => 3000]];
    }

    /* ═══ SCREEN 7 — Checkout (GET) ═══ */
    public function checkout(Request $r)
    {
        $s = $this->state($r);
        if (empty($s['logistic'])) return redirect()->route('ruang-titip.logistik');

        $calc    = $this->calc($s);
        $storage = StorageRoom::find($s['storage_id'] ?? null);
        $courier = ($s['logistic'] ?? null) === 'instant' && !empty($s['courier_code'])
            ? (object) ['name' => $s['courier_name'] ?? $s['courier_code'], 'service' => $s['courier_service_code'] ?? '']
            : null;

        return view('dashboard.ruang-titip.checkout', compact('s', 'calc', 'storage', 'courier'));
    }

    /* SCREEN 7 (POST) — simpan pesanan & buat transaksi Tripay */
    public function place(Request $r, TripayService $tripay)
    {
        $r->validate([
            'payment_method' => 'required|string',
            'agree'          => 'accepted',
            'pickup_date'    => 'nullable|date|after_or_equal:today',
            'pickup_time'    => 'nullable|string',
        ], [
            'agree.accepted' => 'Kamu harus menyetujui Syarat & Ketentuan.',
        ]);

        $s = $this->state($r);
        $calc = $this->calc($s);

        $order = TitipanOrder::create([
            'user_id'        => Auth::id(),
            'storage_id'     => $s['storage_id'],
            'item_type'      => $s['item_type'],
            'items'          => $s['items'],
            'date_start'     => $s['date_start'] ?? null,
            'date_end'       => $s['date_end'] ?? null,
            'pickup_date'    => $r->input('pickup_date'),
            'pickup_time'    => $r->input('pickup_time'),
            'logistic'       => $s['logistic'],
            'address'        => $s['address'] ?? null,
            'note'           => $s['note'] ?? null,
            'courier_code'   => $s['courier_code'] ?? null,
            'courier_service_code' => $s['courier_service_code'] ?? null,
            'courier_company' => $s['courier_code'] ?? null,
            'address_area_id' => $s['address_area_id'] ?? null,
            'address_postal_code' => $s['address_postal_code'] ?? null,
            'packing'        => $s['logistic'] === 'rutip' ? 'buy' : null,
            'payment_method' => $r->payment_method,
            'item_subtotal'  => $calc['itemSubtotal'],
            'courier_cost'   => $calc['courierCost'],
            'packing_cost'   => $calc['packingCost'],
            'platform_fee'   => $calc['platform_fee'],
            'total'          => $calc['total'],
            'status'         => 'menunggu_pembayaran',
            'payment_status' => 'UNPAID',
        ]);

        try {
            $result = $tripay->createTransaction([
                'method' => $r->payment_method,
                'merchant_ref' => $order->code(),
                'amount' => $calc['total'],
                'customer_name' => Auth::user()->name ?? 'Penitip RuTip',
                'customer_email' => Auth::user()->email ?? 'noreply@rutip.test',
                'customer_phone' => Auth::user()->phone ?? '0800000000',
                'order_items' => [
                    ['name' => 'Biaya Penitipan (' . $calc['totalItems'] . ' item)', 'price' => (int) $calc['itemSubtotal'], 'quantity' => 1],
                    ['name' => 'Biaya Layanan Platform', 'price' => (int) $calc['platform_fee'], 'quantity' => 1],
                    ...($calc['courierCost'] > 0 ? [['name' => 'Biaya Kurir/Anjem', 'price' => (int) $calc['courierCost'], 'quantity' => 1]] : []),
                ],
                'callback_url' => route('tripay.callback'),
                'return_url' => route('ruang-titip.success', $order),
            ]);

            $order->update([
                'tripay_reference' => $result['reference'] ?? null,
                'tripay_checkout_url' => $result['checkout_url'] ?? null,
                'tripay_pay_code' => $result['pay_code'] ?? null,
                'tripay_payment_method' => $r->payment_method,
            ]);
        } catch (\Throwable $e) {
            report($e);
            $order->update(['payment_status' => 'FAILED']);

            return back()->withErrors(['payment_method' => 'Gagal memulai pembayaran: ' . $e->getMessage()]);
        }

        $r->session()->forget('titip');
        return redirect()->route('ruang-titip.success', $order);
    }

    public function success(TitipanOrder $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        return view('dashboard.ruang-titip.success', compact('order'));
    }

    private function itemSizesFromStorage(?StorageRoom $storage)
    {
        if (! $storage) {
            return collect();
        }

        $pricing = $storage->pricing ?? [];
        $rows = [];

        foreach (['kardus', 'koper'] as $type) {
            foreach (($pricing[$type] ?? []) as $id => $row) {
                $code = is_array($row) ? ($row['id'] ?? $id) : $id;
                $rows[] = (object) [
                    'type' => $type,
                    'code' => $code,
                    'label' => is_array($row) ? ($row['label'] ?? strtoupper((string) $code)) : strtoupper((string) $code),
                    'dims' => is_array($row) ? ($row['dims'] ?? '-') : '-',
                    'price' => is_array($row) ? (int) ($row['price'] ?? 0) : (int) $row,
                ];
            }
        }

        if (isset($pricing['dimensiLain'])) {
            $rows[] = (object) [
                'type' => 'dimensi',
                'code' => 'dimensi_lain',
                'label' => 'Dimensi Lain',
                'dims' => '30x30x30 - 100x100x100 cm',
                'price' => (int) $pricing['dimensiLain'],
            ];
        }

        return collect($rows)->filter(fn ($row) => $row->price > 0)->values();
    }
}
