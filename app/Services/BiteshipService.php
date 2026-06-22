<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('biteship.base_url'), '/');
        $this->apiKey = (string) config('biteship.api_key');
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    private function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders(['Authorization' => $this->apiKey])
            ->acceptJson();
    }

    /**
     * Cari area resmi Biteship (untuk autocomplete alamat).
     * Mengembalikan array: [['id' => ..., 'name' => ..., 'postal_code' => ...], ...]
     */
    public function searchAreas(string $input, int $limit = 10): array
    {
        if (mb_strlen(trim($input)) < 3) {
            return [];
        }

        $response = $this->client()->get('/maps/areas', [
            'countries' => 'ID',
            'input' => $input,
        ]);

        if (! $response->successful()) {
            Log::warning('Biteship searchAreas gagal', ['status' => $response->status(), 'body' => $response->body()]);
            return [];
        }

        $areas = $response->json('areas', []);

        return collect($areas)
            ->map(function (array $area) {
                preg_match('/(\d{5})\s*$/', $area['name'] ?? '', $m);

                return [
                    'id' => $area['id'] ?? null,
                    'name' => $area['name'] ?? '',
                    'postal_code' => $m[1] ?? null,
                ];
            })
            ->filter(fn ($area) => $area['id'])
            ->take($limit)
            ->values()
            ->all();
    }

    /**
     * Hitung ongkir. $origin/$destination berupa ['area_id' => ...] atau ['postal_code' => ...].
     * $items: [['name'=>, 'value'=>, 'weight'=>(gram), 'quantity'=>], ...]
     * $courierCodes: array kode kurir, misal ['jne','jnt'] atau ['gojek','grab','lalamove'].
     *
     * Return: ['success' => bool, 'pricing' => [ [courier_code, courier_service_code, courier_name,
     *   courier_service_name, price, duration, ...], ... ], 'message' => string|null]
     */
    public function getRates(array $origin, array $destination, array $items, array $courierCodes): array
    {
        if (empty($courierCodes)) {
            return ['success' => false, 'pricing' => [], 'message' => 'Tidak ada kurir yang dipilih.'];
        }

        $payload = array_merge(
            $this->prefixed('origin', $origin),
            $this->prefixed('destination', $destination),
            [
                'couriers' => implode(',', $courierCodes),
                'items' => array_map(fn ($item) => [
                    'name' => $item['name'] ?? 'Barang',
                    'value' => (int) ($item['value'] ?? 10000),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'weight' => max(1, (int) ($item['weight'] ?? 1000)),
                ], $items),
            ]
        );

        $response = $this->client()->post('/rates/couriers', $payload);

        if (! $response->successful()) {
            Log::warning('Biteship getRates gagal', ['status' => $response->status(), 'body' => $response->body()]);

            return ['success' => false, 'pricing' => [], 'message' => $response->json('error') ?? 'Gagal mengambil tarif kurir.'];
        }

        $body = $response->json();

        return [
            'success' => (bool) ($body['success'] ?? false),
            'pricing' => $body['pricing'] ?? [],
            'message' => $body['message'] ?? null,
        ];
    }

    /**
     * Buat pesanan pengiriman (booking kurir) ke Biteship.
     * $origin/$destination contact: name, phone, address, area_id|postal_code.
     * $courier: ['company' => ..., 'type' => ..., 'service_code' => ... (opsional)]
     * $schedule: ['date' => 'YYYY-MM-DD', 'time' => 'HH:mm'] (opsional). Jika kosong,
     * kurir akan dicari untuk penjemputan sekarang (delivery_type=now).
     */
    public function createOrder(array $origin, array $destination, array $courier, array $items, ?string $referenceId = null, ?array $schedule = null): array
    {
        $isScheduled = ! empty($schedule['date']) && ! empty($schedule['time']);

        $payload = [
            'origin_contact_name' => $origin['name'],
            'origin_contact_phone' => $origin['phone'],
            'origin_address' => $origin['address'],
            'origin_note' => $origin['note'] ?? null,
            'destination_contact_name' => $destination['name'],
            'destination_contact_phone' => $destination['phone'],
            'destination_address' => $destination['address'],
            'destination_note' => $destination['note'] ?? null,
            'courier_company' => $courier['company'],
            'courier_type' => $courier['type'],
            'delivery_type' => $isScheduled ? 'scheduled' : 'now',
            'delivery_date' => $isScheduled ? $schedule['date'] : null,
            'delivery_time' => $isScheduled ? $schedule['time'] : null,
            'order_note' => $referenceId ? "RuTip order {$referenceId}" : null,
            'reference_id' => $referenceId,
            'items' => array_map(fn ($item) => [
                'name' => $item['name'] ?? 'Barang',
                'value' => (int) ($item['value'] ?? 10000),
                'quantity' => (int) ($item['quantity'] ?? 1),
                'weight' => max(1, (int) ($item['weight'] ?? 1000)),
            ], $items),
        ];

        $payload = array_merge($payload, $this->prefixed('origin', $origin));
        $payload = array_merge($payload, $this->prefixed('destination', $destination));
        $payload = array_filter($payload, fn ($v) => $v !== null);

        $response = $this->client()->post('/orders', $payload);

        if (! $response->successful()) {
            Log::error('Biteship createOrder gagal', ['status' => $response->status(), 'body' => $response->body(), 'payload' => $payload]);

            throw new \RuntimeException('Gagal membuat pesanan Biteship: ' . $response->body());
        }

        return $response->json();
    }

    public function getOrder(string $biteshipOrderId): array
    {
        $response = $this->client()->get("/orders/{$biteshipOrderId}");

        if (! $response->successful()) {
            throw new \RuntimeException('Gagal mengambil data pesanan Biteship: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Ambil bagian origin_postal_code/origin_area_id dst dari array lokasi.
     *
     * Kode pos diutamakan: area_id hasil pencarian /maps/areas berada di level
     * kecamatan dan satu id dipakai bersama oleh beberapa kode pos, sehingga
     * endpoint /rates/couriers & /orders sering menolaknya ("No courier
     * available"). Kode pos terbukti lebih konsisten dikenali.
     */
    private function prefixed(string $prefix, array $location): array
    {
        $out = [];
        if (! empty($location['postal_code'])) {
            $out["{$prefix}_postal_code"] = (int) $location['postal_code'];
        } elseif (! empty($location['area_id'])) {
            $out["{$prefix}_area_id"] = $location['area_id'];
        }
        if (! empty($location['latitude']) && ! empty($location['longitude'])) {
            $out["{$prefix}_latitude"] = $location['latitude'];
            $out["{$prefix}_longitude"] = $location['longitude'];
        }

        return $out;
    }
}
