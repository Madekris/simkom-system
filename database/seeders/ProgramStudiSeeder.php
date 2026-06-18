<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            ['nama' => 'Sistem Informasi'],
            ['nama' => 'Sistem Komputer'],
            ['nama' => 'Teknologi Informasi'],
            ['nama' => 'Bisnis Digital'],
        ];

        foreach ($prodis as $prodi) {
            // Menggunakan updateOrCreate agar saat di-run berkali-kali tidak duplikat
            ProgramStudi::updateOrCreate(
                ['nama' => $prodi['nama']],
                $prodi
            );
        }
    }
}
