<?php

namespace App\Models;

use App\Support\StorageSizes;
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

    protected $fillable = [
        'name', 'title', 'location', 'address', 'description', 'long_description',
        'sizes', 'pricing', 'capacity_total', 'capacity_used',
        'facilities', 'rules', 'photo', 'photos', 'active', 'opening_hours',
    ];

    /** Kolom pricing lama tidak dipakai lagi (harga per ukuran sekarang global), tapi wajib terisi. */
    protected $attributes = [
        'pricing' => '[]',
    ];

    protected $casts = [
        'sizes'         => 'array',
        'pricing'       => 'array',
        'facilities'    => 'array',
        'rules'         => 'array',
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
     * Kapasitas terpakai dalam satuan "setara ukuran S" (S=1, M=2, L=3, XL=5, XXL=8), dihitung langsung
     * dari pesanan yang sudah dibayar, jadi selalu sama di halaman admin dan pengguna.
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
                ->sum(function (TitipanOrder $order) {
                    $units = 0;
                    foreach ((array) ($order->items ?? []) as $code => $qty) {
                        $units += StorageSizes::unit((string) $code) * (int) $qty;
                    }

                    return $units;
                });
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
     * Kode ukuran yang diterima ruang ini (urut S, M, L, XL, XXL). Ruang lama tanpa pilihan ukuran
     * memakai ukuran dari harga lamanya, dan bila tidak ada, semua ukuran.
     */
    public function getAcceptedSizesAttribute(): array
    {
        $wanted = [];
        foreach ((array) ($this->sizes ?? []) as $code) {
            if ($normalized = StorageSizes::normalize((string) $code)) {
                $wanted[$normalized] = true;
            }
        }

        if (! $wanted) {
            foreach (['kardus', 'koper'] as $type) {
                foreach ((array) (($this->pricing ?? [])[$type] ?? []) as $key => $row) {
                    $id = is_array($row) ? (string) ($row['id'] ?? $key) : (string) $key;
                    $price = is_array($row) ? (int) ($row['price'] ?? 0) : (int) $row;
                    if ($price > 0 && ($normalized = StorageSizes::normalize($id))) {
                        $wanted[$normalized] = true;
                    }
                }
            }
        }

        $ordered = array_values(array_filter(StorageSizes::codes(), fn ($code) => isset($wanted[$code])));

        return $ordered ?: StorageSizes::codes();
    }

    /** Spesifikasi + harga terkini tiap ukuran yang diterima ruang ini. */
    public function getSizeRowsAttribute(): array
    {
        $all = StorageSizes::all();

        return array_values(array_filter(array_map(fn ($code) => $all[$code] ?? null, $this->accepted_sizes)));
    }

    public function getMinPriceAttribute(): int
    {
        return (int) (collect($this->size_rows)->min('price') ?? 0);
    }

    public function getMaxPriceAttribute(): int
    {
        return (int) (collect($this->size_rows)->max('price') ?? 0);
    }

    public function getPrimaryPhotoAttribute(): ?string
    {
        return collect($this->photos ?? [])->first() ?: $this->photo;
    }

    /** Jenis hari untuk jam operasional: weekday (Senin-Jumat), saturday, sunday. */
    public static function dayTypeOf(\Carbon\CarbonInterface $date): string
    {
        return match ($date->dayOfWeek) {
            \Carbon\CarbonInterface::SUNDAY => 'sunday',
            \Carbon\CarbonInterface::SATURDAY => 'saturday',
            default => 'weekday',
        };
    }

    /**
     * Baca teks jam operasional ("08.00–17.00", "08:00 - 17:00 WIB", ...) menjadi
     * [menit buka, menit tutup], atau null bila tutup/tidak terbaca.
     */
    public static function parseHours(?string $text): ?array
    {
        $text = trim((string) $text);
        if ($text === '' || preg_match('/tutup|libur/iu', $text)) {
            return null;
        }
        if (! preg_match('/(\d{1,2})[.:](\d{2})\s*(?:–|—|-|s\/d|sd|sampai)\s*(\d{1,2})[.:](\d{2})/u', $text, $m)) {
            return null;
        }

        $open = ((int) $m[1]) * 60 + (int) $m[2];
        $close = ((int) $m[3]) * 60 + (int) $m[4];

        return ($open >= 0 && $close > $open && $close <= 24 * 60) ? [$open, $close] : null;
    }

    /**
     * Pilihan jam antar barang pada satu jenis hari: setiap 30 menit dari jam buka sampai
     * 30 menit sebelum tutup, format "HH:MM". Kosong bila gudang tutup di hari itu.
     */
    public function dropoffSlots(string $dayType): array
    {
        $dayType = in_array($dayType, ['weekday', 'saturday', 'sunday'], true) ? $dayType : 'weekday';
        $text = $this->hours[$dayType];
        $range = self::parseHours($text);

        // Teks aneh yang bukan "tutup": pakai jam bawaan hari itu supaya pelanggan tetap bisa memilih.
        if (! $range && ! preg_match('/tutup|libur/iu', $text)) {
            $range = self::parseHours(self::DEFAULT_HOURS[$dayType]);
        }
        if (! $range) {
            return [];
        }

        $step = max(15, (int) config('pickup_slots.dropoff_step_minutes', 30));
        $slots = [];
        for ($t = $range[0]; $t + $step <= $range[1]; $t += $step) {
            $slots[] = sprintf('%02d:%02d', intdiv($t, 60), $t % 60);
        }

        return $slots;
    }

    /** Pilihan jam antar untuk ketiga jenis hari: ['weekday' => [...], 'saturday' => [...], 'sunday' => [...]]. */
    public function dropoffSlotsByDay(): array
    {
        return [
            'weekday' => $this->dropoffSlots('weekday'),
            'saturday' => $this->dropoffSlots('saturday'),
            'sunday' => $this->dropoffSlots('sunday'),
        ];
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
