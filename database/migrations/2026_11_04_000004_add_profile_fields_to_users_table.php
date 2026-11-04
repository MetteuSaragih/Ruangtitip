<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('campus')->nullable();
            $table->string('major')->nullable();
            $table->string('cohort_year', 4)->nullable();
            $table->string('hometown')->nullable();
            $table->json('notif_prefs')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['campus', 'major', 'cohort_year', 'hometown', 'notif_prefs']);
        });
    }
};
