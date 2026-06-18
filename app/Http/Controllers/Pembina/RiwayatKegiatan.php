<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
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
        $namaUser = Auth::user()->name ?? 'Pembina';

        // 2. Ambil ID organisasi yang dibina (menggunakan foreign key id_organisasi sesuai struktur tabel Anda)
        $idOrmawaBinaan = AnggotaOrganisasi::where('id_user', Auth::id())
            ->pluck('id_organisasi')
            ->toArray();

        // 3. Hitung total ormawa yang dibina
        $totalOrmawaBinaan = count($idOrmawaBinaan);

        // 4. Hitung jumlah kegiatan dari ormawa binaan tersebut yang berstatus 'Pending'
        $totalKegiatanPending = Kegiatan::whereIn('id_organisasi', $idOrmawaBinaan)
            ->where('status', 'Pending')
            ->count();


        // 
        // 

        $idOrmawaBinaan = \App\Models\AnggotaOrganisasi::where('id_user', Auth::id())
            ->pluck('id_organisasi')
            ->toArray();

        // 2. Hitung statistik masing-masing card
        $totalOrmawaBinaan = count($idOrmawaBinaan);

        // Kegiatan Aktif (Status 'Disetujui' atau sedang berjalan)
        $kegiatanAktif = \App\Models\Kegiatan::whereIn('id_organisasi', $idOrmawaBinaan)
            ->where('status', 'Mendatang')
            ->count();

        // Menunggu Review (Status 'Pending')
        $menungguReview = \App\Models\Kegiatan::whereIn('id_organisasi', $idOrmawaBinaan)
            ->where('status', 'Pending')
            ->count();

        // Disetujui Bulan Ini (Status 'Disetujui' dan dibuat pada bulan berjalan)
        $disetujuiBulanIni = \App\Models\Kegiatan::whereIn('id_organisasi', $idOrmawaBinaan)
            ->where('status', 'Mendatang')
            ->whereMonth('created_at', \Carbon\Carbon::now()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->count();


        // 
        // 
        // 

        $idOrmawaBinaan = \App\Models\AnggotaOrganisasi::where('id_user', Auth::id())
            ->pluck('id_organisasi')
            ->toArray();

        // 2. Ambil data ORMAWA lengkap dengan jumlah anggota aktif dan jumlah kegiatan aktif
        $listOrmawaBinaan = \App\Models\Organisasi::whereIn('id', $idOrmawaBinaan)
            ->withCount([
                // Menghitung anggota yang statusnya aktif di ormawa ini
                'anggotaOrganisasi as total_anggota' => function($query) {
                    $query->where('status', 'aktif'); 
                },
                // Menghitung kegiatan yang sudah disetujui/aktif
                'kegiatan as kegiatan_aktif_count' => function($query) {
                    $query->where('status', 'Mendatang');
                }
            ])
            ->get();

        // 3. Ambil data Kegiatan berstatus 'Pending' untuk panel persetujuan mendesak
        $kegiatanMendesak = \App\Models\Kegiatan::with('organisasi')
            ->whereIn('id_organisasi', $idOrmawaBinaan)
            ->where('status', 'Pending')
            ->orderBy('tanggal_kegiatan', 'asc') // Mengurutkan dari yang paling dekat tanggalnya
            ->take(5) // Membatasi maksimal 5 data agar tampilan tetap proporsional
            ->get();
        
        return view('pages.pembina.dashboard', compact(
            'namaUser',
            'totalOrmawaBinaan',
            'totalKegiatanPending',
            'kegiatanAktif',
            'menungguReview',
            'disetujuiBulanIni',
            'listOrmawaBinaan',
            'kegiatanMendesak'
        ));
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
