<?php

namespace App\Support;

use App\Models\AppSetting;

/**
 * Katalog ukuran barang Ruang Titip (S, M, L, XL). Spesifikasi dari config/item_sizes.php,
 * harga per hari dari pengaturan admin (app_settings) dengan harga awal sebagai cadangan.
 */
final class StorageSizes
{
    public const SETTING_KEY = 'size_prices';

    private static ?array $prices = null;

    /** Semua ukuran: kode => spesifikasi lengkap + 'code' + 'price' terkini. */
    public static function all(): array
    {
        $out = [];
        foreach ((array) config('item_sizes.sizes', []) as $code => $spec) {
            $out[$code] = $spec + ['code' => $code];
            $out[$code]['price'] = self::price($code);
        }

        return $out;
    }

    public static function codes(): array
    {
        return array_keys((array) config('item_sizes.sizes', []));
    }

    /** Ubah kode lama (ks, kpc, dimensi_lain, ...) ke kode S/M/L/XL; null bila tidak dikenal. */
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

    /** Harga per barang per hari. */
    public static function price(?string $code): int
    {
        $normalized = self::normalize($code);
        if (! $normalized) {
            return 0;
        }

        $saved = self::savedPrices();
        $default = (int) config("item_sizes.sizes.{$normalized}.price", 0);

        return (int) (($saved[$normalized] ?? 0) > 0 ? $saved[$normalized] : $default);
    }

    /** Harga yang disimpan admin: kode => rupiah. */
    public static function savedPrices(): array
    {
        if (self::$prices === null) {
            self::$prices = (array) AppSetting::read(self::SETTING_KEY, []);
        }

        return self::$prices;
    }

    /** Simpan harga per ukuran (hanya kode valid dan harga positif). */
    public static function savePrices(array $prices): void
    {
        $clean = [];
        foreach (self::codes() as $code) {
            $value = (int) ($prices[$code] ?? 0);
            if ($value > 0) {
                $clean[$code] = $value;
            }
        }

        AppSetting::write(self::SETTING_KEY, $clean);
        self::$prices = $clean;
    }

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
