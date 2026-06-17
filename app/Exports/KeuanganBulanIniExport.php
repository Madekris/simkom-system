<?php

namespace App\Exports;

namespace App\Exports;

use App\Models\KeuanganKegiatan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KeuanganBulanIniExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil data transaksi khusus bulan ini beserta relasi kegiatannya
     */
    public function collection()
    {
        // 1. Ubah menjadi 'kegiatan' sesuai nama fungsi di model KeuanganKegiatan
        return KeuanganKegiatan::with('kegiatan')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->latest('created_at')
            ->get();
    }

    /**
     * Tentukan Judul Kolom di baris paling atas Excel
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Kegiatan',
            'Keterangan / Rincian',
            'Jenis',
            'Nominal (Rp)',
        ];
    }

    /**
     * Petakan data agar Nama Kegiatan berada di sebelah rincian keuangannya
     */
    public function map($transaksi): array
    {
        return [
            $transaksi->created_at->format('d/m/Y'),
            // 2. Sesuaikan pemanggilan objek relasi ke ->kegiatan
            // Sesuaikan juga kolom 'judul_kegiatan' jika nama kolom aslinya berbeda (misal: 'nama_kegiatan')
            $transaksi->kegiatan->judul_kegiatan ?? 'N/A', 
            $transaksi->keterangan,
            ucfirst($transaksi->jenis_transaksi ?? '-'),
            $transaksi->nominal,
        ];
    }
}