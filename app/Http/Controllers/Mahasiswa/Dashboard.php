<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\PendaftaranAnggota;
use App\Models\JenisOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index()
    {
        $stats = [
            'total_ormawa' => Organisasi::count(),
            'ormawa_aktif' => Organisasi::where('status', 'aktif')->count(),
            'kegiatan_aktif' => 0,
            'kegiatan_diikuti' => 0,
            'kegiatan_selesai' => 0,
        ];

        return view('pages.mahasiswa.dashboard', compact('stats'));
    }

    public function organisasi(Request $request)
    {
        $search = $request->query('search');

        $organisasi = Organisasi::with(['jenisOrganisasi', 'ketua.user.mahasiswa', 'pengurus.user.mahasiswa', 'bendahara.user.mahasiswa'])
            ->withCount(['anggotaOrganisasi as anggota_count' => function ($query) {
                $query->where('status', 'aktif');
            }])
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', '%' . $search . '%');
            })
            ->get();

        return view('pages.mahasiswa.organisasi_index', compact('organisasi'));
    }

    public function show($id)
    {
        $organisasi = Organisasi::with(['jenisOrganisasi', 'ketua.user.mahasiswa', 'bendahara.user.mahasiswa'])
            ->findOrFail($id);

        return view('pages.mahasiswa.organisasi_show', compact('organisasi'));
    }

    public function pendaftaran($id)
    {
        $target_organisasi = Organisasi::findOrFail($id);

        return view('pages.mahasiswa.pendaftaran_form', compact('target_organisasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_organisasi' => 'required|exists:organisasis,id',
            'no_whatsapp' => 'required|string|max:20',
            'alasan' => 'required|string|min:10',
            'setuju_adart' => 'required|accepted',
        ]);

        $sudahMendaftar = PendaftaranAnggota::where('id_user', Auth::id())
            ->where('id_organisasi', $request->id_organisasi)
            ->whereIn('status', ['pending', 'disetujui'])
            ->exists();

        if ($sudahMendaftar) {
            return back()
                ->withInput()
                ->with('error', 'Anda sudah memiliki pendaftaran aktif untuk organisasi ini.');
        }

        Auth::user()->update([
            'no_telepon' => $request->no_whatsapp,
        ]);

        PendaftaranAnggota::create([
            'id_user' => Auth::id(),
            'id_organisasi' => $request->id_organisasi,
            'status' => 'pending',
            'keterangan' => $request->alasan,
        ]);

        return redirect()
            ->route('mahasiswa.organisasi.index')
            ->with('success', 'Pendaftaran Anda berhasil dikirim dan sedang diproses!');
    }
}
