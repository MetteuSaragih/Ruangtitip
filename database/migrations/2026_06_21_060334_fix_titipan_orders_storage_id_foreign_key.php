<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function foreignKeyExists(string $table, string $constraint): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraint)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->exists();
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if ($this->foreignKeyExists('titipan_orders', 'titipan_orders_storage_id_foreign')) {
            Schema::table('titipan_orders', function (Blueprint $table) {
                $table->dropForeign('titipan_orders_storage_id_foreign');
            });
        }

        if (! $this->foreignKeyExists('titipan_orders', 'titipan_orders_storage_id_foreign')) {
            Schema::table('titipan_orders', function (Blueprint $table) {
                $table->foreign('storage_id')->references('id')->on('storage_rooms')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->foreignKeyExists('titipan_orders', 'titipan_orders_storage_id_foreign')) {
            Schema::table('titipan_orders', function (Blueprint $table) {
                $table->dropForeign('titipan_orders_storage_id_foreign');
            });

            Schema::table('titipan_orders', function (Blueprint $table) {
                $table->foreign('storage_id')->references('id')->on('storages');
            });
        }
    }
};
