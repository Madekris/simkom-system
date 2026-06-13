<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrganisasiController extends Controller
{
    /**
     * Menampilkan form edit profil organisasi sendiri
     */
    public function edit()
    {
        // 1. Cari data ormawa si pengurus yang sedang login
        $pengurus = DB::table('anggota_organisasis')->where('id_user', Auth::id())->first();
        
        if (!$pengurus) {
            abort(403, 'Anda tidak terdaftar sebagai pengurus organisasi manapun.');
        }

        // 2. Ambil data organisasinya
        $organisasi = DB::table('organisasis')->where('id', $pengurus->id_organisasi)->first();

        return view('pages.pengurus.organisasi_edit', compact('organisasi'));
    }

    /**
     * Memproses update data profil dengan sistem pengunci antar-ORMAWA
     */
    public function update(Request $request)
    {
        // 1. Cari identitas organisasi pengurus yang asli dari database
        $pengurus = DB::table('anggota_organisasis')->where('id_user', Auth::id())->first();
        
        if (!$pengurus) {
            abort(403, 'Akses ditolak. Anda bukan pengurus.');
        }

        // 2. KUNCI KEAMANAN: Cek apakah input ID dari form di-manipulasi (di-inspect element)
        // Jika id_organisasi yang mau diubah tidak sama dengan id_organisasi si pengurus, lempar Error 403 Forbidden!
        if ((int)$request->id_organisasi !== (int)$pengurus->id_organisasi) {
            abort(403, 'Tindakan Ilegal! Anda tidak berhak mengubah data organisasi lain.');
        }

        // 3. Validasi Data Form
        $request->validate([
            'deskripsi' => 'nullable|string',
            'visi'      => 'nullable|string',
            'misi'      => 'nullable|string',
            'ad_art'    => 'nullable|string',
        ]);

        // 4. Eksekusi Update (Hanya mengupdate organisasi milik dia sendiri)
        DB::table('organisasis')->where('id', $pengurus->id_organisasi)->update([
            'deskripsi'  => $request->deskripsi,
            'visi'       => $request->visi,
            'misi'       => $request->misi,
            'ad_art'     => $request->ad_art,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Profil organisasi Anda berhasil diperbarui!');
    }

}