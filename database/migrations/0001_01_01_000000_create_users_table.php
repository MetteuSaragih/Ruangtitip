<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Create Users Table - rutip_db
|--------------------------------------------------------------------------
|
| Tabel ini mendukung dua metode registrasi:
| 1. Email + Password manual
| 2. Google OAuth (google_id diisi, password null)
|
| Kolom 'role' untuk membedakan penitip biasa vs admin.
|
*/

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name')->nullable();          // boleh kosong saat OTP pertama
    $table->string('email')->unique();
    $table->string('password')->nullable();       // tidak dipakai, tapi tetap ada
    $table->string('google_id')->nullable()->unique();
    $table->string('avatar')->nullable();
    $table->string('phone')->nullable();
    $table->enum('role', ['penitip', 'admin'])->default('penitip');
    $table->boolean('is_active')->default(true);
    $table->timestamp('email_verified_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
});

        Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
