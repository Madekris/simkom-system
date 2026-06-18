<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Kegiatan;

class KegiatanSaya extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Ambil kegiatan MENDATANG yang diikuti oleh mahasiswa ini
        // Kita panggil relasi belongsToMany dari User, lalu filter status tabel kegiatans
        $kegiatanMendatang = Auth::user()->pendaftaranKegiatan()
            ->where('kegiatans.status', 'Mendatang') // Menegaskan filter status pada tabel kegiatans
            ->with('organisasi')
            ->orderBy('tanggal_kegiatan', 'asc')
            ->get();

        // 2. Ambil RIWAYAT KEGIATAN (Selesai / Berlangsung) yang diikuti oleh mahasiswa ini
        $riwayatKegiatan = Auth::user()->pendaftaranKegiatan()
            ->whereIn('kegiatans.status', ['Selesai', 'Berlangsung']) // Menegaskan filter status pada tabel kegiatans
            ->with('organisasi')
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get();

        // 3. Gabungkan keduanya hanya untuk mendapatkan daftar tahun unik untuk dropdown filter di Blade
        $semuaKegiatan = $kegiatanMendatang->merge($riwayatKegiatan);
        $daftarTahun = $semuaKegiatan->map(function($item) {
            return \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('Y');
        })->unique()->sort()->values();

        // 4. Kirim data yang telah disaring ke view Blade mahasiswa
        return view('pages.mahasiswa.kegiatanSaya', compact('kegiatanMendatang', 'riwayatKegiatan', 'daftarTahun'));
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
     * Display the specified resource.
     */
    public function show($id)
    {
        // Cari kegiatan beserta organisasi penyelenggaranya
        $kegiatan = Kegiatan::with('organisasi')->find($id);

        if (!$kegiatan) {
            return response()->json(['message' => 'Kegiatan tidak ditemukan'], 404);
        }

        // Kembalikan data terformat JSON untuk dibaca oleh Alpine.js
        return response()->json([
            'judul' => $kegiatan->judul_kegiatan,
            'ormawa' => $kegiatan->organisasi->nama ?? 'Umum',
            'tanggal' => \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('d F Y'),
            'waktu' => \Carbon\Carbon::parse($kegiatan->waktu_kegiatan)->format('H:i') . ' WITA',
            'lokasi' => $kegiatan->lokasi,
            'kuota' => $kegiatan->kuota_peserta . ' Peserta',
            'deskripsi' => $kegiatan->deskripsi ?? 'Tidak ada deskripsi.',
            'status' => $kegiatan->status
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