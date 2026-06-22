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
        Schema::table('orders', function (Blueprint $table) {
            $table->date('pickup_date')->nullable()->after('courier_service_code');
            $table->string('pickup_time', 5)->nullable()->after('pickup_date'); // format HH:mm
        });

        Schema::table('packing_orders', function (Blueprint $table) {
            $table->date('pickup_date')->nullable()->after('courier_company');
            $table->string('pickup_time', 5)->nullable()->after('pickup_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_date', 'pickup_time']);
        });

        Schema::table('packing_orders', function (Blueprint $table) {
            $table->dropColumn(['pickup_date', 'pickup_time']);
        });
    }
};
