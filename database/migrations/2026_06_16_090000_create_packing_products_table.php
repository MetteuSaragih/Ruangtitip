<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel produk Toko Packing — kardus, pelindung, perekat, dll.
     */
    public function up(): void
    {
        Schema::create('packing_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');                 // Kardus | Pelindung | Perekat
            $table->unsignedInteger('price');           // harga jual (Rupiah)
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedTinyInteger('discount')->nullable(); // persen, mis. 10
            $table->string('emoji', 16)->default('📦');  // placeholder gambar (sesuai desain)
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packing_products');
    }
};
