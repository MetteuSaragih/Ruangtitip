<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jam operasional per ruang, ditampilkan di halaman detail ruang untuk pengguna.
        if (Schema::hasTable('storage_rooms') && ! Schema::hasColumn('storage_rooms', 'opening_hours')) {
            Schema::table('storage_rooms', function (Blueprint $table) {
                $table->json('opening_hours')->nullable();
            });
        }

        // Kode pengajuan jual asal barang katalog (internal, tidak tampil ke pembeli).
        if (Schema::hasTable('preloved_items') && ! Schema::hasColumn('preloved_items', 'source_code')) {
            Schema::table('preloved_items', function (Blueprint $table) {
                $table->string('source_code', 32)->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('storage_rooms', 'opening_hours')) {
            Schema::table('storage_rooms', function (Blueprint $table) {
                $table->dropColumn('opening_hours');
            });
        }

        if (Schema::hasColumn('preloved_items', 'source_code')) {
            Schema::table('preloved_items', function (Blueprint $table) {
                $table->dropColumn('source_code');
            });
        }
    }
};
