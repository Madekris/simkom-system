<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            // 'name' => 'Test User', <-- HAPUS ATAU KOMENTARI BARIS INI
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Tambahkan ini jika perlu password untuk login nanti
            'no_telepon' => '081234567890',
        ]);
    }
}
