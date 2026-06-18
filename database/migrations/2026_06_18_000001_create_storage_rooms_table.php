<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->text('address');
            $table->text('description')->nullable();
            $table->json('pricing');          // {kardus:[{id,label,dims,price}], koper:[...], dimensiLain:int}
            $table->integer('capacity_total')->default(0);
            $table->integer('capacity_used')->default(0);
            $table->json('facilities')->nullable();
            $table->string('photo')->nullable();
            $table->json('photos')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_rooms');
    }
};
