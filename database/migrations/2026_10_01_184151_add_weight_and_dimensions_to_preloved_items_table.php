<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preloved_items', function (Blueprint $table) {
            // Berat (gram) dan dimensi (cm), dipakai untuk cek ongkir Biteship.
            $table->unsignedInteger('weight')->default(1000)->after('price');
            $table->unsignedInteger('length')->default(30)->after('weight');
            $table->unsignedInteger('width')->default(20)->after('length');
            $table->unsignedInteger('height')->default(15)->after('width');
        });
    }

    public function down(): void
    {
        Schema::table('preloved_items', function (Blueprint $table) {
            $table->dropColumn(['weight', 'length', 'width', 'height']);
        });
    }
};
