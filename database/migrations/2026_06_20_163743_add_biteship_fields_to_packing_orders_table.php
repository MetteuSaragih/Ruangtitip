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
            $table->string('address_area_id')->nullable()->after('address');
            $table->string('address_postal_code', 10)->nullable()->after('address_area_id');
            $table->string('courier_service_code')->nullable()->after('courier');
            $table->string('courier_company')->nullable()->after('courier_service_code');
            $table->string('biteship_order_id')->nullable()->after('courier_company');
            $table->string('biteship_tracking_id')->nullable()->after('biteship_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packing_orders', function (Blueprint $table) {
            $table->dropColumn([
                'address_area_id', 'address_postal_code', 'courier_service_code',
                'courier_company', 'biteship_order_id', 'biteship_tracking_id',
            ]);
        });
    }
};
