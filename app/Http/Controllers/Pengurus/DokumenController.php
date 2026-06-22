<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\DokumenKegiatan as DokumenModel;
use App\Models\Kegiatan;

class DokumenController extends Controller
{
    /**
     * Halaman Utama: Daftar Dokumen & Statistik (Strict per Organisasi)
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Ambil data organisasi user dari tabel pivot
        $organisasi = DB::table('anggota_organisasis')
            ->join('organisasis', 'anggota_organisasis.id_organisasi', '=', 'organisasis.id')
            ->where('anggota_organisasis.id_user', $user->id)
            ->select('organisasis.*')
            ->first();

        if (!$organisasi) {
            return redirect()->back()->with('error', 'Akun Anda belum terhubung dengan organisasi mahasiswa mana pun.');
        }

        // 2. Ambil dokumen kegiatan yang berelasi dengan organisasi lewat tabel kegiatans
        $documents = DB::table('dokumen_kegiatans')
            ->join('kegiatans', 'dokumen_kegiatans.id_kegiatan', '=', 'kegiatans.id')
            ->where('kegiatans.id_organisasi', $organisasi->id)
            ->select('dokumen_kegiatans.*')
            ->orderBy('dokumen_kegiatans.created_at', 'desc')
            ->get();

        // 3. Hitung statistik secara fleksibel dari hasil koleksi data
        $countProposal = $documents->filter(function ($item) {
            return stripos($item->jenis_dokumen, 'proposal') !== false;
        })->count();

        $countLpj = $documents->filter(function ($item) {
            return stripos($item->jenis_dokumen, 'lpj') !== false;
        })->count();

        $countKonstitusi = $documents->filter(function ($item) {
            return stripos($item->jenis_dokumen, 'konstitusi') !== false;
        })->count();

        $countDokumentasi = $documents->filter(function ($item) {
            return stripos($item->jenis_dokumen, 'dokumentasi') !== false;
        })->count();

        return view('pages.pengurus.daftar-dokumen', compact(
            'documents',
            'countProposal',
            'countLpj',
            'countKonstitusi',
            'countDokumentasi'
        ));
    }

    /**
     * Halaman Form Form Upload Dokumen (Strict per Organisasi)
     */
    public function create()
    {
        $user = Auth::user();

        // Ambil ID organisasi pengurus yang sedang login
        $organisasi = DB::table('anggota_organisasis')
            ->where('id_user', $user->id)
            ->first();

        // Pengurus hanya boleh memilih kegiatan milik organisasinya sendiri di dropdown select form
        $kegiatans = $organisasi 
            ? Kegiatan::where('id_organisasi', $organisasi->id_organisasi)->get() 
            : collect();

        return view('pages.pengurus.upload-dokumen', compact('kegiatans'));
    }

    /**
     * Memproses Penyimpanan File ke Storage & Database (Menjawab Masalah 404 POST)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kegiatan'   => 'required', 
            'nama_dokumen'  => 'required|string|max:255',
            'jenis_dokumen' => 'required|in:proposal,laporan_kegiatan,lpj_keuangan,notulen,dokumentasi', 
            'berkas'        => 'required|file|mimes:pdf,docx,xlsx,png,jpg,jpeg|max:10240', 
        ]);

        if ($request->hasFile('berkas')) {
            $file = $request->file('berkas');
            $namaAsli = $file->getClientOriginalName();
            
            $namaFileBaru = time() . '_' . str_replace(' ', '_', $namaAsli);
            $path = $file->storeAs('dokumen_kegiatan', $namaFileBaru, 'public');
            $pathUrl = '/storage/' . $path;

            DokumenModel::create([
                'id_kegiatan'   => $request->id_kegiatan,
                'jenis_dokumen' => $request->jenis_dokumen,
                'nama_file'     => $request->nama_dokumen, 
                'path_url'      => $pathUrl,
            ]);

            return redirect()->route('pengurus.dokumen.index')->with('success', 'Dokumen berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Berkas gagal diunggah, periksa kembali inputan Anda.');
    }

    /**
     * Proses Download Dokumen Fisik
     */
    public function download($id)
    {
        $dokumen = DokumenModel::find($id);

        if (!$dokumen) {
            return redirect()->back()->with('error', 'Data dokumen tidak ditemukan di database.');
        }

        $namaFileFisik = basename($dokumen->path_url); 
        $pathFile = 'public/dokumen_kegiatan/' . $namaFileFisik;

        if (!Storage::exists($pathFile)) {
            return redirect()->back()->with('error', 'Gagal mendownload! Berkas fisik tidak ditemukan di folder storage.');
        }

        $ekstensi = pathinfo($namaFileFisik, PATHINFO_EXTENSION);
        $namaUnduhan = str_replace(' ', '_', $dokumen->nama_file) . '.' . $ekstensi;

        return Storage::download($pathFile, $namaUnduhan);
    }
}