<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('preloved_products', function (Blueprint $table) {
            $table->unsignedInteger('weight')->default(1000)->after('price'); // gram
        });

        Schema::table('packing_products', function (Blueprint $table) {
            $table->unsignedInteger('weight')->default(500)->after('price'); // gram
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preloved_products', function (Blueprint $table) {
            $table->dropColumn('weight');
        });

        Schema::table('packing_products', function (Blueprint $table) {
            $table->dropColumn('weight');
        });
    }
};
