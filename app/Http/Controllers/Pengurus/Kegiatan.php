<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\DokumenKegiatan;
use App\Models\Kegiatan as ModelsKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Kegiatan extends Controller
{
    protected $table = 'kegiatans';
    
    // TAMBAHKAN BARIS INI: Beritahu Laravel kalau primary key-nya bukan 'id'
    protected $primaryKey = 'id_kegiatan';
    /**
     * Menampilkan daftar semua kegiatan ormawa.
     */
    public function index(Request $request)
    {
        // 1. Ambil data organisasi pengurus yang sedang login
        $anggotaOrganisasi = AnggotaOrganisasi::where('id_user', Auth::id())->first();

        if (!$anggotaOrganisasi) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai pengurus organisasi manapun.');
        }

        // 2. Tangkap parameter filter menggunakan query() (default: 'semua')
        $statusFilter = $request->query('status', 'semua');

        // 3. Query dasar kegiatan berdasarkan organisasi
        $query = ModelsKegiatan::with('dokumenKegiatan')
            ->where('id_organisasi', $anggotaOrganisasi->id_organisasi)
            ->latest();

        // 4. Sesuaikan filter dengan value database
        if ($statusFilter !== 'semua') {
            if ($statusFilter === 'berlangsung') {
                $query->whereIn('status', ['Pending', 'Mendatang', 'pending']);
            } elseif ($statusFilter === 'selesai') {
                $query->where('status', 'Selesai');
            } elseif ($statusFilter === 'dibatalkan') {
                $query->where('status', 'Dibatalkan');
            }
        }

        $kegiatan = $query->get();

        return view('pages.pengurus.kegiatan', compact('kegiatan', 'statusFilter'));
    }

    /**
     * Menampilkan halaman formulir tambah kegiatan.
     */
    public function create()
    {
        // Ambil data organisasi pengurus aktif untuk dilempar ke form (jika dibutuhkan id_organisasi otomatis)
        $anggotaOrganisasi = AnggotaOrganisasi::where('id_user', Auth::id())->first();

        if (!$anggotaOrganisasi) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses organisasi.');
        }

        return view('pages.pengurus.kegiatan-create', compact('anggotaOrganisasi')); 
    }

    /**
     * Menyimpan data kegiatan baru ke database beserta file proposal.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_organisasi'    => 'required|integer',
            'id_periode'       => 'required|integer',
            'judul_kegiatan'   => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tanggal_kegiatan' => 'required|date|after_or_equal:today',
            'waktu_kegiatan'   => 'required',
            'lokasi'           => 'required|string|max:255',
            'kuota_peserta'    => 'required|numeric|min:0', // Proteksi anti-minus
            'proposal'         => 'required|file|mimes:pdf|max:10240',
        ]);

        $status = 'pending';

        $kegiatan = ModelsKegiatan::query()->create([
            'id_organisasi'    => $validated['id_organisasi'],
            'id_periode'       => $validated['id_periode'],
            'judul_kegiatan'   => $validated['judul_kegiatan'],
            'deskripsi'        => $validated['deskripsi'],
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'waktu_kegiatan'   => $validated['waktu_kegiatan'],
            'lokasi'           => $validated['lokasi'],
            'kuota_peserta'    => $validated['kuota_peserta'],
            'status'           => $status,
        ]);

        if ($request->hasFile('proposal')) {
            $file = $request->file('proposal');
            $ekstensi = $file->getClientOriginalExtension();
            $slugJudul = Str::slug($validated['judul_kegiatan']);
            $namaFileUnique = $slugJudul . '-' . time() . '.' . $ekstensi;
            $path = $file->storeAs('proposals', $namaFileUnique, 'public');

            DokumenKegiatan::query()->create([
                'id_kegiatan'   => $kegiatan->id,
                'jenis_dokumen' => 'Proposal',
                'nama_file'     => $namaFileUnique,
                'path_url'      => $path,
            ]);
        }

        return redirect()->route('pengurus.kegiatan.index')->with('success', 'Kegiatan berhasil disimpan');
    }

    /**
     * Menampilkan detail data kegiatan spesifik.
     */
    public function show($id)
    {
        $anggotaOrganisasi = AnggotaOrganisasi::where('id_user', Auth::id())->first();

        if (!$anggotaOrganisasi) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar sebagai pengurus organisasi manapun.');
        }

        $kegiatan = ModelsKegiatan::with('dokumenKegiatan')
            ->where('id_organisasi', $anggotaOrganisasi->id_organisasi)
            ->findOrFail($id);

        return view('pages.pengurus.kegiatan-detail', compact('kegiatan'));
    }

    /**
     * Memperbarui data kegiatan melalui Form Pop-up Edit.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_kegiatan'   => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'lokasi'           => 'required|string|max:255',
            'kuota_peserta'    => 'required|numeric|min:0', // Proteksi backend anti-minus
            'status'           => 'required|in:Pending,Berlangsung,Selesai,Dibatalkan,pending',
            'deskripsi'        => 'required|string',
        ]);

        $kegiatan = ModelsKegiatan::query()->findOrFail($id); 

        $kegiatan->update([
            'judul_kegiatan'   => $request->judul_kegiatan,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'lokasi'           => $request->lokasi,
            'kuota_peserta'    => $request->kuota_peserta,
            'status'           => $request->status,
            'deskripsi'        => $request->deskripsi,
        ]);

        return redirect()->route('pengurus.kegiatan.index')
                        ->with('success', 'Kegiatan berhasil diperbarui!');
    }
}