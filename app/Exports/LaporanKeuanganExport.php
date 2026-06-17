<?php

namespace App\Exports;

use App\Models\Organisasi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanKeuanganExport implements FromView, ShouldAutoSize
{
    /**
     * Lempar data dari database ke dalam view khusus Excel
     */
    public function view(): View
    {
        return view('exports.laporan-keuangan', [
            // Mengambil semua organisasi beserta kegiatan dan transaksi didalamnya
            'ormawaWithKeuangan' => Organisasi::with('kegiatan.keuanganKegiatan')->get()
        ]);
    }
}