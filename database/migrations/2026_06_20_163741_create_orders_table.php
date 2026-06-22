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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->json('items');
            $table->unsignedInteger('subtotal')->default(0);
            $table->unsignedInteger('service_fee')->default(0);
            $table->unsignedInteger('shipping_cost')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->string('shipping_method')->nullable();
            $table->json('shipping_address')->nullable();
            $table->string('courier_code')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('courier_service_code')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('midtrans_token')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('biteship_order_id')->nullable();
            $table->string('biteship_tracking_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
