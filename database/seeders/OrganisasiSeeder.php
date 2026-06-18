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
        $organisasi = [
            [
                'nama' => 'BEM Institut Teknologi & Bisnis',
                'id_jenis_organisasi' => JenisOrganisasi::where('nama', 'Badan Eksekutif Mahasiswa (BEM)')->value('id'),
                'visi' => 'Mewujudkan komitmen mahasiswa yang solutif, adaptif, dan berjiwa technopreneur.',
                'misi' => 'Mengembangkan ekosistem digital internal, mengadvokasi hak mahasiswa, dan menyelenggarakan inkubasi bisnis.',
                'deskripsi' => 'Lembaga eksekutif tertinggi di tingkat kampus yang menjembatani mahasiswa dengan rektorat.',
                'ad_art' => 'ad_art_bem.pdf',
                'status' => 'aktif',
            ],
            [
                'nama' => 'Himpunan Mahasiswa Sistem Informasi (HIMSISFO)',
                'id_jenis_organisasi' => JenisOrganisasi::where('nama', 'Unit Kegiatan Mahasiswa')->value('id'), // Himpunan Mahasiswa Jurusan (HMJ)
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