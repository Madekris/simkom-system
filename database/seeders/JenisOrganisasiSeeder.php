<?php

namespace Database\Seeders;

use App\Models\JenisOrganisasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisOrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Badan Eksekutif Mahasiswa (BEM)'],
            ['nama' => 'Himpunan Mahasiswa Jurusan (HMJ)'],
            ['nama' => 'Unit Kegiatan Mahasiswa (UKM)'],
            ['nama' => 'Komunitas Kampus'],
        ];

        foreach ($data as $item) {
            // Mengecek berdasarkan nama, jika belum ada maka dibuat baru
            JenisOrganisasi::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
