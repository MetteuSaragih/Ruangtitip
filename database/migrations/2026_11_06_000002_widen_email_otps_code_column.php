<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kode OTP kini disimpan sebagai HMAC-SHA256 (64 karakter), bukan 6 angka polos.
     * Migrasi pembuat tabel sudah dijalankan di produksi, jadi kolomnya harus dilebarkan di sini.
     */
    public function up(): void
    {
        if (Schema::hasTable('email_otps') && Schema::hasColumn('email_otps', 'code')) {
            Schema::table('email_otps', function (Blueprint $table) {
                $table->string('code', 64)->change();
            });
        }
    }

    public function down(): void
    {
        // Sengaja kosong: mempersempit kolom akan memotong hash yang sudah tersimpan.
    }
};
