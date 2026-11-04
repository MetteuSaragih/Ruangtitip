<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preloved_items', function (Blueprint $table) {
            if (! Schema::hasColumn('preloved_items', 'description')) {
                $table->text('description')->nullable();
            }
            $table->string('function_note')->nullable();
            $table->string('completeness')->nullable();
            $table->string('flaws')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('preloved_items', function (Blueprint $table) {
            $table->dropColumn(['function_note', 'completeness', 'flaws']);
        });
    }
};
