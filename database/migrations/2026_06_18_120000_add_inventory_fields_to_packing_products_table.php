<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packing_products', function (Blueprint $table) {
            $table->string('unit', 32)->default('pcs')->after('category');
            $table->unsignedInteger('low_threshold')->default(10)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::table('packing_products', function (Blueprint $table) {
            $table->dropColumn(['unit', 'low_threshold']);
        });
    }
};
