<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Jalankan Seeder kustom untuk RUTIP
        $this->call([
            RuangTitipSeeder::class,
            PackingProductSeeder::class,
        ]);

        // 2. Data Testing (Opsional: bisa dikomentari jika tidak butuh)
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Tambahkan password agar user bisa login
        ]);
    }
}