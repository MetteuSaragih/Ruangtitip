<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Tabel pesanan yang dibayar lewat QRIS dengan pemeriksaan manual oleh admin. */
    private const TABLES = ['titipan_orders', 'orders', 'packing_orders'];

    public function up(): void
    {
        foreach (self::TABLES as $name) {
            if (! Schema::hasTable($name)) {
                continue;
            }

            Schema::table($name, function (Blueprint $table) use ($name) {
                if (! Schema::hasColumn($name, 'payment_proof')) {
                    $table->string('payment_proof')->nullable();
                }
                if (! Schema::hasColumn($name, 'payment_proof_at')) {
                    $table->timestamp('payment_proof_at')->nullable();
                }
                if (! Schema::hasColumn($name, 'payment_reviewed_at')) {
                    $table->timestamp('payment_reviewed_at')->nullable();
                }
                if (! Schema::hasColumn($name, 'payment_reviewed_by')) {
                    $table->unsignedBigInteger('payment_reviewed_by')->nullable();
                }
                if (! Schema::hasColumn($name, 'payment_reject_reason')) {
                    $table->string('payment_reject_reason', 500)->nullable();
                }
                if (! Schema::hasColumn($name, 'payment_attempts')) {
                    $table->unsignedSmallInteger('payment_attempts')->default(0);
                }
                if (! Schema::hasColumn($name, 'paid_at')) {
                    $table->timestamp('paid_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $name) {
            if (! Schema::hasTable($name)) {
                continue;
            }

            $drop = array_values(array_filter(
                ['payment_proof', 'payment_proof_at', 'payment_reviewed_at', 'payment_reviewed_by', 'payment_reject_reason', 'payment_attempts', 'paid_at'],
                fn ($c) => Schema::hasColumn($name, $c)
            ));
            if ($drop) {
                Schema::table($name, fn (Blueprint $table) => $table->dropColumn($drop));
            }
        }
    }
};
