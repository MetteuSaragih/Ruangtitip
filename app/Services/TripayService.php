<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    private string $baseUrl;
    private string $apiKey;
    private string $privateKey;
    private string $merchantCode;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('tripay.base_url'), '/');
        $this->apiKey = (string) config('tripay.api_key');
        $this->privateKey = (string) config('tripay.private_key');
        $this->merchantCode = (string) config('tripay.merchant_code');
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '' && $this->privateKey !== '' && $this->merchantCode !== '';
    }

    private function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withToken($this->apiKey)
            ->connectTimeout(5)
            ->timeout(20)
            ->acceptJson();
    }

    /** Daftar channel pembayaran aktif di akun Tripay (VA, QRIS, e-wallet, gerai, dst). */
    public function getPaymentChannels(): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        try {
            $response = $this->client()->get('/merchant/payment-channel');
        } catch (\Throwable $e) {
            Log::warning('Tripay getPaymentChannels error', ['error' => $e->getMessage()]);

            return [];
        }

        if (! $response->successful()) {
            Log::warning('Tripay getPaymentChannels gagal', ['status' => $response->status(), 'body' => $response->body()]);

            return [];
        }

        $data = $response->json('data');

        return is_array($data) ? $data : [];
    }

    /**
     * Buat transaksi closed payment di Tripay.
     * $params: method, merchant_ref, amount, customer_name, customer_email, customer_phone,
     *          order_items => [['name','price','quantity'], ...], callback_url, return_url, expired_time
     */
    public function createTransaction(array $params): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('Pembayaran belum dikonfigurasi (kunci Tripay kosong).');
        }

        $amount = (int) $params['amount'];
        $signature = hash_hmac(
            'sha256',
            $this->merchantCode . $params['merchant_ref'] . $amount,
            $this->privateKey
        );

        $payload = array_merge($params, [
            'amount' => $amount,
            'signature' => $signature,
        ]);

        try {
            $response = $this->client()->post('/transaction/create', $payload);
        } catch (\Throwable $e) {
            Log::error('Tripay createTransaction error koneksi', ['error' => $e->getMessage()]);

            throw new \RuntimeException('Layanan pembayaran tidak dapat dihubungi. Coba lagi sebentar.', 0, $e);
        }

        $data = $response->json('data');

        if (! $response->successful() || ! $response->json('success') || ! is_array($data)) {
            // Detail lengkap hanya di log; pesan pengecualian dipakai controller untuk ditampilkan ke pengguna.
            Log::error('Tripay createTransaction gagal', [
                'status' => $response->status(),
                'body' => $response->body(),
                'merchant_ref' => $params['merchant_ref'] ?? null,
            ]);

            $reason = (string) ($response->json('message') ?? '');

            throw new \RuntimeException('Gagal membuat transaksi Tripay' . ($reason !== '' ? ': ' . $reason : '.'));
        }

        return $data;
    }

    public function getTransactionDetail(string $reference): array
    {
        try {
            $response = $this->client()->get('/transaction/detail', ['reference' => $reference]);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Layanan pembayaran tidak dapat dihubungi.', 0, $e);
        }

        if (! $response->successful()) {
            Log::warning('Tripay getTransactionDetail gagal', ['status' => $response->status(), 'body' => $response->body()]);

            throw new \RuntimeException('Gagal mengambil detail transaksi Tripay.');
        }

        $data = $response->json('data');

        return is_array($data) ? $data : [];
    }

    /** Verifikasi signature callback Tripay (header X-Callback-Signature, HMAC-SHA256 atas raw body). */
    public function verifyCallbackSignature(string $rawBody, ?string $signatureHeader): bool
    {
        // Kunci kosong berarti HMAC bisa dipalsukan siapa saja: tolak.
        if ($this->privateKey === '' || ! $signatureHeader) {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $this->privateKey);

        return hash_equals($expected, $signatureHeader);
    }
}
