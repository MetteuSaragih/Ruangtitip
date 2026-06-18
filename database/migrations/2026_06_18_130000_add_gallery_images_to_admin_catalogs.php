<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('storage_rooms', function (Blueprint $table) {
            if (! Schema::hasColumn('storage_rooms', 'photos')) {
                $table->json('photos')->nullable()->after('photo');
            }
        });

        Schema::table('preloved_items', function (Blueprint $table) {
            if (! Schema::hasColumn('preloved_items', 'photos')) {
                $table->json('photos')->nullable()->after('photo');
            }
        });

        Schema::table('packing_products', function (Blueprint $table) {
            if (! Schema::hasColumn('packing_products', 'images')) {
                $table->json('images')->nullable()->after('emoji');
            }
        });
    }

    public function down(): void
    {
        Schema::table('storage_rooms', function (Blueprint $table) {
            if (Schema::hasColumn('storage_rooms', 'photos')) {
                $table->dropColumn('photos');
            }
        });

        Schema::table('preloved_items', function (Blueprint $table) {
            if (Schema::hasColumn('preloved_items', 'photos')) {
                $table->dropColumn('photos');
            }
        });

        Schema::table('packing_products', function (Blueprint $table) {
            if (Schema::hasColumn('packing_products', 'images')) {
                $table->dropColumn('images');
            }
        });
    }
};
