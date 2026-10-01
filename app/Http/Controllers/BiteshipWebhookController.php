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
     * Daftarkan URL ini di dashboard Biteship: Settings → Webhook URL,
     * contoh: https://domainmu.com/biteship/webhook
     *
     * Route ini bebas dari middleware 'auth' dan dikecualikan dari CSRF.
     */
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Biteship webhook diterima', $payload);

        $biteshipOrderId = $payload['order_id'] ?? null;
        $status = $payload['status'] ?? null;
        $trackingId = $payload['courier_tracking_id'] ?? $payload['waybill_id'] ?? null;

        if (! $biteshipOrderId) {
            return response()->json(['message' => 'OK']);
        }

        foreach ([PackingOrder::class, Order::class, TitipanOrder::class] as $model) {
            $order = $model::where('biteship_order_id', $biteshipOrderId)->first();
            if ($order) {
                $update = [];
                if ($trackingId) {
                    $update['biteship_tracking_id'] = $trackingId;
                }
                if ($status) {
                    $update['biteship_status'] = $status;
                    if (in_array($status, self::FAILED_STATUSES, true)) {
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
        }

        return response()->json(['message' => 'OK']);
    }
}
