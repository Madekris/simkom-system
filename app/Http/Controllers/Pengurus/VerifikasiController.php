<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranAnggota;
use App\Models\AnggotaOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    public function index()
    {
        // Ambil data pengurus untuk mengetahui organisasi mana yang dikelola
        $pengurus = AnggotaOrganisasi::where('id_user', Auth::id())->first();

        // Keamanan: Jika user bukan pengurus, hentikan akses
        if (!$pengurus) {
            return abort(403, 'Anda tidak memiliki akses sebagai pengurus organisasi.');
        }

        $id_organisasi = $pengurus->id_organisasi;

        // Ambil data dengan filter yang tepat
        $pendaftarBaru = PendaftaranAnggota::with(['user'])
            ->where('id_organisasi', $id_organisasi)
            ->where('status', 'pending')
            ->get();

        $listAnggota = AnggotaOrganisasi::with(['user'])
            ->where('id_organisasi', $id_organisasi)
            ->where('status', 'aktif')
            ->get();

        $anggotaDiarsipkan = AnggotaOrganisasi::with(['user'])
            ->where('id_organisasi', $id_organisasi)
            ->where('status', 'diarsipkan')
            ->get();

        return view('pages.pengurus.verifikasi_index', compact('pendaftarBaru', 'listAnggota', 'anggotaDiarsipkan'));
    }

    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:aktif,ditolak', // Gunakan 'aktif' bukan 'disetujui'
        ]);

        $pendaftaran = PendaftaranAnggota::findOrFail($id);

        DB::transaction(function () use ($pendaftaran, $request) {
            // 1. Update status pendaftaran
            $pendaftaran->update(['status' => $request->status]);

            // 2. Jika disetujui ('aktif'), masukkan ke tabel anggota
            if ($request->status === 'aktif') {
                // Gunakan updateOrCreate untuk mencegah duplikasi jika tombol ditekan 2x
                AnggotaOrganisasi::updateOrCreate(
                    [
                        'id_user' => $pendaftaran->id_user, 
                        'id_organisasi' => $pendaftaran->id_organisasi
                    ],
                    [
                        'id_periode' => 1, 
                        'jabatan'    => 'Anggota',
                        'status'     => 'aktif',
                    ]
                );
            }
        });

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui!');
    }

    public function updateAnggota(Request $request, $id)
    {
        $request->validate([
            'jabatan' => 'required|string|max:100',
            'status' => 'required|in:aktif,diarsipkan',
        ]);

        $anggota = AnggotaOrganisasi::findOrFail($id);
        $anggota->update([
            'jabatan' => $request->jabatan,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function arsip($id)
    {
        $anggota = AnggotaOrganisasi::findOrFail($id);
        $anggota->update(['status' => 'diarsipkan']);
        return redirect()->back()->with('success', 'Anggota berhasil diarsipkan!');
    }

    public function restore($id)
    {
        $anggota = AnggotaOrganisasi::findOrFail($id);
        $anggota->update(['status' => 'aktif']);
        return redirect()->back()->with('success', 'Anggota berhasil dipulihkan!');
    }
}