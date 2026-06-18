<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pesanan Toko Packing (header). Item disimpan sebagai JSON agar ringkas,
     * konsisten dengan alur checkout di desain (TokoPackingPage.tsx).
     */
    public function up(): void
    {
        Schema::create('packing_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();     // mis. TP-2026-48213
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('items');                       // [{product_id,name,price,qty}]
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping_cost')->default(0);
            $table->unsignedInteger('total');
            $table->string('logistic');                  // pickup | biteship
            $table->string('courier')->nullable();       // gojek | grab | lalamove
            $table->text('address')->nullable();
            $table->string('payment_method')->nullable(); // qris | va | ew
            $table->string('status')->default('diproses'); // diproses | dikirim | selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packing_orders');
    }
};
