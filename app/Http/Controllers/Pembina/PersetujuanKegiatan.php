<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanKegiatan extends Controller
{
    public function index ()
    {

        $anggota = AnggotaOrganisasi::where('id_user', Auth::id())->first();

        if ($anggota) {
            // Jika terdaftar di organisasi, ambil semua kegiatannya
            $kegiatan = Kegiatan::where('id_organisasi', $anggota?->id_organisasi)
                ->where('status', 'Pending')
                ->get();
        } else {
            $kegiatan = collect(); 
        }
        // dd($kegiatan->toArray());
        return view('pages.pembina.persetujuan-kegiatan', compact('kegiatan'));
    }

    public function setStatus (Request $request, string $id)
    {
       $status = $request->input('status'); // atau bisa juga: $request->status;

        // 2. Cari data kegiatan berdasarkan ID, jika tidak ada akan return 404
        $kegiatan = Kegiatan::findOrFail($id);

        // 3. Update kolom status kegiatannya
        $kegiatan->update([
            'status' => $status
        ]);

        // 4. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui!');
    }
}
