<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('titipan_requests')) {
            return;
        }

        Schema::create('titipan_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('titipan_order_id')->constrained('titipan_orders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20); // perpanjang | kirim | ambil
            $table->json('payload')->nullable();
            $table->string('status', 20)->default('menunggu'); // menunggu | diproses | selesai | ditolak
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['titipan_order_id', 'type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titipan_requests');
    }
};
