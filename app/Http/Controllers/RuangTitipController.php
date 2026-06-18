<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\ItemSize;
use App\Models\Storage;
use App\Models\TitipanOrder;
use App\Models\UserAddress;
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
| [DUMMY] Jarak km masih tetap (DEFAULT_KM). Nanti dihitung otomatis
| dari alamat customer ke lokasi gudang.
*/

class RuangTitipController extends Controller
{
    private const PLATFORM_FEE     = 1000;   // biaya layanan penitipan (dok. Th.1)
    private const PACKING_PER_BOX  = 15000;  // jasa packing+anjem per kardus (dok. Th.1)
    private const DEFAULT_KM       = 5;      // [DUMMY] estimasi jarak penjemputan

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

        $sizes = ItemSize::whereIn('code', array_keys($items))->get()->keyBy('code');
        $itemSubtotal = 0;
        $totalItems = 0;
        foreach ($items as $code => $qty) {
            $qty = (int) $qty;
            if (isset($sizes[$code])) {
                $totalItems += $qty;
                $itemSubtotal += $qty * $sizes[$code]->price * $months;
            }
        }

        $logistic    = $s['logistic'] ?? 'self';
        $courierCost = 0;
        $packingCost = 0;
        $kmCost      = 0;
        $km          = self::DEFAULT_KM;

        if ($logistic === 'rutip') {
            // Total Anjem = (harga/km × jarak) + (jasa packing/kardus × jumlah item)
            $courier = Courier::where('code', 'rutip_fleet')->first();
            $perKm   = $courier->price_per_km ?? 10000;
            $kmCost      = $perKm * $km;
            $packingCost = self::PACKING_PER_BOX * $totalItems;
            $courierCost = $kmCost + $packingCost;
        } elseif ($logistic === 'instant' && !empty($s['courier_code'])) {
            $courierCost = (int) (Courier::where('code', $s['courier_code'])->value('price') ?? 0);
        }

        $total = $itemSubtotal + $courierCost + self::PLATFORM_FEE;

        return [
            'months'        => $months,
            'totalItems'    => $totalItems,
            'itemSubtotal'  => $itemSubtotal,
            'courierCost'   => $courierCost,
            'packingCost'   => $packingCost,
            'kmCost'        => $kmCost,
            'km'            => $km,
            'platform_fee'  => self::PLATFORM_FEE,
            'total'         => $total,
        ];
    }

    /* ═══ SCREEN 1 — Daftar gudang ═══ */
    public function index()
    {
        $storages = Storage::where('is_active', true)->get();
        return view('dashboard.ruang-titip.index', compact('storages'));
    }

    /* ═══ SCREEN 2 — Detail gudang ═══ */
    public function show(Request $r, Storage $storage)
    {
        $this->setState($r, ['storage_id' => $storage->id]);
        $storage->load('reviews');
        return view('dashboard.ruang-titip.detail', compact('storage'));
    }

    /* ═══ SCREEN 3 — GABUNGAN: item + rentang tanggal (GET) ═══ */
    public function detailForm(Request $r)
    {
        if (empty($this->state($r)['storage_id'])) {
            return redirect()->route('ruang-titip.index');
        }
        $kardus  = ItemSize::where('type', 'kardus')->get();
        $koper   = ItemSize::where('type', 'koper')->get();
        $dimensi = ItemSize::where('type', 'dimensi')->get();
        $s = $this->state($r);
        $selectedItems = $s['items'] ?? [];
        return view('dashboard.ruang-titip.detail-item', compact('kardus', 'koper', 'dimensi', 's', 'selectedItems'));
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

        $sizes = ItemSize::whereIn('code', array_keys($items))->get()->keyBy('code');
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
        if (empty($this->state($r)['items'])) return redirect()->route('ruang-titip.detail-item');
        return view('dashboard.ruang-titip.logistik');
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
        return view('dashboard.ruang-titip.alamat', compact('addresses', 's'));
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
            ]);
            $addressId = $addr->id;
        } else {
            $data = $r->validate(['address_id' => 'required|exists:user_addresses,id']);
            $addressId = (int) $data['address_id'];
        }

        // pastikan alamat milik user
        $addr = UserAddress::where('user_id', Auth::id())->findOrFail($addressId);
        $this->setState($r, ['address_id' => $addr->id, 'address' => $addr->address, 'note' => $addr->note]);

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
        // [DUMMY] daftar kurir instan dari DB (belum API Biteship)
        $couriers = Courier::where('group', 'instant')->get();
        return view('dashboard.ruang-titip.kurir', compact('couriers', 's'));
    }

    /* SCREEN 6 (POST) */
    public function kurirStore(Request $r)
    {
        $data = $r->validate(['courier_code' => 'required|exists:couriers,code']);
        $this->setState($r, ['courier_code' => $data['courier_code']]);
        return redirect()->route('ruang-titip.checkout');
    }

    /* ═══ SCREEN 7 — Checkout (GET) ═══ */
    public function checkout(Request $r)
    {
        $s = $this->state($r);
        if (empty($s['logistic'])) return redirect()->route('ruang-titip.logistik');

        $calc    = $this->calc($s);
        $storage = Storage::find($s['storage_id'] ?? null);
        $courier = !empty($s['courier_code']) ? Courier::where('code', $s['courier_code'])->first() : null;

        return view('dashboard.ruang-titip.checkout', compact('s', 'calc', 'storage', 'courier'));
    }

    /* SCREEN 7 (POST) — simpan pesanan */
    public function place(Request $r)
    {
        $r->validate([
            'payment_method' => 'required|string',
            'agree'          => 'accepted',
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
            'pickup_time'    => null, // tidak dipakai lagi
            'logistic'       => $s['logistic'],
            'address'        => $s['address'] ?? null,
            'note'           => $s['note'] ?? null,
            'courier_code'   => $s['courier_code'] ?? null,
            'packing'        => $s['logistic'] === 'rutip' ? 'buy' : null,
            'payment_method' => $r->payment_method,
            'item_subtotal'  => $calc['itemSubtotal'],
            'courier_cost'   => $calc['courierCost'],
            'packing_cost'   => $calc['packingCost'],
            'platform_fee'   => $calc['platform_fee'],
            'total'          => $calc['total'],
            'status'         => 'menunggu_pembayaran',
        ]);

        $r->session()->forget('titip');
        return redirect()->route('ruang-titip.success', $order);
    }

    public function success(TitipanOrder $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        return view('dashboard.ruang-titip.success', compact('order'));
    }
}
