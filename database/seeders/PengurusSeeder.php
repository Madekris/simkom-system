<?php

namespace Database\Seeders;

use App\Models\AnggotaOrganisasi;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa; // Sesuaikan nama model Anda (biasanya Mahasiswa)
use App\Models\Organisasi;
use App\Models\PeriodeKepengurusan;
use Illuminate\Support\Facades\Hash;

class PengurusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataMahasiswa = [
            [
                'email' => '240030369@stikom-bali.ac.id',
                'no_telepon' => '082144556677',
                'password' => 'krisna123',
                'role' => 'pengurus',
                'jabatan' => 'Ketua',
                'detail' => [
                    'nim' => '240030369',
                    'nama' => 'I Made krisna Widiatmika',
                    'id_program_studi' => 1, // Sistem Informasi
                    'semester' => 4,
                ]
            ],
            [
                'email' => '240030386@stikom-bali.ac.id',
                'no_telepon' => '082144556677',
                'password' => 'dino123',
                'role' => 'pengurus',
                'jabatan' => 'Wakil Ketua',
                'detail' => [
                    'nim' => '240030386',
                    'nama' => 'I Gede Dino',
                    'id_program_studi' => 1, // Sistem Informasi
                    'semester' => 4,
                ]
            ],
            [
                'email' => '240030353@stikom-bali.ac.id',
                'no_telepon' => '082144556677',
                'password' => 'deny123',
                'role' => 'pengurus',
                'jabatan' => 'Sekretaris',
                'detail' => [
                    'nim' => '240030353',
                    'nama' => 'I Made Denny Krisna Dwipayana',
                    'id_program_studi' => 1, // Sistem Informasi
                    'semester' => 4,
                ]
            ],
            [
                'email' => '240030424@stikom-bali.ac.id',
                'no_telepon' => '082144556677',
                'password' => 'surya123',
                'role' => 'bendahara',
                'jabatan' => 'Bendahara',
                'detail' => [
                    'nim' => '240030424',
                    'nama' => 'Ida Bagus Putu Surya Negara',
                    'id_program_studi' => 1, // Sistem Informasi
                    'semester' => 4,
                ]
            ]
        ];

        foreach ($dataMahasiswa as $data) {
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
            Mahasiswa::updateOrCreate(
                ['nim' => $data['detail']['nim']], // Cek keunikan berdasarkan NIM
                [
                    'id_user' => $user->id, // Hubungkan Foreign Key
                    'nama' => $data['detail']['nama'],
                    'id_program_studi' => $data['detail']['id_program_studi'],
                    'semester' => $data['detail']['semester'],
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