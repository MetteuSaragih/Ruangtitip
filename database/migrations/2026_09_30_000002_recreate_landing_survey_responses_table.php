<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('landing_survey_responses');

        Schema::create('landing_survey_responses', function (Blueprint $table) {
            $table->id();
            $table->string('minat');
            $table->json('layanan')->nullable();
            $table->string('harga')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_survey_responses');
    }
};
