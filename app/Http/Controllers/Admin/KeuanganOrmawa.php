<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;
use Barryvdh\DomPDF\Facade\Pdf;

class KeuanganOrmawa extends Controller
{
    public function index () {
        $ormawaWithKeuangan = Organisasi::with('kegiatan.keuanganKegiatan', 'pembina.user.pembina')->get();
        return view('pages.admin.keuangan-ormawa', compact('ormawaWithKeuangan'));
    }

    public function exportExcel(Request $request)
    {
        $format = $request->query('format', 'excel');
        $idOrganisasi = $request->query('id_organisasi'); // Ambil filter id jika ada
        $tanggal = now()->format('Y-m-d');

        // 1. Ambil query base (Koleksi data)
        $query = Organisasi::with('kegiatan.keuanganKegiatan');
        
        // Saring jika tombol export berasal dari dalam modal detail ormawa tertentu
        if ($idOrganisasi) {
            $query->where('id', $idOrganisasi);
            $ormawaSpesifik = Organisasi::find($idOrganisasi);
            $slugNama = str_replace(' ', '_', $ormawaSpesifik->nama ?? 'Ormawa');
            $namaBaseFile = 'Laporan_Keuangan_' . $slugNama . '_' . $tanggal;
        } else {
            $namaBaseFile = 'Laporan_Keuangan_Global_Ormawa_' . $tanggal;
        }

        $ormawaWithKeuangan = $query->get();

        // ---------------------------------------------------------
        // PROSES PENGUNDUHAN BERDASARKAN FORMAT
        // ---------------------------------------------------------
        if ($format === 'excel') {
            // Kita bisa pakai view blade excel yang sama, Maatwebsite otomatis menyesuaikan datanya
            return Excel::download(new LaporanKeuanganExport($ormawaWithKeuangan), $namaBaseFile . '.xlsx');
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('pages.admin.cetak.pdf_keuangan_global', compact('ormawaWithKeuangan'))
                      ->setPaper('a4', 'landscape');
                      
            return $pdf->download($namaBaseFile . '.pdf');
        }
    }
}