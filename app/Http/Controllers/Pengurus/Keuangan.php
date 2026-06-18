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
    
    public function export( string $id)
    {
        // 1. Cari data organisasi terlebih dahulu untuk penamaan file file excel yang dinamis
        $ormawa = Organisasi::findOrFail($id);
        
        // Slug nama ormawa agar aman digunakan sebagai nama file (contoh: ukm-mapala)
        $fileName = 'laporan-keuangan-' . Str::slug($ormawa->nama) . '-' . now()->format('Y-m-d') . '.xlsx';

        // 2. Trigger download excel lewat package
        return Excel::download(new KeuanganOrmawaExport($id), $fileName);
    }
}
