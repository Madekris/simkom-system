<?php

namespace Database\Seeders;

use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi;
use App\Models\Pembina;
use App\Models\PeriodeKepengurusan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PembinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPembina = [
            [
                'email' => '240030933@stikom-bali.ac.id',
                'no_telepon' => '0821445897',
                'password' => 'pembina123',
                'role' => 'pembina',
                'jabatan' => 'Pembina',
                'detail' => [
                    'nip' => '240030933',
                    'nama' => 'I Made Komang Sudiatmika',
                ]
            ]
        ];

        foreach ($dataPembina as $data) {
            // 1. Buat atau perbarui data di tabel users terlebih dahulu
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'no_telepon' => $data['no_telepon'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                ]
            );

            // 2. Gunakan id dari user yang baru dibuat untuk mengisi tabel mahasiswas
            Pembina::updateOrCreate(
                ['nip' => $data['detail']['nip']], // Cek keunikan berdasarkan NIM
                [
                    'id_user' => $user->id, // Hubungkan Foreign Key
                    'nama' => $data['detail']['nama'],
                ]
            );

            $organisasi = Organisasi::where('nama', 'BEM Institut Teknologi & Bisnis')->first();

            // Pastikan organisasi ditemukan agar tidak error null pointer
            if ($organisasi) {
                
                // 2. Ambil periode aktif yang memiliki id_organisasi terkait menggunakan first()
                $periode = PeriodeKepengurusan::where('id_organisasi', $organisasi->id)
                    ->where('status_periode', 'aktif')
                    ->first();

                // Pastikan periode aktifnya juga ditemukan sebelum insert ke anggota
                if ($periode) {
                    
                    // 3. Eksekusi updateOrCreate dengan ID yang sudah berupa data tunggal (bukan array/collection)
                    AnggotaOrganisasi::updateOrCreate(
                        [
                            'id_user' => $user->id, 
                            'id_organisasi' => $organisasi->id
                        ],
                        [
                            'id_periode' => $periode->id,
                            'jabatan' => $data['jabatan'],
                            'status' => 'aktif',
                        ]
                    );
                    
                } else {
                    // Handle jika periode aktif belum di-seed / belum dibuat
                    $this->command->warn("Periode aktif untuk {$organisasi->nama} tidak ditemukan.");
                }
            } else {
                // Handle jika nama organisasi salah / tidak ditemukan
                $this->command->warn("Organisasi BEM Institut Teknologi & Bisnis tidak ditemukan.");
            }
        }
    }
}
