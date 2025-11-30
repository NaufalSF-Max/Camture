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
        // User::factory(10)->create();

        // Membuat Akun Admin
        User::factory()->create([
            'name' => 'Admin Camture',
            'email' => 'admin@camture.com',
            'password' => bcrypt('password'), // passwordnya adalah 'password'
            'role' => 'admin',
        ]);

        // Membuat Akun User Biasa
        User::factory()->create([
            'name' => 'User Camture',
            'email' => 'user@camture.com',
            'password' => bcrypt('password'), // passwordnya adalah 'password'
            'role' => 'user',
        ]);

        // 3. Buat 50 Data Dummy User (Untuk tes Pagination & Search)
        User::factory(50)->create();

        $this->command->info('Berhasil membuat 1 Admin, 1 User Test, dan 50 User Dummy!');
    }
}
