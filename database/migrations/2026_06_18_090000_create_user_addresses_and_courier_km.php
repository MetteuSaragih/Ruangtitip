<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Revisi Ruang Titip:
| - Tabel user_addresses (multi-alamat, satu alamat utama)
| - Kolom price_per_km & packing_fee untuk kurir "Anjem RuTip"
|--------------------------------------------------------------------------
*/

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->nullable();   // "Kos", "Rumah", dst
            $table->text('address');
            $table->text('note')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Tambahan kolom untuk perhitungan ongkir Anjem RuTip
        Schema::table('couriers', function (Blueprint $table) {
            $table->unsignedInteger('price_per_km')->default(0)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('couriers', function (Blueprint $table) {
            $table->dropColumn('price_per_km');
        });
        Schema::dropIfExists('user_addresses');
    }
};
