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
        Schema::table('packing_orders', function (Blueprint $table) {
            $table->unsignedInteger('platform_fee')->default(1000)->after('shipping_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packing_orders', function (Blueprint $table) {
            $table->dropColumn('platform_fee');
        });
    }
};
