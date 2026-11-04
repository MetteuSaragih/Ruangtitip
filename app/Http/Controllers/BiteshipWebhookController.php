<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\TitipanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BiteshipWebhookController extends Controller
{
    /**
     * Status Biteship yang menandakan pengiriman gagal/batal — begitu
     * webhook melaporkan salah satu ini, pesanan ditandai "perlu tindakan
     * admin" supaya tidak diam-diam terbengkalai (mis. driver tidak
     * ditemukan untuk kurir instan).
     */
    private const FAILED_STATUSES = ['cancelled', 'rejected', 'courier_not_found', 'on_hold', 'returned'];

    /**
     * Webhook status pengiriman dari Biteship (event: order.status / order.waybill_id).
     * Daftarkan URL ini di dashboard Biteship: Settings -> Webhook URL,
     * contoh: https://domainmu.com/biteship/webhook
     *
     * Biteship tidak menandatangani payload, jadi permintaan diverifikasi dengan token rahasia
     * bersama (BITESHIP_WEBHOOK_TOKEN). Isi token yang sama sebagai header kustom
     * "X-Webhook-Token" di pengaturan webhook Biteship, atau tambahkan "?token=..." pada URL.
     * Tanpa token terkonfigurasi, permintaan ditolak (kecuali APP_ENV=local).
     *
     * Route ini bebas dari middleware 'auth' dan dikecualikan dari CSRF.
     */
    public function handle(Request $request)
    {
        $expected = (string) config('biteship.webhook_token');

        if ($expected === '') {
            if (! app()->environment('local')) {
                Log::warning('Biteship webhook ditolak: BITESHIP_WEBHOOK_TOKEN belum diisi');

                return response()->json(['message' => 'Forbidden'], 403);
            }
        } else {
            $given = (string) ($request->header('X-Webhook-Token') ?? $request->query('token', ''));

            if (! hash_equals($expected, $given)) {
                Log::warning('Biteship webhook token tidak valid', ['ip' => $request->ip()]);

                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        $payload = $request->all();
        Log::info('Biteship webhook diterima', ['order_id' => $payload['order_id'] ?? null, 'status' => $payload['status'] ?? null]);

        $biteshipOrderId = isset($payload['order_id']) && is_scalar($payload['order_id']) ? (string) $payload['order_id'] : null;
        $status = isset($payload['status']) && is_scalar($payload['status']) ? strtolower((string) $payload['status']) : null;
        $trackingId = $payload['courier_tracking_id'] ?? $payload['waybill_id'] ?? null;
        $trackingId = is_scalar($trackingId) && $trackingId !== '' ? (string) $trackingId : null;

        // Ping uji dari Biteship saat webhook didaftarkan tidak membawa order_id.
        if (! $biteshipOrderId) {
            return response()->json(['message' => 'OK']);
        }

        foreach ([PackingOrder::class, Order::class, TitipanOrder::class] as $model) {
            $order = $model::where('biteship_order_id', $biteshipOrderId)->first();
            if (! $order) {
                continue;
            }

            // Idempoten: hanya tulis kolom yang benar-benar berubah.
            $update = [];
            if ($trackingId && $order->biteship_tracking_id !== $trackingId) {
                $update['biteship_tracking_id'] = $trackingId;
            }
            if ($status) {
                if ($order->biteship_status !== $status) {
                    $update['biteship_status'] = $status;
                }
                if (in_array($status, self::FAILED_STATUSES, true) && ! $order->needs_admin_attention) {
                    $update['needs_admin_attention'] = true;
                    $update['biteship_last_error'] = "Status pengiriman Biteship: {$status}";
                }
            }
            if ($update) {
                $order->update($update);
            }
            Log::info("Biteship webhook: order {$biteshipOrderId} ({$model}) -> status {$status}");
            break;
        }

        return response()->json(['message' => 'OK']);
    }
}
