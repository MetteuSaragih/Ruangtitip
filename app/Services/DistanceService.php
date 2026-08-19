<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DistanceService
{
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
