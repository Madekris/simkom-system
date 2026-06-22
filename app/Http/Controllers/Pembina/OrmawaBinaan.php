<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrmawaBinaan extends Controller
{
    public function index (Request $request) {
        $anggota = $request['detail-anggota'];
        $ormawa = AnggotaOrganisasi::where('id_user', Auth::id())
            ->with([
                'organisasi.ketua.user.mahasiswa', 
                
                // Memfilter anggota organisasi agar role user-nya BUKAN pembina
                'organisasi.anggotaOrganisasi' => function ($query) {
                    $query->whereHas('user', function ($subQuery) {
                        $subQuery->where('role', '!=', 'pembina');
                    })->with('user.mahasiswa.programStudi'); // Tetap load relasi ke bawahnya
                },
                
                'organisasi.kegiatan' => function ($query) {
                    // Memfilter status kegiatan
                    $query->whereIn('status', ['Mendatang', 'Berlangsung']);
                }
            ])->get();

        $dAnggota = [];
        if ($anggota) {
           $dAnggota = AnggotaOrganisasi::where('id_user', Auth::id())
            ->where('id_organisasi', $anggota)
            ->with([
                'organisasi.ketua.user.mahasiswa', 
                'organisasi.anggotaOrganisasi.user.mahasiswa.programStudi',
                'organisasi.kegiatan' => function ($query) {
                    $query->whereIn('status', ['Mendatang', 'Berlangsung']);
                }
            ])
            ->first(); // Mengembalikan objek tunggal, bukan collection
        }
        // dd($ormawa->toArray());
        // dd($ormawa->toArray());
        return view('pages.pembina.ormawa-binaan', compact(
            'ormawa',
            'dAnggota'
        ));
    }

    public function setStatus (string $id) {
        $user = AnggotaOrganisasi::where('id_user', $id)->first();
        if ($user) {
            // Logika Toggle:
            // Jika status adalah 'Aktif', ubah menjadi 'Nonaktif',
            // selain itu (jika 'Nonaktif' atau lainnya), ubah menjadi 'Aktif'
            $user->status = ($user->status === 'aktif') ? 'nonaktif' : 'aktif';
            // Simpan perubahan ke database
            $user->save();
            return redirect()->back()->with('success', 'Status anggota berhasil diubah menjadi ' . $user->status);
        }
        return redirect()->back()->with('error', 'Anggota tidak ditemukan.');
    }
}
