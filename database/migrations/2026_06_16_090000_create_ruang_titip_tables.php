<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Ruang Titip — tabel gudang, ukuran item, ulasan, dan pesanan
|--------------------------------------------------------------------------
*/

return new class extends Migration
{
    public function up(): void
    {
        // Gudang / Ruang Titip
        Schema::create('storages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->unsignedTinyInteger('filled')->default(0); // persen kapasitas
            $table->unsignedInteger('price')->default(0);       // harga dasar / bulan
            $table->json('tags')->nullable();                   // ["AC","CCTV","24 Jam"]
            $table->string('emoji')->default('🏢');
            $table->string('color')->default('#7c3aed');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Ulasan gudang
        Schema::create('storage_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_id')->constrained()->cascadeOnDelete();
            $table->string('reviewer_name');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('text');
            $table->string('avatar', 4)->nullable();
            $table->string('color')->default('#7c3aed');
            $table->timestamps();
        });

        // Master ukuran item (kardus / koper / dimensi)
        Schema::create('item_sizes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['kardus', 'koper', 'dimensi']);
            $table->string('code');         // ks, km, kl ...
            $table->string('label');        // S, M, L, Cabin ...
            $table->string('dims');         // "20×15×10 cm"
            $table->unsignedInteger('price'); // harga/bulan
            $table->timestamps();
        });

        // Master kurir (dummy Biteship)
        Schema::create('couriers', function (Blueprint $table) {
            $table->id();
            $table->enum('group', ['instant', 'rutip']); // instant shipper / anjem rutip
            $table->string('code');
            $table->string('name');
            $table->string('service');
            $table->string('eta');
            $table->unsignedInteger('price');
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // Pesanan titipan (disimpan di akhir alur)
        Schema::create('titipan_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('storage_id')->constrained('storage_rooms');
            $table->string('item_type');             // kardus/koper/dimensi
            $table->json('items');                   // {"km":2,"kl":1}
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('pickup_time')->nullable();
            $table->string('logistic')->nullable();  // self/rutip/instant
            $table->text('address')->nullable();
            $table->text('note')->nullable();
            $table->string('courier_code')->nullable();
            $table->string('packing')->nullable();   // self/buy
            $table->string('payment_method')->nullable();
            $table->unsignedInteger('item_subtotal')->default(0);
            $table->unsignedInteger('courier_cost')->default(0);
            $table->unsignedInteger('packing_cost')->default(0);
            $table->unsignedInteger('platform_fee')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->string('status')->default('menunggu_pembayaran');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titipan_orders');
        Schema::dropIfExists('couriers');
        Schema::dropIfExists('item_sizes');
        Schema::dropIfExists('storage_reviews');
        Schema::dropIfExists('storages');
    }
};
