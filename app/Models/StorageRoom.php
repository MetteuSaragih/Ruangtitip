<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageRoom extends Model
{
    /** Status pesanan yang memakai slot di ruang (sudah dibayar dan belum selesai). */
    public const ACTIVE_ORDER_STATUSES = ['penjadwalan_penjemputan', 'dalam_gudang', 'proses_pengembalian'];

    /** Jam operasional bawaan bila admin belum mengisi. */
    public const DEFAULT_HOURS = [
        'weekday' => '08.00–17.00',
        'saturday' => '08.00–12.00',
        'sunday' => 'Tutup',
    ];

    /** Label dan dimensi resmi tiap ukuran (kode sama dengan kolom harga di form admin). */
    public const SIZE_META = [
        'kardus' => [
            'ks' => ['S', '20×15×10 cm'], 'km' => ['M', '30×20×15 cm'],
            'kl' => ['L', '40×30×20 cm'], 'kxl' => ['XL', '50×40×30 cm'],
        ],
        'koper' => [
            'kpc' => ['Cabin', '35×23×15 cm'], 'kpm' => ['Medium', '50×33×20 cm'],
            'kpl' => ['Large', '65×43×25 cm'], 'kpxl' => ['XL', '75×50×30 cm'],
        ],
    ];

    protected $fillable = [
        'name', 'location', 'address', 'description',
        'pricing', 'capacity_total', 'capacity_used',
        'facilities', 'photo', 'photos', 'active', 'opening_hours',
    ];

    protected $casts = [
        'pricing'       => 'array',
        'facilities'    => 'array',
        'photos'        => 'array',
        'opening_hours' => 'array',
        'active'        => 'boolean',
    ];

    private ?int $usedCache = null;

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Slot terpakai dihitung langsung dari pesanan yang sudah dibayar, jadi selalu
     * sama di halaman admin dan halaman pengguna. Satu barang dalam pesanan = satu slot.
     */
    public function getCapacityUsedAttribute($value): int
    {
        if (! $this->exists) {
            return (int) $value;
        }

        if ($this->usedCache !== null) {
            return $this->usedCache;
        }

        try {
            return $this->usedCache = (int) TitipanOrder::where('storage_id', $this->id)
                ->whereIn('status', self::ACTIVE_ORDER_STATUSES)
                ->get(['id', 'items'])
                ->sum(fn (TitipanOrder $order) => $order->totalItems());
        } catch (\Throwable $e) {
            return (int) $value;
        }
    }

    public function getCapacityPctAttribute(): int
    {
        if ($this->capacity_total <= 0) {
            return 0;
        }

        return (int) round(($this->capacity_used / $this->capacity_total) * 100);
    }

    /**
     * Harga per ukuran dalam bentuk baris [id, label, dims, price] untuk kardus, koper, dan dimensi.
     * Menerima dua format simpanan: peta kode => harga (dari form admin) atau baris berlabel.
     */
    public function getPricingRowsAttribute(): array
    {
        $pricing = (array) ($this->pricing ?? []);
        $out = ['kardus' => [], 'koper' => [], 'dimensi' => []];

        foreach (['kardus', 'koper'] as $type) {
            foreach ((array) ($pricing[$type] ?? []) as $key => $row) {
                $id = is_array($row) ? (string) ($row['id'] ?? $key) : (string) $key;
                $meta = self::SIZE_META[$type][$id] ?? null;
                $price = is_array($row) ? (int) ($row['price'] ?? 0) : (int) $row;

                if ($price <= 0) {
                    continue;
                }

                $out[$type][] = [
                    'id' => $id,
                    'label' => (is_array($row) ? ($row['label'] ?? null) : null) ?: ($meta[0] ?? strtoupper($id)),
                    'dims' => (is_array($row) ? ($row['dims'] ?? null) : null) ?: ($meta[1] ?? null),
                    'price' => $price,
                ];
            }
        }

        $other = (int) ($pricing['dimensiLain'] ?? 0);
        if ($other > 0) {
            $out['dimensi'][] = [
                'id' => 'dimensi_lain',
                'label' => 'Dimensi lain',
                'dims' => '30×30×30 sampai 100×100×100 cm',
                'price' => $other,
            ];
        }

        return $out;
    }

    public function getMinPriceAttribute(): int
    {
        return (int) (collect($this->pricing_rows)->flatten(1)->min('price') ?? 0);
    }

    public function getMaxPriceAttribute(): int
    {
        return (int) (collect($this->pricing_rows['koper'])->max('price') ?? 0);
    }

    public function getPrimaryPhotoAttribute(): ?string
    {
        return collect($this->photos ?? [])->first() ?: $this->photo;
    }

    /** Jam operasional lengkap (nilai kosong diganti bawaan). */
    public function getHoursAttribute(): array
    {
        $saved = (array) ($this->opening_hours ?? []);

        return [
            'weekday' => trim((string) ($saved['weekday'] ?? '')) ?: self::DEFAULT_HOURS['weekday'],
            'saturday' => trim((string) ($saved['saturday'] ?? '')) ?: self::DEFAULT_HOURS['saturday'],
            'sunday' => trim((string) ($saved['sunday'] ?? '')) ?: self::DEFAULT_HOURS['sunday'],
        ];
    }
}
