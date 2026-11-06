<?php

namespace App\Support;

use App\Models\AppSetting;

/**
 * Katalog ukuran barang Ruang Titip (S, M, L, XL, XXL).
 *
 * Spesifikasi dari config/item_sizes.php. Tarif harian dan tarif 1 bulan per ukuran
 * dari pengaturan admin (app_settings), dengan angka awal config sebagai cadangan.
 * Paket 7 hari, 3, 6, dan 12 bulan dihitung dari dua angka itu (lihat config 'plans').
 */
final class StorageSizes
{
    public const SETTING_KEY = 'size_prices';

    private static ?array $prices = null;

    /* ───────────── Katalog ───────────── */

    /** Semua ukuran: kode => spesifikasi + 'code', 'price' (harian), 'monthly', dan 'tiers'. */
    public static function all(): array
    {
        $out = [];
        foreach ((array) config('item_sizes.sizes', []) as $code => $spec) {
            $out[$code] = $spec + ['code' => $code];
            $out[$code]['price'] = self::price($code);
            $out[$code]['monthly'] = self::monthly($code);
            $out[$code]['tiers'] = self::tiers($code);
        }

        return $out;
    }

    public static function codes(): array
    {
        return array_keys((array) config('item_sizes.sizes', []));
    }

    /** Ubah kode lama (ks, kpc, dimensi_lain, ...) ke kode ukuran baru; null bila tidak dikenal. */
    public static function normalize(?string $code): ?string
    {
        if ($code === null || $code === '') {
            return null;
        }

        $code = trim($code);
        if (in_array($code, self::codes(), true)) {
            return $code;
        }

        $upper = strtoupper($code);
        if (in_array($upper, self::codes(), true)) {
            return $upper;
        }

        return config('item_sizes.legacy.' . strtolower($code));
    }

    /** Spesifikasi satu ukuran (kode lama ikut dikenali), atau null. */
    public static function get(?string $code): ?array
    {
        $normalized = self::normalize($code);

        return $normalized ? (self::all()[$normalized] ?? null) : null;
    }

    public static function label(?string $code): string
    {
        return self::get($code)['label'] ?? ($code ? (string) $code : 'Barang');
    }

    public static function unit(?string $code): int
    {
        return (int) (self::get($code)['unit'] ?? 1);
    }

    /* ───────────── Harga ───────────── */

    /** Tarif harian per barang. */
    public static function price(?string $code): int
    {
        $normalized = self::normalize($code);
        if (! $normalized) {
            return 0;
        }

        $saved = (int) (self::saved($normalized)['daily'] ?? 0);

        return $saved > 0 ? $saved : (int) config("item_sizes.sizes.{$normalized}.price", 0);
    }

    /** Tarif 1 bulan (30 hari) per barang. */
    public static function monthly(?string $code): int
    {
        $normalized = self::normalize($code);
        if (! $normalized) {
            return 0;
        }

        $saved = (int) (self::saved($normalized)['monthly'] ?? 0);

        return $saved > 0 ? $saved : (int) config("item_sizes.sizes.{$normalized}.monthly", 0);
    }

    /**
     * Paket harga satu ukuran, urut dari terpendek:
     * [key, label, days, price, note]. Harian dan 1 bulan adalah input admin, sisanya dihitung.
     */
    public static function tiers(?string $code): array
    {
        $daily = self::price($code);
        $monthly = self::monthly($code);
        $plans = (array) config('item_sizes.plans', []);
        $round = max(1, (int) ($plans['rounding'] ?? 1000));

        $week = (array) ($plans['week'] ?? ['days' => 7, 'paid_days' => 6]);
        $tiers = [
            ['key' => 'daily', 'label' => 'Harian', 'days' => 1, 'price' => $daily, 'note' => 'per hari'],
            ['key' => 'week', 'label' => (int) $week['days'] . ' hari', 'days' => (int) $week['days'], 'price' => $daily * (int) $week['paid_days'], 'note' => 'bayar ' . (int) $week['paid_days'] . ' hari'],
        ];

        foreach ((array) ($plans['months'] ?? []) as $months => $plan) {
            $months = (int) $months;
            $discount = (int) $plan['discount'];
            // Hitung dengan bilangan bulat supaya pembulatan ke atas tidak melenceng.
            $numerator = $monthly * $months * (100 - $discount);
            $price = intdiv($numerator + ($round * 100 - 1), $round * 100) * $round;
            $tiers[] = [
                'key' => 'm' . $months,
                'label' => $months . ' bulan',
                'days' => (int) $plan['days'],
                'price' => $price,
                'note' => $months === 1 ? 'tarif bulanan' : ($discount > 0 ? "hemat {$discount}%" : 'total'),
            ];
        }

        return $tiers;
    }

    /**
     * Biaya termurah untuk menitipkan SATU barang selama $days hari, memakai gabungan paket
     * (boleh melebihi lama titip bila itu lebih murah, mis. 29 hari memakai paket 1 bulan).
     *
     * @return array{total:int, lines:array<int,array{label:string,days:int,price:int,qty:int}>}
     */
    public static function cost(?string $code, int $days): array
    {
        $days = max(1, $days);
        $tiers = array_values(array_filter(self::tiers($code), fn ($t) => $t['price'] > 0 && $t['days'] > 0));
        if (! $tiers) {
            return ['total' => 0, 'lines' => []];
        }
        // Paket terpanjang dulu: bila harga sama, pilih paket yang lebih besar.
        usort($tiers, fn ($a, $b) => $b['days'] <=> $a['days']);

        $best = [0 => 0];
        $pick = [];
        for ($d = 1; $d <= $days; $d++) {
            $best[$d] = PHP_INT_MAX;
            foreach ($tiers as $i => $t) {
                $cost = $t['price'] + $best[max(0, $d - $t['days'])];
                if ($cost < $best[$d]) {
                    $best[$d] = $cost;
                    $pick[$d] = $i;
                }
            }
        }

        $counts = [];
        for ($d = $days; $d > 0; $d = max(0, $d - $tiers[$pick[$d]]['days'])) {
            $counts[$pick[$d]] = ($counts[$pick[$d]] ?? 0) + 1;
        }
        krsort($counts); // tampilkan paket terpendek dulu
        $lines = [];
        foreach ($counts as $i => $qty) {
            $lines[] = ['label' => $tiers[$i]['label'], 'days' => $tiers[$i]['days'], 'price' => $tiers[$i]['price'], 'qty' => $qty];
        }

        return ['total' => (int) $best[$days], 'lines' => $lines];
    }

    /**
     * Hitung biaya semua barang: [kode => jumlah] untuk $days hari.
     *
     * @return array{total:int, per_day:int, items:array<int,array{code:string,label:string,qty:int,each:int,subtotal:int,lines:array}>}
     */
    public static function quote(array $items, int $days): array
    {
        $rows = [];
        $total = 0;
        $perDay = 0;
        foreach ($items as $code => $qty) {
            $qty = (int) $qty;
            $normalized = self::normalize((string) $code);
            if ($qty <= 0 || ! $normalized) {
                continue;
            }
            $one = self::cost($normalized, $days);
            $subtotal = $one['total'] * $qty;
            $total += $subtotal;
            $perDay += self::price($normalized) * $qty;
            $rows[] = [
                'code' => $normalized,
                'label' => self::label($normalized),
                'qty' => $qty,
                'each' => $one['total'],
                'subtotal' => $subtotal,
                'lines' => $one['lines'],
            ];
        }

        return ['total' => $total, 'per_day' => $perDay, 'items' => $rows];
    }

    /* ───────────── Penyimpanan harga admin ───────────── */

    /** Tarif tersimpan satu ukuran: ['daily' => int, 'monthly' => int] (bentuk lama: angka = harian saja). */
    private static function saved(string $code): array
    {
        $all = self::savedPrices();
        $row = $all[$code] ?? null;
        if (is_array($row)) {
            return ['daily' => (int) ($row['daily'] ?? 0), 'monthly' => (int) ($row['monthly'] ?? 0)];
        }

        return ['daily' => (int) $row, 'monthly' => 0];
    }

    /** Tarif yang disimpan admin: kode => ['daily', 'monthly'] (atau angka harian pada data lama). */
    public static function savedPrices(): array
    {
        if (self::$prices === null) {
            self::$prices = (array) AppSetting::read(self::SETTING_KEY, []);
        }

        return self::$prices;
    }

    /**
     * Simpan tarif per ukuran. Bentuk masukan: kode => ['daily' => int, 'monthly' => int].
     * Hanya kode valid dengan tarif positif yang disimpan.
     */
    public static function savePrices(array $prices): void
    {
        $clean = [];
        foreach (self::codes() as $code) {
            $row = $prices[$code] ?? [];
            $daily = (int) (is_array($row) ? ($row['daily'] ?? 0) : $row);
            $monthly = (int) (is_array($row) ? ($row['monthly'] ?? 0) : 0);
            if ($daily > 0 || $monthly > 0) {
                $clean[$code] = ['daily' => max(0, $daily), 'monthly' => max(0, $monthly)];
            }
        }

        AppSetting::write(self::SETTING_KEY, $clean);
        self::$prices = $clean;
    }

    /* ───────────── Pengiriman (cadangan untuk pesanan lama) ───────────── */

    /** Berat (gram) dan dimensi (cm) satu barang ukuran ini, untuk hitung ongkir Biteship. */
    public static function shipping(?string $code): array
    {
        $spec = self::get($code);
        if (! $spec) {
            return config('item_sizes.fallback');
        }

        [$length, $width, $height] = $spec['dims'];

        return ['length' => $length, 'width' => $width, 'height' => $height, 'weight' => (int) $spec['kg'] * 1000];
    }

    /** Hapus cache (dipakai tes dan setelah menyimpan). */
    public static function flush(): void
    {
        self::$prices = null;
    }
}
