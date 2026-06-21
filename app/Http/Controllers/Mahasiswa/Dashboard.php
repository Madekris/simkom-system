<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi;
use App\Models\PendaftaranAnggota;
use App\Models\JenisOrganisasi;
use Illuminate\Support\Str;
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

        $myOrganisasi = PendaftaranAnggota::where('id_user', Auth::id())
            ->whereIn('status', ['aktif'])
            ->pluck('id_organisasi') // Mengambil kolom ID nya saja
            ->toArray();

        return view('pages.mahasiswa.organisasi_index', compact('organisasi', 'myOrganisasi'));
    }

    public function show( string $id)
    {
        
        $organisasi = Organisasi::with(['jenisOrganisasi', 'ketua.user.mahasiswa', 'bendahara.user.mahasiswa', 'pembina.user.pembina', 'anggotaOrganisasi', 'periode', 'kegiatan'])
            ->findOrFail($id);

        $ketuaData = Organisasi::with('ketua.user.mahasiswa')->find($id);
        $ketua = [
            'jabatan' => 'Ketua',
            'nama' => $ketuaData->ketua->user->mahasiswa->nama ?? '-'
        ];

        $wakilData = Organisasi::with('wakil.user.mahasiswa')->find($id);
        $wakil = [
            'jabatan' => 'Wakil Ketua',
            'nama' => $wakilData->wakil->user->mahasiswa->nama ?? '-'
        ];

        $sekreData = Organisasi::with('sekre.user.mahasiswa')->find($id);
        $sekre = [
            'jabatan' => 'Sekretaris',
            'nama' => $sekreData->sekre->user->mahasiswa->nama ?? '-'
        ];

        $bendaharaData = Organisasi::with('bendahara.user.mahasiswa')->find($id);
        $bendahara = [
            'jabatan' => 'Bendahara',
            'nama' => $bendaharaData->bendahara->user->mahasiswa->nama ?? '-'
        ];

        $myOrganisasi = PendaftaranAnggota::where('id_user', Auth::id())
            ->whereIn('status', ['aktif'])
            ->pluck('id_organisasi')
            ->toArray();

        // dd($myOrganisasi);

        $pengurusOrmawa = [
            $ketua,
            $wakil,
            $sekre,
            $bendahara
        ];


        // dd($pengurusOrmawa);

        return view('pages.mahasiswa.organisasi_show', compact('organisasi', 'pengurusOrmawa', 'myOrganisasi'));
    }

    public function pendaftaran(string $id)
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
            ->whereIn('status', ['pending', 'aktif'])
            ->get();

        // dd($sudahMendaftar->toArray());

        if ($sudahMendaftar && $sudahMendaftar->isNotEmpty()) {
            return back()
                ->withInput()
                ->with('error', "Anda sudah memiliki pendaftaran aktif untuk organisasi ini. Status: " . Str::title($sudahMendaftar->first()?->status ?? 'Tidak Diketahui'));
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
