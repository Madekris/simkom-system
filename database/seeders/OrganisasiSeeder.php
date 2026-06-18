<?php

namespace Database\Seeders;

use App\Models\JenisOrganisasi;
use Illuminate\Database\Seeder;
use App\Models\Organisasi; // Sesuaikan dengan nama Model Organisasi Anda

class OrganisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // Ambil atau buat otomatis jika belum ada di database
        $idBem = JenisOrganisasi::firstOrCreate(['nama' => 'Badan Eksekutif Mahasiswa (BEM)'])->id;
        $idUkm = JenisOrganisasi::firstOrCreate(['nama' => 'Unit Kegiatan Mahasiswa (UKM)'])->id; 
        // Catatan: Jika HIMSISFO itu HMJ, kamu bisa ganti namanya jadi 'Himpunan Mahasiswa Jurusan (HMJ)'

        $organisasi = [
            [
                'nama' => 'BEM Institut Teknologi & Bisnis',
                'id_jenis_organisasi' => $idBem, // <-- Gunakan variabel di atas
                'visi' => 'Mewujudkan komitmen mahasiswa yang solutif, adaptif, dan berjiwa technopreneur.',
                'misi' => 'Mengembangkan ekosistem digital internal, mengadvokasi hak mahasiswa, dan menyelenggarakan inkubasi bisnis.',
                'deskripsi' => 'Lembaga eksekutif tertinggi di tingkat kampus yang menjembatani mahasiswa dengan rektorat.',
                'ad_art' => 'ad_art_bem.pdf',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Himpunan Mahasiswa Sistem Informasi (HIMSISFO)',
                'id_jenis_organisasi' => $idUkm, // <-- Gunakan variabel di atas
                'visi' => 'Menjadi pusat inovasi dan kolaborasi teknologi informasi terkemuka.',
                'misi' => 'Meningkatkan kompetensi coding, analisis data, manajemen projek, serta menyelenggarakan IT Fest tahunan.',
                'deskripsi' => 'Wadah mahasiswa program studi Sistem Informasi untuk mengembangkan soft skill dan hard skill industri.',
                'ad_art' => 'ad_art_himsisfo.pdf',
                'status' => 'aktif',
            ]
        ];

        foreach ($organisasi as $item) {
            Organisasi::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}