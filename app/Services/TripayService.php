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
            ->acceptJson();
    }

    /** Daftar channel pembayaran aktif di akun Tripay (VA, QRIS, e-wallet, gerai, dst). */
    public function getPaymentChannels(): array
    {
        $response = $this->client()->get('/merchant/payment-channel');

        if (! $response->successful()) {
            Log::warning('Tripay getPaymentChannels gagal', ['status' => $response->status(), 'body' => $response->body()]);

            return [];
        }

        return $response->json('data', []);
    }

    /**
     * Buat transaksi closed payment di Tripay.
     * $params: method, merchant_ref, amount, customer_name, customer_email, customer_phone,
     *          order_items => [['name','price','quantity'], ...], callback_url, return_url, expired_time
     */
    public function createTransaction(array $params): array
    {
        $signature = hash_hmac(
            'sha256',
            $this->merchantCode . $params['merchant_ref'] . $params['amount'],
            $this->privateKey
        );

        $payload = array_merge($params, [
            'amount' => (int) $params['amount'],
            'signature' => $signature,
        ]);

        $response = $this->client()->post('/transaction/create', $payload);

        if (! $response->successful() || ! ($response->json('success'))) {
            Log::error('Tripay createTransaction gagal', ['status' => $response->status(), 'body' => $response->body(), 'payload' => $payload]);

            throw new \RuntimeException('Gagal membuat transaksi Tripay: ' . $response->body());
        }

        return $response->json('data');
    }

    public function getTransactionDetail(string $reference): array
    {
        $response = $this->client()->get('/transaction/detail', ['reference' => $reference]);

        if (! $response->successful()) {
            throw new \RuntimeException('Gagal mengambil detail transaksi Tripay: ' . $response->body());
        }

        return $response->json('data', []);
    }

    /** Verifikasi signature callback Tripay (header X-Callback-Signature, HMAC-SHA256 atas raw body). */
    public function verifyCallbackSignature(string $rawBody, ?string $signatureHeader): bool
    {
        if (! $signatureHeader) {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $this->privateKey);

        return hash_equals($expected, $signatureHeader);
    }
}
