<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\PendaftaranPesertaKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PendaftaranPesertaController extends Controller
{
    /**
     * Memproses pendaftaran kegiatan secara instan tanpa verifikasi pengurus
     */
    public function daftar(Request $request)
    {
        $request->validate([
            'id_kegiatan' => 'required|exists:kegiatans,id',
        ]);

        $userId = Auth::id();

        // 1. Validasi awal: Cek apakah user sudah pernah mendaftar (baik diterima maupun ditolak)
        $sudahDaftar = PendaftaranPesertaKegiatan::where('id_kegiatan', $request->id_kegiatan)
            ->where('id_user', $userId)
            ->exists();

        if ($sudahDaftar) {
            return redirect()->back()->with('error', 'Anda sudah melakukan pendaftaran pada kegiatan ini.');
        }

        // Memulai transaksi database agar perhitungan kuota akurat secara real-time
        DB::beginTransaction();
        try {
            // Mengunci baris data kegiatan pilihan untuk menghindari manipulasi kuota simultan
            $kegiatan = Kegiatan::lockForUpdate()->find($request->id_kegiatan);

            // Hitung jumlah peserta yang saat ini sudah berhasil masuk/terdaftar
            $pendaftarDiterima = PendaftaranPesertaKegiatan::where('id_kegiatan', $kegiatan->id)
                ->where('status', 'diterima')
                ->count();

            // 2. Kondisi jika Kuota Penuh -> Status otomatis "ditolak"
            if ($pendaftarDiterima >= $kegiatan->kuota) {
                PendaftaranPesertaKegiatan::create([
                    'id_kegiatan' => $kegiatan->id,
                    'id_user' => $userId,
                    'status' => 'ditolak', // Otomatis ditolak karena kuota habis
                ]);

                DB::commit();
                return redirect()->back()->with('error', 'Pendaftaran gagal. Kuota kegiatan ini sudah penuh!');
            }

            // 3. Kondisi jika Kuota Masih Ada -> Langsung "diterima" tanpa verifikasi pengurus
            PendaftaranPesertaKegiatan::create([
                'id_kegiatan' => $kegiatan->id,
                'id_user' => $userId,
                'status' => 'diterima', // Langsung aktif / terdaftar
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Selamat! Anda berhasil terdaftar pada kegiatan ini.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kegagalan sistem, silakan coba beberapa saat lagi.');
        }
    }
}