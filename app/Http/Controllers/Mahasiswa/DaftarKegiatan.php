<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\PendaftaranPesertaKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DaftarKegiatan extends Controller
{
    public function index ()
    {

        // dd($daftarKegiatan->toArray());
        $daftarKegiatan = Kegiatan::with('organisasi')
        ->withCount('pendaftaranPesertaKegiatan') // <-- Hitung relasi secara efisien di sini
        ->where('status', 'Mendatang')
        ->get();

        return view('pages.mahasiswa.daftar-kegiatan', compact(
            'daftarKegiatan'
        ));
    }
    public function show (string $id)
    {

        $daftarKegiatan = Kegiatan::with(['periode', 'organisasi'])
            ->withCount('pendaftaranPesertaKegiatan')
            ->where('id', $id) // Cari berdasarkan ID yang spesifik
            ->first();
        // dd($daftarKegiatan->toArray());
        return view('pages.mahasiswa.daftar-kegiatan-detail', compact(
            'daftarKegiatan'
        ));
    }

    public function daftar(string $id)
    {
        // 1. Proses simpan data ke database

        $userId = Auth::id();

        // 1. Cek apakah user sudah pernah mendaftar di kegiatan ini sebelumnya
        $pendaftaranLama = PendaftaranPesertaKegiatan::where('id_kegiatan', $id)
            ->where('id_user', $userId)
            ->first();
        
        if ($pendaftaranLama) {
            // Jika statusnya BUKAN 'Gagal' (artinya masih Menunggu konfirmasi / Pendaftaran berhasil)
            // Maka dia TIDAK BOLEH mendaftar lagi
            if ($pendaftaranLama->status !== 'Gagal') {
                return redirect()->back()->with('error', 'Kamu sudah mendaftar di kegiatan ini dengan status: ' . $pendaftaranLama->status);
            }
            
            // Opsional: Jika statusnya 'Gagal', kamu bisa memilih untuk menghapus data lama 
            // atau membiarkannya dan membuat baris baru. Di sini kita biarkan membuat baris baru.
        }
        PendaftaranPesertaKegiatan::create([
            'id_kegiatan' => $id,
            'id_user' => $userId, 
            'status' => 'Menunggu konfirmasi',
        ]);

        // 2. REDIRECT KE URL KEGIATAN SAYA
        // Menggunakan path internal agar lebih fleksibel jika port/domain berubah
        return redirect()->to('/mahasiswa/kegiatan-saya')->with('success', 'Pendaftaran berhasil dikirim! Silakan cek status konfirmasi kamu di sini.');
    }
    
}
