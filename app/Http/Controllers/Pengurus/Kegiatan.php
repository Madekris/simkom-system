<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\DokumenKegiatan;
use App\Models\Kegiatan as ModelsKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Kegiatan extends Controller
{
    protected $table = 'kegiatans';
    
    // TAMBAHKAN BARIS INI: Beritahu Laravel kalau primary key-nya bukan 'id'
    protected $primaryKey = 'id_kegiatan';
    public function index ()
    {
        $anggotaOrganisasi = AnggotaOrganisasi::where('id_user', Auth::id())->get();

        $kegiatan = ModelsKegiatan::with('dokumenKegiatan')->where('id_organisasi', $anggotaOrganisasi->first()->id_organisasi)->get();

        // dd($kegiatan->toArray());
        return view('pages.pengurus.kegiatan', compact(
            'kegiatan'
        ));
    }

    public function create () {
        $anggotaOrganisasi = AnggotaOrganisasi::where('id_user', Auth::id())->get();

        // dd($anggotaOrganisasi->toArray());
        return view('pages.pengurus.kegiatan-create', compact(
            'anggotaOrganisasi'
        ));
    }

    public function store(Request $request)
    {
        // 1. Jalankan Validasi Data Masuk
        $validated = $request->validate([
            'id_organisasi'    => 'required|integer',
            'id_periode'       => 'required|integer',
            'judul_kegiatan'   => 'required|string|max:255',
            'deskripsi'        => 'required|string',
            'tanggal_kegiatan' => 'required|date|after_or_equal:today',
            'waktu_kegiatan'   => 'required',
            'lokasi'           => 'required|string|max:255',
            'kuota_peserta'    => 'required|integer|min:1',
            'proposal'         => 'required|file|mimes:pdf|max:10240',
        ], [
            // id_organisasi & id_periode
            'id_organisasi.required'    => 'Organisasi harus ditentukan.',
            'id_organisasi.integer'     => 'ID organisasi tidak valid.',
            'id_periode.required'       => 'Periode aktif harus ditentukan.',
            'id_periode.integer'        => 'ID periode tidak valid.',

            // judul_kegiatan
            'judul_kegiatan.required'   => 'Judul kegiatan wajib diisi.',
            'judul_kegiatan.string'     => 'Judul kegiatan harus berupa teks.',
            'judul_kegiatan.max'        => 'Judul kegiatan tidak boleh lebih dari 255 karakter.',

            // deskripsi
            'deskripsi.required'        => 'Deskripsi kegiatan wajib diisi.',
            'deskripsi.string'          => 'Deskripsi harus berupa teks.',

            // tanggal_kegiatan
            'tanggal_kegiatan.required' => 'Tanggal kegiatan wajib diisi.',
            'tanggal_kegiatan.date'     => 'Format tanggal kegiatan tidak valid.',
            'tanggal_kegiatan.after_or_equal' => 'Tanggal kegiatan tidak boleh lewat dari hari ini.',

            // waktu_kegiatan
            'waktu_kegiatan.required'   => 'Waktu pelaksanaan kegiatan wajib diisi.',

            // lokasi
            'lokasi.required'           => 'Lokasi kegiatan wajib diisi.',
            'lokasi.string'             => 'Lokasi harus berupa teks.',
            'lokasi.max'                => 'Lokasi tidak boleh lebih dari 255 karakter.',

            // kuota_peserta
            'kuota_peserta.required'    => 'Estimasi kuota peserta wajib diisi.',
            'kuota_peserta.integer'     => 'Kuota peserta harus berupa angka.',
            'kuota_peserta.min'         => 'Kuota peserta minimal diisi 1 orang.',

            // proposal
            'proposal.file'             => 'Berkas yang diunggah harus berupa file.',
            'proposal.mimes'            => 'Proposal harus dalam format dokumen PDF.',
            'proposal.max'              => 'Ukuran file proposal maksimal adalah 10 MB.',
        ]);

        // 2. Tentukan status berdasarkan button mana yang diklik
        $status = 'pending';

        // 3. Handle File Upload Proposal (jika ada)
        $proposalPath = null;
        if ($request->hasFile('proposal')) {
            // Menyimpan ke folder storage/app/public/proposal
            $proposalPath = $request->file('proposal')->store('proposal', 'public');
        }

        // 4. Masukkan Data ke Database
        $kegiatan = ModelsKegiatan::create([
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

        $idKegiatanBaru = $kegiatan->id;

        // dd($kegiatan);

        if ($request->hasFile('proposal')) {
            $file = $request->file('proposal');
            
            // 1. Ambil ekstensi asli file (misal: pdf)
            $ekstensi = $file->getClientOriginalExtension();
            
            // 2. Ambil judul kegiatan dari input, lalu ubah menjadi format slug yang aman untuk URL/File
            $slugJudul = \Illuminate\Support\Str::slug($validated['judul_kegiatan']);
            
            // 3. Buat nama unik: judul-kegiatan-timestamp.pdf (Contoh: workshop-ai-2026-1718726400.pdf)
            $namaFileUnique = $slugJudul . '-' . time() . '.' . $ekstensi;
            
            // 4. Simpan ke storage dengan nama unik tersebut ke folder 'proposals' di disk 'public'
            $path = $file->storeAs('proposals', $namaFileUnique, 'public');

            // 5. Insert ke tabel dokumen_kegiatans
            DokumenKegiatan::create([
                'id_kegiatan'   => $idKegiatanBaru,
                'jenis_dokumen' => 'Proposal',
                'nama_file'     => $namaFileUnique, // Menyimpan nama unik yang baru dibuat
                'path_url'      => $path,
            ]);
        }

        // 5. Kembalikan Respon Sukses
        return redirect()->route('pengurus.kegiatan.index') // Sesuaikan dengan route tujuan Anda setelah submit
            ->with('success', 'Kegiatan berhasil disimpan');
    }


}

