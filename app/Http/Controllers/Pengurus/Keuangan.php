<?php

namespace App\Http\Controllers\Pengurus;

use App\Exports\KeuanganOrmawaExport;
use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class Keuangan extends Controller
{
    public function index ()
    {
        $idOrmawaUser = \App\Models\AnggotaOrganisasi::where('id_user', Auth::id())
            ->where('status', 'aktif')
            ->value('id_organisasi');

        $ormawa = Organisasi::where('id', $idOrmawaUser)->get();

        $ormawaWithKeuangan = Organisasi::with('kegiatan.keuanganKegiatan')->find($idOrmawaUser);

        // dd($ormawaWithKeuangan->toArray());
        return view('pages.pengurus.keuangan', compact(
            'ormawa',
            'ormawaWithKeuangan'
        ));
    }
    
    public function export( string $id, Request $request)
    {
        // 1. Cari data organisasi terlebih dahulu untuk penamaan file file excel yang dinamis
        $ormawa = Organisasi::findOrFail($id);
        
        // Slug nama ormawa agar aman digunakan sebagai nama file (contoh: ukm-mapala)
        $fileName = 'laporan-keuangan-' . Str::slug($ormawa->nama) . '-' . now()->format('Y-m-d') . '.xlsx';

        // Jika input 'start_date' kosong, default-nya adalah 1 bulan yang lalu dari hari ini
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));

        // Jika input 'end_date' kosong, default-nya adalah hari ini
        $endDate = $request->input('end_date', now()->format('Y-m-d'));


        // Sekarang aman dipanggil karena genap 3 parameter
        return Excel::download(new KeuanganOrmawaExport($id, $startDate, $endDate), 'laporan-keuangan.xlsx');
    }
}
