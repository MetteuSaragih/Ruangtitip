<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preloved_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('source')->default('luar'); // luar | titip
            $table->string('titip_ref')->nullable();
            $table->boolean('allow_open')->default(false);
            $table->string('name');
            $table->string('category');
            $table->string('condition'); // baru | baik | cukup
            $table->text('description');
            $table->string('completeness');
            $table->string('flaws');
            $table->json('photos')->nullable();
            $table->string('bank_name');
            $table->string('bank_number');
            $table->string('bank_holder');
            $table->string('status')->default('seleksi');
            $table->text('reason')->nullable();
            $table->unsignedInteger('offer')->nullable();
            $table->date('schedule_date')->nullable();
            $table->string('schedule_slot')->nullable();
            $table->json('log')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preloved_submissions');
    }
};
