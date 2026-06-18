<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@stikom-bali.ac.id'], // Kondisi pengecekan keunikan berdasarkan email
            [
                'no_telepon' => '081234567890',
                'password'   => Hash::make('admin123'), // Mengamankan password
                'role'       => 'admin', // Mengisi kolom ENUM dengan 'admin'
            ]
        );
    }
}
