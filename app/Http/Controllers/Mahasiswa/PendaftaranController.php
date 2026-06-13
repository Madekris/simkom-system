<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\PendaftaranAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftaranController extends Controller
{
    public function index()
    {
        $riwayat = PendaftaranAnggota::with('organisasi')
            ->where('id_user', Auth::id())
            ->latest()
            ->get();

        return view('pages.mahasiswa.pendaftaran_riwayat', compact('riwayat'));
    }

    public function create()
    {
        $organisasi = Organisasi::all();
        $user = Auth::user()->load('mahasiswa.programStudi');

        return view('pages.mahasiswa.pendaftaran_form', compact('organisasi', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_organisasi'     => 'required|exists:organisasis,id',
            'dokumen_pendukung' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan'        => 'nullable|string'
        ]);

        $userId = Auth::id();

        // PERBAIKAN: Gunakan 'pending' agar sesuai dengan enum di database
        $isRegistered = PendaftaranAnggota::where('id_user', $userId)
            ->where('id_organisasi', $request->id_organisasi)
            ->where('status', 'pending')
            ->exists();

        if ($isRegistered) {
            return redirect()->back()->withErrors(['id_organisasi' => 'Anda sudah mengirimkan pengajuan untuk organisasi ini.']);
        }

        $pathDoc = null;
        if ($request->hasFile('dokumen_pendukung')) {
            $pathDoc = $request->file('dokumen_pendukung')->store('dokumen_pendaftaran', 'public');
        }

        PendaftaranAnggota::create([
            'id_user'           => $userId,
            'id_organisasi'     => $request->id_organisasi,
            'dokumen_pendukung' => $pathDoc,
            'status'            => 'pending', // Konsisten dengan VerifikasiController
            'keterangan'        => $request->keterangan ?? '-'
        ]);

        return redirect()->route('mahasiswa.pendaftaran.index')->with('success', 'Pendaftaran Anda berhasil dikirim!');
    }
}