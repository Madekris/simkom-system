<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi;
use App\Models\PendaftaranAnggota;
use App\Models\JenisOrganisasi;
use App\Models\Mahasiswa;
use App\Models\PendaftaranPesertaKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index()
    {
        $dataMahasiswa = Mahasiswa::where('id_user', Auth::user()->id)->first();
        $totalOrmawa = AnggotaOrganisasi::where('id_user', Auth::user()->id)->count();

        $semuaKegiatan = PendaftaranPesertaKegiatan::where('id_user', Auth::id());

        $kegiatanDiikuti = PendaftaranPesertaKegiatan::where('id_user', Auth::id())
            ->whereHas('kegiatan', function ($query) {
                // Menyaring pendaftaran yang HANYA memiliki kegiatan berstatus 'ongoing'
                $query->where('status', 'Mendatang');
            })
            ->with(['kegiatan' => function ($query) {
                // Memuat data kegiatannya yang berstatus 'ongoing'
                $query->where('status', 'Mendatang');
            }])
            ->get(); // Jangan lupa tambahkan get() di ujung untuk mengambil datanya

        $totalKegiatanMendatang = $kegiatanDiikuti->count();

        $totalSemuaKegiatanSelesai = $semuaKegiatan->count() - $totalKegiatanMendatang;

        $ormawaMahasiswa = AnggotaOrganisasi::where('id_user', Auth::id())->with('organisasi')->get();

        return view('pages.mahasiswa.dashboard', compact(
            'dataMahasiswa',
            'totalOrmawa',
            'totalKegiatanMendatang',
            'totalSemuaKegiatanSelesai',
            'ormawaMahasiswa'
        ));
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

        $isTerdaftar = PendaftaranAnggota::where('id_user', Auth::id())->whereIn('status', ['aktif', 'pending'])->first();

        if(!$isTerdaftar){
            PendaftaranAnggota::create([
                'id_user' => Auth::id(),
                'id_organisasi' => $request->id_organisasi,
                'status' => 'pending',
                'keterangan' => $request->alasan,
            ]);
        } else {
            return redirect()
                ->route('mahasiswa.organisasi.index')
                ->with('error', 'Anda sudah melakukan pendaftaran di organisasi ini!');
        }

        return redirect()
            ->route('mahasiswa.organisasi.index')
            ->with('success', 'Pendaftaran Anda berhasil dikirim dan sedang diproses!');
    }
}
