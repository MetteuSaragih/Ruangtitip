<?php

namespace App\Http\Controllers;

use App\Services\ManualPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Unggah dan lihat bukti pembayaran QRIS. Keputusan admin ada di Admin\TagihanController.
 * Berkas disimpan di disk privat dan hanya dibuka lewat route ini (pemilik atau admin).
 */
class PaymentProofController extends Controller
{
    public function store(Request $request, string $type, int $id)
    {
        $order = $this->ownedOrder($type, $id);

        if (! ManualPayment::canUpload($order)) {
            $msg = ManualPayment::state($order->payment_status) === 'review'
                ? 'Bukti pembayaranmu sedang diperiksa admin. Tunggu kabar dari kami.'
                : 'Pembayaran untuk pesanan ini tidak bisa diubah lagi.';

            return redirect(ManualPayment::pageUrl($order))->with('error', $msg);
        }

        $request->validate([
            'proof' => [
                'required', 'file',
                'mimes:' . implode(',', config('payment.proof.mimes')),
                'max:' . config('payment.proof.max_kb'),
            ],
        ], [
            'proof.required' => 'Pilih foto bukti pembayaran dulu.',
            'proof.file' => 'Berkas tidak terbaca. Coba pilih ulang fotonya.',
            'proof.mimes' => 'Bukti harus berupa foto JPG, PNG, atau WEBP.',
            'proof.max' => 'Ukuran foto maksimal ' . (int) (config('payment.proof.max_kb') / 1024) . ' MB.',
            'proof.uploaded' => 'Foto gagal diunggah. Coba lagi dengan foto yang lebih kecil.',
        ]);

        try {
            ManualPayment::submitProof($order, $request->file('proof'));
        } catch (RuntimeException $e) {
            return redirect(ManualPayment::pageUrl($order))->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            // Detail teknis (mis. error database) hanya masuk log, tidak ditampilkan ke pelanggan.
            report($e);

            return redirect(ManualPayment::pageUrl($order))
                ->with('error', 'Bukti pembayaran belum bisa dikirim. Coba lagi sebentar lagi, atau hubungi admin lewat WhatsApp.');
        }

        return redirect(ManualPayment::pageUrl($order))
            ->with('success', 'Bukti pembayaran terkirim. Admin akan memeriksanya.');
    }

    public function show(string $type, int $id)
    {
        $user = Auth::user();
        $class = $this->modelFor($type);
        $order = $class::query()->findOrFail($id);

        abort_unless($user && ($user->role === 'admin' || (int) $order->user_id === (int) $user->id), 403);

        $disk = Storage::disk(config('payment.proof.disk'));
        $path = (string) $order->payment_proof;
        abort_if($path === '' || ! $disk->exists($path), 404);

        return $disk->response($path, null, [
            'Cache-Control' => 'private, max-age=300',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function modelFor(string $type): string
    {
        abort_unless(array_key_exists($type, ManualPayment::TYPES), 404);

        return ManualPayment::TYPES[$type];
    }

    private function ownedOrder(string $type, int $id)
    {
        $class = $this->modelFor($type);

        return $class::query()
            ->whereKey($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }
}
