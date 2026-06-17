<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKeuanganExport;
class KeuanganOrmawa extends Controller
{
    public function index () {

        $ormawaWithKeuangan = Organisasi::with('kegiatan.keuanganKegiatan')->get();

       
        // dd($ormawaWithKeuangan->toArray());
        return view('pages.admin.keuangan-ormawa', compact('ormawaWithKeuangan'));
    }

    public function exportExcel()
    {
        $namaFile = 'Laporan_Keuangan_Ormawa_' . now()->format('Y-m-d') . '.xlsx';
        
        return Excel::download(new LaporanKeuanganExport, $namaFile);
    }
}
