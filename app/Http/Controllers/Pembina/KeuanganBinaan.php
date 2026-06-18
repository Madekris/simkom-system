<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\KeuanganKegiatan;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganBinaan extends Controller
{
    public function index (Request $request) {

    $totalPemasukan = KeuanganKegiatan::where('jenis_transaksi', 'pemasukan')->sum('nominal');
    $totalPengeluaran = KeuanganKegiatan::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
    $ormawaWithKeuangan = Organisasi::with('kegiatan.keuanganKegiatan')->get();

    // dd($ormawaWithKeuangan->toArray());
    $keyword = $request->input('search');

    $userId = Auth::id();

// 1. Ambil hanya daftar ID organisasi yang dibina oleh user ini (pluck untuk efisiensi memori)
    $idOrganisasiBinaan = AnggotaOrganisasi::where('id_user', $userId)
        ->pluck('id_organisasi') // Ganti 'organisasi_id' sesuai nama foreign key di tabel anggota_organisasi Anda
        ->toArray();

    // 2. Query mengambil data ormawa keuangan bertingkat, dikunci hanya untuk organisasi binaan
    $ormawaWithKeuangan = Organisasi::with(['kegiatan.keuanganKegiatan'])
        // Kunci scope hanya untuk organisasi yang dibina
        ->whereIn('id', $idOrganisasiBinaan)
        
        // Kondisi pencarian: Jika variabel $keyword terisi, filter berdasarkan nama
        ->when($keyword, function($query) use ($keyword) {
            return $query->where('nama', 'LIKE', '%' . $keyword . '%');
        })
        ->get();

        return view('pages.pembina.keuangan-binaan', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'ormawaWithKeuangan'
        ));
    }
}
