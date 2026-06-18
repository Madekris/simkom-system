<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\DokumenKegiatan;
use App\Models\Kegiatan;
use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index () {

        $namaUser = Auth::user()->name ?? 'Mahasiswa';

        // 2. Cari ormawa tempat mahasiswa ini terdaftar (menggunakan foreign key id_user)
        $idOrmawaUser = \App\Models\AnggotaOrganisasi::where('id_user', Auth::id())
            ->where('status', 'aktif')
            ->pluck('id_organisasi')
            ->toArray();

        $ormawa = Organisasi::whereIn('id', $idOrmawaUser)->get();

        // dd($ormawa->toArray());
        // 3. Hitung jumlah kegiatan aktif (Disetujui) dari ORMAWA tersebut
        $kegiatanAktif = \App\Models\Kegiatan::whereIn('id_organisasi', $idOrmawaUser)
            ->where('status', 'Mendatang')
            ->count();

        // 4. Hitung tugas/LPJ/dokumen pending (Sesuaikan dengan nama model atau kolom tugas di sistem Anda)
        // Di sini dicontohkan menghitung kegiatan yang masih berstatus 'Pending' atau butuh revisi
        $tugasPending = \App\Models\Kegiatan::whereIn('id_organisasi', $idOrmawaUser)
            ->where('status', 'Pending')
            ->count();


        // 
        // 
        // 

        // 2. Hitung Total Anggota Aktif di ORMAWA tersebut
        $totalAnggota = \App\Models\AnggotaOrganisasi::whereIn('id_organisasi', $idOrmawaUser)
            ->where('status', 'aktif')
            ->count();

        // 4. Hitung Kegiatan Selesai Bulan Ini (Status 'Selesai' dan terjadi pada bulan & tahun berjalan)
        $selesaiBulanIni = \App\Models\Kegiatan::whereIn('id_organisasi', $idOrmawaUser)
            ->where('status', 'Selesai')
            ->whereMonth('created_at', \Carbon\Carbon::now()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->count();

        // 1. Ambil semua ID kegiatan milik ORMAWA tersebut (Pastikan string kolom diberi tanda kutip)
        $idKegiatan = Kegiatan::where('id_organisasi', $idOrmawaUser)->pluck('id');

        // 2. Hitung Total Dokumen / Proposal / LPJ yang telah diunggah berdasarkan ID kegiatan di atas
        $totalDokumen = DokumenKegiatan::whereIn('id_kegiatan', $idKegiatan)->count();

        // 
        // 
        // 

        // 2. Ambil daftar kegiatan mendatang yang siap dilaksanakan (Status: Disetujui / Berlangsung / Dibatalkan)
        $kegiatanMendatang = \App\Models\Kegiatan::whereIn('id_organisasi', $idOrmawaUser)
            ->whereIn('status', ['Mendatang', 'Berlangsung']) // Ambil status relevan
            ->orderBy('tanggal_kegiatan', 'asc')
            ->take(3) // Batasi maksimal 3 item sesuai kebutuhan template layout
            ->get();

        return view('pages.pengurus.dashboard', compact(
            'namaUser',
            'kegiatanAktif',
            'tugasPending',
            'totalAnggota',
            'selesaiBulanIni',
            'totalDokumen',
            'kegiatanMendatang',
            'ormawa'
        ));
    }
}
