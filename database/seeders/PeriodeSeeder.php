<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organisasi;
use App\Models\Periode; // Sesuaikan dengan nama Model Periode Anda
use App\Models\PeriodeKepengurusan;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil semua organisasi yang sudah di-seed sebelumnya
        $daftarOrganisasi = Organisasi::all();

        // Jika tabel organisasi masih kosong, beri peringatan agar tidak error
        if ($daftarOrganisasi->isEmpty()) {
            $this->command->warn('Seeder Periode dilewati karena data Organisasi masih kosong. Jalankan OrganisasiSeeder terlebih dahulu!');
            return;
        }

        // 2. Loop setiap organisasi untuk dipasangkan dengan periode jabatannya
        foreach ($daftarOrganisasi as $organisasi) {
            
            // Data Periode Lama (2024 - 2025) -> Status: demisioner / tidak aktif
            PeriodeKepengurusan::updateOrCreate(
                [
                    'id_organisasi' => $organisasi->id,
                    'tahun_mulai' => 2024,
                    'tahun_selesai' => 2025,
                ],
                [
                    'status_periode' => 'nonaktif', // atau 'demisioner' sesuaikan kebutuhan sistem Anda
                ]
            );

            // Data Periode Aktif Sekarang (2025 - 2026) -> Status: aktif
            PeriodeKepengurusan::updateOrCreate(
                [
                    'id_organisasi' => $organisasi->id,
                    'tahun_mulai' => 2025,
                    'tahun_selesai' => 2026,
                ],
                [
                    'status_periode' => 'aktif',
                ]
            );
        }
    }
}