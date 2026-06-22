<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('titipan_orders', function (Blueprint $table) {
            $table->dropForeign('titipan_orders_storage_id_foreign');
        });

        Schema::table('titipan_orders', function (Blueprint $table) {
            $table->foreign('storage_id')->references('id')->on('storage_rooms')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titipan_orders', function (Blueprint $table) {
            $table->dropForeign('titipan_orders_storage_id_foreign');
        });

        Schema::table('titipan_orders', function (Blueprint $table) {
            $table->foreign('storage_id')->references('id')->on('storages');
        });
    }
};
