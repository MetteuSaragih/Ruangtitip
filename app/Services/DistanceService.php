<?php

namespace App\Services;

use App\Models\UserAddress;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistanceService
{
    /**
     * Cari beberapa kandidat alamat (buat dropdown autocomplete) lewat Nominatim.
     * Return: [['label' => display_name, 'lat' => ..., 'lng' => ...], ...]
     */
    public function suggest(string $query, int $limit = 5): array
    {
        $query = trim($query);
        if (mb_strlen($query) < 4) {
            return [];
        }

        $cacheKey = 'geosuggest:' . md5(mb_strtolower($query)) . ':' . $limit;

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($query, $limit) {
            try {
                $response = Http::withHeaders(['User-Agent' => 'RuTip-Platform/1.0'])
                    ->timeout(6)
                    ->get('https://nominatim.openstreetmap.org/search', [
                        'q' => $query,
                        'format' => 'json',
                        'limit' => $limit,
                        'countrycodes' => 'id',
                    ]);

                if (! $response->successful()) {
                    return [];
                }

                return collect($response->json())
                    ->filter(fn ($item) => isset($item['display_name'], $item['lat'], $item['lon']))
                    ->map(fn ($item) => [
                        'label' => $item['display_name'],
                        'lat' => (float) $item['lat'],
                        'lng' => (float) $item['lon'],
                    ])
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::warning('Geocoding suggest gagal', ['query' => $query, 'error' => $e->getMessage()]);

                return [];
            }
        });
    }

    /** Isi lat/lng UserAddress kalau belum ada, dengan geocode dari teks alamat + kecamatan. */
    public function ensureAddressCoords(UserAddress $addr): UserAddress
    {
        if ($addr->latitude && $addr->longitude) {
            return $addr;
        }

        $query = trim($addr->address . ', ' . ($addr->area_name ?? '') . ', Indonesia');
        $geo = $this->geocode($query);
        if ($geo) {
            $addr->update(['latitude' => $geo['lat'], 'longitude' => $geo['lng']]);
        }

        return $addr;
    }

    /**
     * Konversi teks alamat jadi koordinat [lat, lng] pakai Nominatim (OpenStreetMap).
     * Gratis, tanpa API key. Hasil di-cache lama karena teks alamat yang sama
     * akan selalu mengarah ke titik yang sama.
     */
    public function geocode(string $address): ?array
    {
        $address = trim($address);
        if ($address === '') {
            return null;
        }

        $cacheKey = 'geocode:' . md5(mb_strtolower($address));

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($address) {
            // Bagian depan alamat (nama kost/gedung/rumah) sering tidak dikenal OSM,
            // jadi kalau query lengkap gagal, coba lagi dengan segmen depan dibuang
            // satu per satu sampai minimal 2 segmen tersisa (mis. kecamatan, kota).
            $segments = array_values(array_filter(array_map('trim', explode(',', $address))));

            for ($i = 0; $i < count($segments) - 1; $i++) {
                $query = implode(', ', array_slice($segments, $i));
                $point = $this->requestGeocode($query);
                if ($point) {
                    return $point;
                }
            }

            return null;
        });
    }

    private function requestGeocode(string $query): ?array
    {
        try {
            $response = Http::withHeaders(['User-Agent' => 'RuTip-Platform/1.0'])
                ->timeout(6)
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                    'countrycodes' => 'id',
                ]);

            if (! $response->successful()) {
                return null;
            }

            $first = $response->json(0);
            if (! $first || ! isset($first['lat'], $first['lon'])) {
                return null;
            }

            return [
                'lat' => (float) $first['lat'],
                'lng' => (float) $first['lon'],
            ];
        } catch (\Throwable $e) {
            Log::warning('Geocoding alamat gagal', ['query' => $query, 'error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Koordinat gudang/toko RuTip: pakai config manual kalau ada, kalau tidak
     * geocode dari alamatnya (di-cache). Dipakai bersama oleh Ruang Titip,
     * Toko Packing, dan Toko Preloved sebagai titik asal/tujuan Biteship.
     */
    public function warehouseCoords(): ?array
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

        $geo = $this->geocode($address);

        return $geo ? ['lat' => $geo['lat'], 'lng' => $geo['lng']] : null;
    }

    /**
     * Jarak antar dua koordinat dalam km (formula haversine, garis lurus),
     * dikali 1.3 sebagai faktor koreksi supaya lebih mendekati jarak tempuh jalan raya.
     */
    public function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusKm = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c * 1.3;
    }
}
