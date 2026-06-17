<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RiwayatKegiatan extends Controller
{
/**
     * ── FUNGSI BARU: KHUSUS DASHBOARD UTAMA PEMBINA ──
     * Mengisi rute: pembina.dashboard
     */
    public function dashboard()
    {
        // Di sini kamu bisa menghitung total kegiatan untuk widget dashboard nanti
        $totalSelesai = Kegiatan::whereIn('status', ['complete', 'Selesai'])->count();
        $totalBerjalan = Kegiatan::whereIn('status', ['ongoing', 'Berlangsung'])->count();

        // Buat file blade baru bernama dashboard.blade.php di dalam folder Pembina
        return view('pages.pembina.dashboard', compact('totalSelesai', 'totalBerjalan'));
    }

    /**
     * Tampilan Utama Riwayat Kegiatan
     */
    public function index()
    {
        // Ambil semua kegiatan yang memiliki status riwayat
        $riwayatRaw = Kegiatan::whereIn('status', ['complete', 'ongoing', 'selesai', 'berlangsung'])
            ->with('organisasi')
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get();

        // Transformasi data agar status 'complete' otomatis terbaca sebagai 'selesai' di view/Alpine.js
        $riwayatKegiatan = $riwayatRaw->map(function($kegiatan) {
            // Standarisasi status untuk kebutuhan visual dan filter UI
            if ($kegiatan->status === 'complete' || $kegiatan->status === 'selesai') {
                $kegiatan->status_ui = 'selesai';
                $kegiatan->status_label = 'Selesai';
            } else {
                $kegiatan->status_ui = 'berlangsung';
                $kegiatan->status_label = 'Berlangsung';
            }
            return $kegiatan;
        });

        // Ambil daftar tahun unik dari kegiatan untuk filter dropdown
        $daftarTahun = $riwayatKegiatan->map(function($item) {
            return \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('Y');
        })->unique()->sort()->values();

        return view('pages.pembina.riwayatKegiatan', compact('riwayatKegiatan', 'daftarTahun'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Endpoint API JSON untuk Modal Detail Alpine.js
     */
    public function show($id)
    {
        // Ambil kegiatan beserta relasi pendaftaran untuk menghitung jumlah peserta terdaftar
        $kegiatan = Kegiatan::with(['organisasi', 'pendaftaranPesertaKegiatan'])->find($id);

        if (!$kegiatan) {
            return response()->json(['message' => 'Kegiatan tidak ditemukan'], 404);
        }

        // Menghitung realisasi peserta yang disetujui (status = 'disetujui' di tabel pivot)
        $realisasiPeserta = $kegiatan->pendaftaranPesertaKegiatan->where('status', 'disetujui')->count();

        // Mapping status database ke label UI Anda
        $statusLabel = 'Selesai';
        if ($kegiatan->status === 'ongoing') {
            $statusLabel = 'Berlangsung';
        }

        return response()->json([
            'judul' => $kegiatan->judul_kegiatan,
            'ormawa' => $kegiatan->organisasi->nama ?? 'Umum',
            'tanggal' => Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('d F Y'),
            'waktu' => Carbon::parse($kegiatan->waktu_kegiatan)->format('H:i') . ' WITA',
            'lokasi' => $kegiatan->lokasi,
            'kuota' => $kegiatan->kuota_peserta . ' Peserta',
            'realisasi' => $realisasiPeserta . ' Terdaftar',
            'deskripsi' => $kegiatan->deskripsi ?? 'Tidak ada deskripsi.',
            'evaluasi' => $kegiatan->evaluasi_kegiatan ?? 'Belum ada input evaluasi dari kepanitiaan.',
            'status' => $statusLabel
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
