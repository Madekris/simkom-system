<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\PendaftaranPesertaKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendaftaranPeserta extends Controller
{
    /**
     * Mengamankan pendaftaran peserta dengan validasi kuota waktu-nyata
     */
    public function daftar(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required|exists:kegiatans,id',
        ]);

        // Proteksi double-register untuk user yang sama pada satu kegiatan
        $sudahDaftar = PendaftaranPesertaKegiatan::where('id_kegiatan', $request->id_kegiatan)
            ->where('id_user', Auth::id())
            ->exists();

        if ($sudahDaftar) {
            return redirect()->back()->with('error', 'Anda sudah mendaftar pada kegiatan ini.');
        }

        // Memulai transaksi database agar pengecekan kuota tidak mengalami tabrakan data (Race Condition)
        DB::beginTransaction();
        try {
            // Ambil data kegiatan dan kunci baris data tersebut sementara proses validasi berlangsung
            $kegiatan = Kegiatan::lockForUpdate()->find($request->id_kegiatan);

            // Hitung pendaftar saat ini yang statusnya valid / disetujui (atau total pendaftar masuk)
            $pendaftarSekarang = PendaftaranPesertaKegiatan::where('id_kegiatan', $kegiatan->id)
                ->whereIn('status', ['menunggu verifikasi', 'diterima'])
                ->count();

            if ($pendaftarSekarang >= $kegiatan->kuota) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Maaf, kuota pendaftaran kegiatan ini sudah penuh.');
            }

            // Simpan pendaftaran dengan status default awal
            PendaftaranPesertaKegiatan::create([
                'id_kegiatan' => $kegiatan->id,
                'id_user' => Auth::id(),
                'status' => 'menunggu verifikasi',
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Pendaftaran berhasil diajukan! Menunggu verifikasi pengurus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem, silakan coba lagi.');
        }
    }

    /**
     * Memperbarui status pendaftaran (Akses: Pengurus / Admin)
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
        ]);

        $pendaftaran = PendaftaranPesertaKegiatan::findOrFail($id);
        $pendaftaran->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran peserta berhasil diperbarui.');
    }
}