<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\Organisasi;
use App\Models\PeriodeKepengurusan;
use Illuminate\Database\Seeder;

class KegiatanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Organisasi secara dinamis berdasarkan nama ormawa
        $bem = Organisasi::where('nama', 'BEM Institut Teknologi & Bisnis')->first();

        $periode = PeriodeKepengurusan::where('id_organisasi', $bem->id)->first();
      

        $kegiatans = [
            [
                'id_organisasi' => $bem->id,
                'id_periode' => $periode->id,
                'judul_kegiatan' => 'Workshop UI/UX Design & Prototyping bersama Figma',
                'deskripsi' => 'Pelatihan intensif pembuatan design system, wireframe, hingga high-fidelity prototype yang interaktif menggunakan Figma untuk persiapan kompetisi nasional. Kegiatan ini dirancang khusus untuk mahasiswa Sistem Informasi agar dapat memahami standar industri.',
                'tanggal_kegiatan' => '2026-07-10',
                'waktu_kegiatan' => '09:00:00',
                'lokasi' => 'Lab Komputer 3, Gedung Utama Lt. 2',
                'kuota_peserta' => 50,
                'status' => 'Pending',
                'evaluasi_kegiatan' => null,
            ],
            [
                'id_organisasi' => $bem->id, // Sudah diperbaiki dari $idBerry ke $idBem
                'id_periode' => $periode->id,
                'judul_kegiatan' => 'Seminar Technopreneurship: Membangun Startup dari Kampus',
                'deskripsi' => 'Temukan strategi validasi ide bisnis digital, mencari pendanaan awal (seed funding), serta kisah sukses alumni dalam membangun startup berbasis teknologi dari nol.',
                'tanggal_kegiatan' => '2026-07-24',
                'waktu_kegiatan' => '13:30:00',
                'lokasi' => 'Aula Besar Kampus',
                'kuota_peserta' => 150,
                'status' => 'Mendatang',
                'evaluasi_kegiatan' => null,
            ],
            [
                'id_organisasi' => $bem->id,
                'id_periode' => $periode->id,
                'judul_kegiatan' => 'Hackathon SIMKOM 2026: Sustainable Technology Solution',
                'deskripsi' => 'Kompetisi coding 24 jam tingkat internal untuk menciptakan solusi perangkat lunak guna menyelesaikan masalah lingkungan hidup dan manajemen energi bersih di lingkungan sekitar.',
                'tanggal_kegiatan' => '2026-08-05',
                'waktu_kegiatan' => '08:00:00',
                'lokasi' => 'Gedung Inkubator Bisnis Lt. 3',
                'kuota_peserta' => 30,
                'status' => 'Selesai',
                'evaluasi_kegiatan' => 'Acara berjalan dengan sangat sukses, diikuti oleh 10 tim terbaik. Juara 1 berhasil dimenangkan oleh tim dari prodi Sistem Informasi.',
            ],
            // DATA BARU: STATUS DIBATALKAN
            [
                'id_organisasi' => $bem->id,
                'id_periode' => $periode->id,
                'judul_kegiatan' => 'Studi Banding Interaktif ke Bali Creative Industry Center',
                'deskripsi' => 'Kunjungan industri untuk mempelajari manajemen inkubasi bisnis kreatif, pengembangan ekosistem startup lokal, serta peluang kolaborasi riset antara universitas dan pihak industri.',
                'tanggal_kegiatan' => '2026-09-12',
                'waktu_kegiatan' => '08:30:00',
                'lokasi' => 'Bali Creative Industry Center (BCIC), Denpasar',
                'kuota_peserta' => 40,
                'status' => 'Dibatalkan',
                'evaluasi_kegiatan' => 'Kegiatan terpaksa dibatalkan dikarenakan adanya bentrok jadwal dengan agenda akreditasi institusi dan pembatasan kuota kunjungan dari pihak lokasi target.',
            ],
        ];

        foreach ($kegiatans as $kegiatan) {
            Kegiatan::updateOrCreate(
                ['judul_kegiatan' => $kegiatan['judul_kegiatan']], // Kunci pengecekan agar tidak duplikat saat di-run ulang
                $kegiatan
            );
        }
    }
}