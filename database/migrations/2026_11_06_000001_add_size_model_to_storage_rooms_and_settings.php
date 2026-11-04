<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pengaturan aplikasi (mis. harga per ukuran S/M/L/XL, berlaku di semua ruang).
        if (! Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->json('value')->nullable();
                $table->timestamps();
            });
        }

        // Model ruang mengikuti tampilan pengguna: jenis ruang, ukuran yang diterima, deskripsi lengkap, aturan khusus ruang.
        if (Schema::hasTable('storage_rooms')) {
            Schema::table('storage_rooms', function (Blueprint $table) {
                if (! Schema::hasColumn('storage_rooms', 'title')) {
                    $table->string('title')->nullable();
                }
                if (! Schema::hasColumn('storage_rooms', 'sizes')) {
                    $table->json('sizes')->nullable();
                }
                if (! Schema::hasColumn('storage_rooms', 'long_description')) {
                    $table->text('long_description')->nullable();
                }
                if (! Schema::hasColumn('storage_rooms', 'rules')) {
                    $table->json('rules')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');

        if (Schema::hasTable('storage_rooms')) {
            Schema::table('storage_rooms', function (Blueprint $table) {
                foreach (['title', 'sizes', 'long_description', 'rules'] as $column) {
                    if (Schema::hasColumn('storage_rooms', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
