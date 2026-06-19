<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Organisasi;
use Illuminate\Http\Request;

class SemuaKegiatan extends Controller
{
   public function index(Request $request) 
    {
        // 1. Ambil data ormawa untuk list pilihan di dropdown select
        $ormawa = Organisasi::with('jenisOrganisasi')->get();

        // 2. Bangun query dasar untuk kegiatan
        $query = Kegiatan::with('organisasi.jenisOrganisasi');

        // Filter 1: Berdasarkan Pencarian (Judul Kegiatan)
        $query->when($request->filled('search'), function ($q) use ($request) {
            $q->where('judul_kegiatan', 'like', '%' . $request->search . '%');
        });

        // Filter 2: Berdasarkan Status (Pending, Berlangsung, Selesai, Dibatalkan)
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // Filter 3: Berdasarkan ID Ormawa (Relasi organisasi_id)
        $query->when($request->filled('ormawa_id'), function ($q) use ($request) {
            $q->where('id_organisasi', $request->ormawa_id);
        });

        // 3. Eksekusi query (Urutkan dari yang terbaru jika mau)
        $kegiatan = $query->latest()->get();

        return view('pages.admin.semua-kegiatan', compact('kegiatan', 'ormawa'));
    }
}
