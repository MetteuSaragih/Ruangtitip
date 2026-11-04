<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrelovedSubmission extends Model
{
    protected $fillable = [
        'code', 'user_id', 'source', 'titip_ref', 'allow_open', 'name', 'category', 'condition',
        'description', 'completeness', 'flaws', 'photos', 'bank_name', 'bank_number', 'bank_holder',
        'status', 'reason', 'offer', 'schedule_date', 'schedule_slot', 'log',
    ];

    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected $casts = [
        'photos' => 'array',
        'log' => 'array',
        'allow_open' => 'boolean',
        'offer' => 'integer',
        'schedule_date' => 'date',
    ];

    /** Label dan warna badge per status, sama dengan prototipe (SS). */
    public const STATUS = [
        'seleksi' => ['Menunggu Seleksi', 'info'],
        'tolak' => ['Pengajuan Ditolak', 'err'],
        'jadwal' => ['Pilih Jadwal Antar', 'warn'],
        'antar' => ['Menunggu Pengantaran', 'info'],
        'periksa' => ['Sedang Diperiksa', 'info'],
        'tidak' => ['Tidak Dibeli', 'err'],
        'tawar' => ['Penawaran Harga', 'warn'],
        'titip' => ['Selesaikan Tagihan Titipan', 'warn'],
        'sepakat' => ['Menunggu Pembayaran ke Kamu', 'info'],
        'dibeli' => ['Dibeli Ruang Titip', 'ok'],
        'batal' => ['Pembelian Batal', 'mute'],
    ];

    public const CONDITIONS = [
        'baru' => 'Seperti baru',
        'baik' => 'Baik',
        'cukup' => 'Cukup, ada bekas pakai',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function needsAction(): bool
    {
        return in_array($this->status, ['jadwal', 'tawar', 'titip'], true);
    }

    public function addLog(string $actor, string $text): void
    {
        $log = $this->log ?? [];
        $log[] = ['t' => now()->toIso8601String(), 'a' => $actor, 'x' => $text];
        $this->log = $log;
    }
}
