<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ukuran XXL baru. Ruang yang sebelumnya menerima semua ukuran (S, M, L, XL)
     * otomatis ikut menerima XXL; ruang yang sengaja membatasi ukuran tidak diubah.
     * Admin tetap bisa mengatur ulang lewat Ruang & kapasitas.
     */
    public function up(): void
    {
        if (! Schema::hasTable('storage_rooms') || ! Schema::hasColumn('storage_rooms', 'sizes')) {
            return;
        }

        foreach (DB::table('storage_rooms')->get(['id', 'sizes']) as $room) {
            $sizes = json_decode((string) $room->sizes, true);
            if (! is_array($sizes) || in_array('XXL', $sizes, true)) {
                continue;
            }
            if (! array_diff(['S', 'M', 'L', 'XL'], $sizes)) {
                $sizes[] = 'XXL';
                DB::table('storage_rooms')->where('id', $room->id)->update(['sizes' => json_encode(array_values($sizes))]);
            }
        }
    }

    public function down(): void
    {
        // Tidak dibalik: menghapus XXL bisa memotong pilihan ruang yang sudah diatur admin.
    }
};
