<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TitipanRequest extends Model
{
    public const TYPES = ['perpanjang', 'kirim', 'ambil'];

    public const TYPE_LABELS = [
        'perpanjang' => 'Tambah periode',
        'kirim' => 'Kirim barang',
        'ambil' => 'Ambil di gudang',
    ];

    public const STATUS_LABELS = [
        'menunggu' => 'Menunggu konfirmasi admin',
        'diproses' => 'Sedang diproses',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
    ];

    protected $fillable = ['titipan_order_id', 'user_id', 'type', 'payload', 'status', 'catatan'];

    protected $casts = ['payload' => 'array'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(TitipanOrder::class, 'titipan_order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function typeLabel(): string
    {
        return self::TYPE_LABELS[$this->type] ?? ucfirst($this->type);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['menunggu', 'diproses'], true);
    }
}
