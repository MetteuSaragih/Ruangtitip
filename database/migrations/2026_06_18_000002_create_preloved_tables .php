<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preloved_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('Lainnya');
            $table->integer('condition')->default(90); // 70,75,80,85,90,95
            $table->unsignedInteger('price');
            $table->string('status')->default('Tersedia'); // Tersedia, Terjual, Draft
            $table->string('photo')->nullable();
            $table->json('photos')->nullable();
            $table->string('seller')->nullable(); // nama penitip
            $table->timestamps();
        });

        Schema::create('preloved_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('preloved_item_id')->constrained()->onDelete('cascade');
            $table->string('buyer_name');
            $table->string('buyer_wa');
            $table->unsignedInteger('price');
            $table->string('delivery'); // Dikirim, Ambil Sendiri, Ekspedisi Biteship
            $table->text('address')->nullable();
            $table->string('status')->default('Menunggu Konfirmasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preloved_orders');
        Schema::dropIfExists('preloved_items');
    }
};
