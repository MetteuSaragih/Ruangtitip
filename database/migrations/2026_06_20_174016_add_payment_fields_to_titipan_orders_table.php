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
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('tripay_reference')->nullable()->after('payment_status');
            $table->string('tripay_checkout_url')->nullable()->after('tripay_reference');
            $table->string('tripay_pay_code')->nullable()->after('tripay_checkout_url');
            $table->string('tripay_payment_method')->nullable()->after('tripay_pay_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titipan_orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status', 'tripay_reference', 'tripay_checkout_url',
                'tripay_pay_code', 'tripay_payment_method',
            ]);
        });
    }
};
