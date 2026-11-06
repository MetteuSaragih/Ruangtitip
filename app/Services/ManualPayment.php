<?php

namespace App\Services;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\PackingOrder;
use App\Models\PrelovedItem;
use App\Models\TitipanOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * Pembayaran manual lewat QRIS.
 *
 * Alur status (kolom payment_status):
 *   UNPAID    belum ada bukti        -> pelanggan membayar lalu mengunggah bukti
 *   REVIEW    bukti masuk            -> admin memeriksa
 *   PAID      admin konfirmasi       -> pesanan lanjut ke tahap berikutnya
 *   REJECTED  admin menolak (alasan) -> pelanggan mengunggah ulang, kembali ke REVIEW
 *
 * Semua perubahan status lewat kelas ini, di dalam transaksi dengan row lock,
 * jadi klik ganda atau dua admin sekaligus tidak menggandakan efek.
 */
class ManualPayment
{
    public const UNPAID = 'UNPAID';
    public const REVIEW = 'REVIEW';
    public const PAID = 'PAID';
    public const REJECTED = 'REJECTED';

    /** Jenis pesanan yang bisa dibayar, beserta model-nya. */
    public const TYPES = [
        'titip' => TitipanOrder::class,
        'preloved' => Order::class,
        'packing' => PackingOrder::class,
    ];

    /* ───────────── Status ───────────── */

    /** Kelompok status: unpaid | review | paid | rejected | failed. */
    public static function state(?string $raw): string
    {
        return match (strtoupper(trim((string) $raw))) {
            self::REVIEW => 'review',
            self::PAID, 'SUCCESS', 'SETTLEMENT', 'SETTLED', 'CAPTURE' => 'paid',
            self::REJECTED => 'rejected',
            'FAILED', 'EXPIRED', 'CANCEL', 'CANCELLED', 'DENY', 'REFUND' => 'failed',
            default => 'unpaid', // UNPAID, pending, kosong
        };
    }

    public static function label(?string $raw): string
    {
        return match (strtoupper(trim((string) $raw))) {
            self::REVIEW => 'Sedang diperiksa',
            self::PAID, 'SUCCESS', 'SETTLEMENT', 'SETTLED', 'CAPTURE' => 'Pembayaran berhasil',
            self::REJECTED => 'Pembayaran gagal',
            'EXPIRED' => 'Kedaluwarsa',
            'CANCEL', 'CANCELLED' => 'Dibatalkan',
            'REFUND' => 'Dikembalikan',
            'FAILED', 'DENY' => 'Gagal',
            default => 'Menunggu pembayaran',
        };
    }

    /** Nada badge: ok | warn | err | info. */
    public static function tone(?string $raw): string
    {
        return match (self::state($raw)) {
            'paid' => 'ok',
            'review' => 'info',
            'rejected', 'failed' => 'err',
            default => 'warn',
        };
    }

    public static function typeOf(Model $order): string
    {
        return match (true) {
            $order instanceof TitipanOrder => 'titip',
            $order instanceof Order => 'preloved',
            $order instanceof PackingOrder => 'packing',
            default => throw new RuntimeException('Jenis pesanan tidak dikenal.'),
        };
    }

    /** Kalimat status pembayaran untuk kartu pesanan pelanggan. */
    public static function userText(Model $order): string
    {
        $total = 'Rp' . number_format((int) $order->total, 0, ',', '.');

        return match (self::state($order->payment_status)) {
            'paid' => "Lunas, total {$total}.",
            'review' => "Bukti pembayaran {$total} sedang diperiksa admin.",
            'rejected' => "Pembayaran {$total} gagal. Foto ulang dan unggah bukti pembayaranmu.",
            'failed' => "Pembayaran {$total} gagal atau kedaluwarsa. Hubungi admin bila sudah membayar.",
            default => "Tagihan {$total} belum dibayar. Bayar lewat QRIS lalu unggah bukti pembayaran.",
        };
    }

    /** Teks tombol tindakan pelanggan di kartu pesanan, atau null bila tidak ada. */
    public static function userCta(Model $order): ?string
    {
        return match (self::state($order->payment_status)) {
            'unpaid' => 'Bayar',
            'rejected' => 'Unggah ulang bukti',
            default => null,
        };
    }

    /** Batasi query ke pesanan yang menunggu tindakan pelanggan: belum ada bukti atau bukti ditolak. */
    public static function needsAction($query)
    {
        return $query->where(fn ($q) => $q->whereNull('payment_status')->orWhereIn('payment_status', [self::UNPAID, 'pending', self::REJECTED]));
    }

    /** Pelanggan boleh mengunggah (ulang) bukti hanya saat belum ada bukti atau bukti ditolak. */
    public static function canUpload(Model $order): bool
    {
        return in_array(self::state($order->payment_status), ['unpaid', 'rejected'], true);
    }

    /** Tampilan nomor pesanan seperti yang dilihat pelanggan. */
    public static function codeOf(Model $order): string
    {
        return match (true) {
            $order instanceof TitipanOrder => $order->code(),
            $order instanceof Order => (string) $order->order_number,
            $order instanceof PackingOrder => (string) $order->order_code,
            default => '#' . $order->getKey(),
        };
    }

    /** Halaman status pembayaran milik pelanggan. */
    public static function pageUrl(Model $order): string
    {
        return match (true) {
            $order instanceof TitipanOrder => route('ruang-titip.success', $order),
            $order instanceof Order => route('checkout.success', $order->order_number),
            default => route('packing.success', $order->order_code),
        };
    }

    /* ───────────── QRIS ───────────── */

    /** URL gambar QRIS asli (qris.png/jpg/jpeg/webp), atau null bila filenya tidak ada. */
    public static function qrisUrl(): ?string
    {
        $cfg = config('payment.qris');
        foreach ($cfg['extensions'] as $ext) {
            $rel = $cfg['directory'] . '/' . $cfg['basename'] . '.' . $ext;
            if (is_file(public_path($rel))) {
                return asset($rel) . '?v=' . filemtime(public_path($rel));
            }
        }

        return null;
    }

    public static function merchant(): string
    {
        return (string) config('payment.qris.merchant');
    }

    /* ───────────── Bukti pembayaran ───────────── */

    public static function proofExists(Model $order): bool
    {
        $path = (string) $order->payment_proof;

        return $path !== '' && Storage::disk(config('payment.proof.disk'))->exists($path);
    }

    /** Simpan bukti dan ubah status ke REVIEW. Melempar RuntimeException bila status tidak mengizinkan. */
    public static function submitProof(Model $order, UploadedFile $file): Model
    {
        $disk = Storage::disk(config('payment.proof.disk'));
        $dir = config('payment.proof.directory') . '/' . self::typeOf($order);
        $newPath = $disk->putFile($dir, $file);
        if (! $newPath) {
            throw new RuntimeException('Gagal menyimpan bukti pembayaran.');
        }

        $oldPath = null;
        try {
            DB::transaction(function () use ($order, $newPath, &$oldPath) {
                $locked = $order::query()->whereKey($order->getKey())->lockForUpdate()->firstOrFail();
                if (! self::canUpload($locked)) {
                    throw new RuntimeException('Bukti pembayaran untuk pesanan ini tidak bisa diunggah lagi.');
                }
                $oldPath = $locked->payment_proof;
                $locked->forceFill([
                    'payment_status' => self::REVIEW,
                    'payment_proof' => $newPath,
                    'payment_proof_at' => now(),
                    'payment_reject_reason' => null,
                    'payment_attempts' => ((int) $locked->payment_attempts) + 1,
                ])->save();
                $order->setRawAttributes($locked->getAttributes(), true);
            });
        } catch (\Throwable $e) {
            $disk->delete($newPath);
            throw $e;
        }

        if ($oldPath && $oldPath !== $newPath) {
            $disk->delete($oldPath);
        }

        return $order;
    }

    /* ───────────── Keputusan admin ───────────── */

    /** Admin menyatakan pembayaran masuk. Hanya dari status REVIEW. */
    public static function confirm(Model $order, ?User $admin): Model
    {
        $paid = DB::transaction(function () use ($order, $admin) {
            $locked = $order::query()->whereKey($order->getKey())->lockForUpdate()->firstOrFail();
            if (self::state($locked->payment_status) === 'paid') {
                return null; // sudah lunas, abaikan klik ganda
            }
            if (strtoupper((string) $locked->payment_status) !== self::REVIEW) {
                throw new RuntimeException('Pembayaran ini belum punya bukti yang perlu diperiksa.');
            }

            $locked->payment_status = self::PAID;
            $locked->payment_reject_reason = null;
            $locked->payment_reviewed_at = now();
            $locked->payment_reviewed_by = $admin?->id;
            $locked->paid_at = now();
            self::settle($locked);
            $locked->save();

            return $locked;
        });

        if ($paid) {
            $order->setRawAttributes($paid->getAttributes(), true);
            try {
                event(new OrderPaid($paid));
            } catch (\Throwable $e) {
                // Pemesanan kurir yang gagal tidak boleh membatalkan konfirmasi bayar;
                // command biteship:book-pending mengulangnya.
                report($e);
            }
        }

        return $order;
    }

    /** Admin menolak bukti (belum masuk atau tidak jelas). Hanya dari status REVIEW. */
    public static function reject(Model $order, ?User $admin, string $reason): Model
    {
        $reason = trim($reason);
        if ($reason === '') {
            throw new RuntimeException('Tulis alasan agar pelanggan tahu apa yang perlu diperbaiki.');
        }

        DB::transaction(function () use ($order, $admin, $reason) {
            $locked = $order::query()->whereKey($order->getKey())->lockForUpdate()->firstOrFail();
            if (strtoupper((string) $locked->payment_status) !== self::REVIEW) {
                throw new RuntimeException('Hanya pembayaran yang sedang diperiksa yang bisa ditolak.');
            }

            $locked->forceFill([
                'payment_status' => self::REJECTED,
                'payment_reject_reason' => mb_substr($reason, 0, 500),
                'payment_reviewed_at' => now(),
                'payment_reviewed_by' => $admin?->id,
            ])->save();
            $order->setRawAttributes($locked->getAttributes(), true);
        });

        Log::info('Pembayaran ditolak admin', ['order' => self::typeOf($order) . '#' . $order->getKey()]);

        return $order;
    }

    /**
     * Efek samping saat pesanan lunas: status pesanan naik satu tahap dan barang preloved
     * ditandai terjual. Dipakai juga oleh callback Tripay lama agar perilakunya sama.
     */
    public static function settle(Model $order): void
    {
        if ($order instanceof PackingOrder) {
            if (in_array($order->status, ['menunggu_pembayaran', null, ''], true)) {
                $order->status = 'diproses';
            }
        } elseif ($order instanceof Order) {
            if (in_array($order->status, [Order::STATUS_PENDING, null, ''], true)) {
                $order->status = Order::STATUS_PAID;
            }
            self::markPrelovedItemsSold($order);
        } elseif ($order instanceof TitipanOrder) {
            if (in_array($order->status, ['menunggu_pembayaran', null, ''], true)) {
                $order->status = 'penjadwalan_penjemputan';
            }
        }
    }

    private static function markPrelovedItemsSold(Order $order): void
    {
        $ids = collect($order->items ?? [])
            ->filter(fn ($i) => ($i['type'] ?? 'preloved') === 'preloved')
            ->pluck('id')
            ->filter()
            ->all();

        if (empty($ids)) {
            return;
        }

        // Barang yang sudah Terjual lewat pesanan lain: tandai supaya admin menindaklanjuti.
        if (PrelovedItem::whereIn('id', $ids)->where('status', 'Terjual')->exists()) {
            $order->needs_admin_attention = true;
            $order->biteship_last_error = 'Barang preloved di pesanan ini sudah terjual lewat pesanan lain.';
        }

        PrelovedItem::whereIn('id', $ids)->update(['status' => 'Terjual']);
    }
}
