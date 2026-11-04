<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'campus')) {
                $table->string('campus')->nullable();
            }
            if (! Schema::hasColumn('users', 'major')) {
                $table->string('major')->nullable();
            }
            if (! Schema::hasColumn('users', 'cohort_year')) {
                $table->string('cohort_year', 4)->nullable();
            }
            if (! Schema::hasColumn('users', 'hometown')) {
                $table->string('hometown')->nullable();
            }
            if (! Schema::hasColumn('users', 'notif_prefs')) {
                $table->json('notif_prefs')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['campus', 'major', 'cohort_year', 'hometown', 'notif_prefs'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
