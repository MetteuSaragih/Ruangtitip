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
            $table->dropColumn(['midtrans_token', 'midtrans_order_id']);
            $table->string('tripay_reference')->nullable()->after('payment_status');
            $table->string('tripay_checkout_url')->nullable()->after('tripay_reference');
            $table->string('tripay_pay_code')->nullable()->after('tripay_checkout_url');
            $table->string('tripay_payment_method')->nullable()->after('tripay_pay_code');
        });

        Schema::table('packing_orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token']);
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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('midtrans_token')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->dropColumn(['tripay_reference', 'tripay_checkout_url', 'tripay_pay_code', 'tripay_payment_method']);
        });

        Schema::table('packing_orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable();
            $table->dropColumn(['tripay_reference', 'tripay_checkout_url', 'tripay_pay_code', 'tripay_payment_method']);
        });
    }
};
