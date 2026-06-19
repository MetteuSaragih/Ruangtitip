<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'penitip', 'admin') NOT NULL DEFAULT 'penitip'");
        DB::table('users')->where('role', 'user')->update(['role' => 'penitip']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('penitip', 'admin') NOT NULL DEFAULT 'penitip'");
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'role')) {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'penitip', 'admin') NOT NULL DEFAULT 'user'");
        DB::table('users')->where('role', 'penitip')->update(['role' => 'user']);
        DB::statement("ALTER TABLE users MODIFY role ENUM('user', 'admin') NOT NULL DEFAULT 'user'");
    }
};
