<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preloved_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->integer('discount_percent')->default(0);
            $table->enum('condition', ['90%+ Mulus', '85%+ Baik', '75% Pernah Pakai', '95% Mulus']);
            $table->integer('stock')->default(1);
            $table->string('image')->nullable();
            $table->string('emoji')->default('📦');
            $table->string('seller_name');
            $table->decimal('seller_rating', 3, 1)->default(5.0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preloved_products');
    }
};