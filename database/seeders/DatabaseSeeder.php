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
    }
}
