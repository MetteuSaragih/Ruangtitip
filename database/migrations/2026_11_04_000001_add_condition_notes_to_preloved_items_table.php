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
            if (! Schema::hasColumn('preloved_items', 'function_note')) {
                $table->string('function_note')->nullable();
            }
            if (! Schema::hasColumn('preloved_items', 'completeness')) {
                $table->string('completeness')->nullable();
            }
            if (! Schema::hasColumn('preloved_items', 'flaws')) {
                $table->string('flaws')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('preloved_items', function (Blueprint $table) {
            foreach (['function_note', 'completeness', 'flaws'] as $column) {
                if (Schema::hasColumn('preloved_items', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
