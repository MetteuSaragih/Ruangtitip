<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom tambahan untuk integrasi Midtrans Snap.
     * - payment_status: status pembayaran dari Midtrans
     *   (pending | settlement | capture | deny | cancel | expire | failure)
     * - snap_token: token Snap untuk membuka popup pembayaran
     */
    public function up(): void
    {
        Schema::table('packing_orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->string('snap_token')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('packing_orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'snap_token']);
        });
    }
};
