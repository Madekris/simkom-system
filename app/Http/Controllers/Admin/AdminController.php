<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi; 
use App\Models\User;       
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // 1. Halaman Dashboard Utama Admin
    public function dashboard()
    {
        $total_ormawa = Organisasi::count();
        $totalAnggota = AnggotaOrganisasi::where('status', 'aktif')->count();
        $kegiatanAktif = Kegiatan::whereIn('status', ['Berlangsung', 'Mendatang'])->count();
        
        $persetujuanKegiatanCount = Kegiatan::where('status', 'Pending')->count();
        
        $ormawaNonaktif = Organisasi::where('status', 'nonaktif')->count();

        $tanggalMulai = Carbon::now()->subMonths(5)->startOfMonth(); 
        $tanggalAkhir = Carbon::now()->endOfMonth();

        // Ambil data kegiatan dari bulan dalam rentang tahun berjalan
        $kegiatanPerBulan = Kegiatan::selectRaw("DATE_FORMAT(tanggal_kegiatan, '%Y-%m') as bulan_tahun, COUNT(*) as total")
            ->whereBetween('tanggal_kegiatan', [$tanggalMulai->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')])
            ->groupBy('bulan_tahun')
            ->orderBy('bulan_tahun', 'asc')
            ->pluck('total', 'bulan_tahun')
            ->toArray();

        $labels = [];
        $chartData = [];
        $berjalan = $tanggalMulai->copy();
        
        while ($berjalan <= $tanggalAkhir) {
            $key = $berjalan->format('Y-m'); 
            $labels[] = $berjalan->translatedFormat('M'); 
            $chartData[] = $kegiatanPerBulan[$key] ?? 0;
            $berjalan->addMonth(); 
        }

        $kegiatanPending = Kegiatan::with('organisasi')->where('status', 'Pending')->get();

        $data = [
            'total_ormawa'      => $total_ormawa,
            'total_anggota'     => $totalAnggota, 
            'kegiatan_aktif'    => $kegiatanAktif,
            'pending'           => $persetujuanKegiatanCount,
            'chartData'         => $chartData,
            'labels'            => $labels,
            'ormawa_aktif'      => ($total_ormawa - $ormawaNonaktif),
            'ormawa_nonaktif'   => $ormawaNonaktif,
            'kegiatan_pending'  => $kegiatanPending
        ];

        return view('pages.admin.organisasi.dashboardAdmin', $data);
    }

    public function persetujuan(Request $request, string $id)
    {
        $status = $request->input('status'); 

        Kegiatan::where('id', $id)->update([
            'status' => $status
        ]);
        
        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui menjadi ' . $status . '!');
    }

    // 2. Menampilkan daftar organisasi (Aktif & Diarsipkan terpisah)
    public function index(Request $request)
    {
        // Eager load 'kegiatan' beserta relasi 'dokumenKegiatans' di dalamnya
        $organisasis = Organisasi::with(['ketua', 'kegiatan.dokumenKegiatans'])
            ->where('status', 'aktif')
            ->get();

        // Format ulang data kegiatan ormawa agar cocok dengan struktur modal AlpineJS di View
        foreach ($organisasis as $o) {
            $formattedKegiatan = [];
            foreach ($o->kegiatan as $k) {
                $dokumen = [];
                
                // Ambil berkas-berkas secara dinamis dari hasil join tabel dokumen_kegiatans
                if ($k->dokumenKegiatans && $k->dokumenKegiatans->count() > 0) {
                    foreach ($k->dokumenKegiatans as $doc) {
                        $dokumen[] = [
                            'id' => $doc->id,
                            'nama_file' => $doc->nama_file,
                            'jenis_dokumen' => $doc->jenis_dokumen, // Berisi value: proposal, rab, lpj_keuangan, dokumentasi
                            'path_url' => $doc->path_url
                        ];
                    }
                }

                $formattedKegiatan[] = [
                    'id' => $k->id,
                    'judul_kegiatan' => $k->judul_kegiatan, // PERBAIKAN: Diubah dari $k->nama ke $k->judul_kegiatan sesuai kolom DB
                    'dokumen_kegiatans' => $dokumen // Dilempar ke modal template loop x-for
                ];
            }
            // Simpan hasil mapping ke atribut baru
            $o->formatted_kegiatans = $formattedKegiatan;
        }

        $organisasis_diarsipkan = Organisasi::with('ketua')->where('status', 'diarsipkan')->get();
        $mahasiswas = DB::table('mahasiswas')->get(); 
        $jenis_organisasis = DB::table('jenis_organisasis')->get(); 

        $oView = [];
        $idOrg = $request->id;

        if ($idOrg) {
            $oView = Organisasi::with(['jenisOrganisasi', 'ketua.user.mahasiswa', 'pembina.user.pembina', 'anggotaOrganisasi', 'periode', 'kegiatan.dokumenKegiatans'])
                ->findOrFail($idOrg);
        }

        return view('pages.admin.organisasi.index', compact(
            'organisasis', 
            'organisasis_diarsipkan', 
            'mahasiswas', 
            'jenis_organisasis',
            'oView'
        ));
    }

    // 3. Menyimpan data organisasi baru
    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_organisasi' => 'required|integer',
            'nama'                => 'required|string|max:255|unique:organisasis,nama',
            'deskripsi'           => 'nullable|string',
            'visi'                => 'nullable|string',
            'misi'                => 'nullable|string',
            'mahasiswa_id'        => 'required|integer', 
            'ad_art'              => 'required|file|mimes:pdf,doc,docx|max:5120', 
        ]);

        $adArtPath = null;
        if ($request->hasFile('ad_art')) {
            $adArtPath = $request->file('ad_art')->store('organisasi/ad_art', 'public');
        }

        DB::transaction(function () use ($request, $adArtPath) {
            $organisasi = Organisasi::create([
                'id_jenis_organisasi' => $request->id_jenis_organisasi,
                'nama'                => $request->nama,
                'deskripsi'           => $request->deskripsi,
                'visi'                => $request->visi,
                'misi'                => $request->misi,
                'ad_art'              => $adArtPath,
                'status'              => 'aktif', 
            ]);

            DB::table('anggota_organisasis')->insert([
                'id_organisasi' => $organisasi->id,
                'id_user'       => $request->mahasiswa_id, 
                'jabatan'       => 'Ketua',
                'status'        => 'aktif',
                'id_periode'    => 1, 
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        });

        return redirect()->route('admin.organisasi.index')->with('success', 'Organisasi baru berhasil ditambahkan!');
    }

    public function create()
    {
        $mahasiswas = \App\Models\Mahasiswa::all(); 
        $jenis_organisasis = \App\Models\JenisOrganisasi::all(); 
        return view('pages.admin.organisasi.create', compact('mahasiswas', 'jenis_organisasis'));
    }

    // 5. Menampilkan form edit
    public function edit($id)
    {
        $organisasis = Organisasi::with('ketua')->get();
        $organisasis_diarsipkan = Organisasi::with('ketua')->where('status', 'diarsipkan')->get();
        $organisasi = Organisasi::findOrFail($id);
        $mahasiswas = DB::table('mahasiswas')->get();
        $jenis_organisasis = DB::table('jenis_organisasis')->get();

        return view('pages.admin.organisasi.index', compact('organisasi', 'organisasis', 'organisasis_diarsipkan', 'mahasiswas', 'jenis_organisasis'));
    }

    // 6. PROSES UPDATE Data Organisasi
    public function update(Request $request, $id)
    {
        $org = Organisasi::findOrFail($id);

        $validatedData = $request->validate([
            'id_jenis_organisasi' => 'required|integer',
            'nama'                => 'required|string|max:255|unique:organisasis,nama,' . $id,
            'status'              => 'required|string|in:aktif,nonaktif,diarsipkan',
            'deskripsi'           => 'nullable|string',
            'visi'                => 'nullable|string',
            'misi'                => 'nullable|string',
            'mahasiswa_id'        => 'required|integer', 
            'ad_art'              => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('ad_art')) {
            if ($org->ad_art) {
                Storage::disk('public')->delete($org->ad_art);
            }
            $validatedData['ad_art'] = $request->file('ad_art')->store('organisasi/ad_art', 'public');
        }

        DB::transaction(function () use ($org, $validatedData, $request, $id) {
            $org->update($validatedData);

            $ketuaLama = DB::table('anggota_organisasis')
                            ->where('id_organisasi', $id)
                            ->where('jabatan', 'Ketua')
                            ->first();

            $idPeriode = \App\Models\PeriodeKepengurusan::where('status_periode', 'aktif')->value('id') ?? 1;

            if ($ketuaLama) {
                DB::table('anggota_organisasis')
                    ->where('id_organisasi', $id)
                    ->where('jabatan', 'Ketua')
                    ->update([
                        'id_user'      => $request->mahasiswa_id,
                        'status'       => 'aktif',
                        'id_periode'   => $idPeriode, 
                        'updated_at'   => now()
                    ]);
            } else {
                DB::table('anggota_organisasis')->insert([
                    'id_organisasi' => $id,
                    'id_user'       => $request->mahasiswa_id,
                    'jabatan'       => 'Ketua',
                    'status'        => 'aktif',
                    'id_periode'    => $idPeriode, 
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);
            }
        });

        return redirect()->route('admin.organisasi.index')->with('success', 'Data organisasi berhasil diperbarui!');
    }

    // 7. Proses ganti status langsung via tombol toggle
    public function toggleStatus($id)
    {
        $org = Organisasi::findOrFail($id);
        $org->status = ($org->status == 'aktif') ? 'nonaktif' : 'aktif';
        $org->save();

        return back()->with('success', 'Status organisasi berhasil diubah.');
    }

    // 8. Fitur Mengarsipkan Data
    public function arsipkan(string $id)
    {
        try {
            $organisasi = Organisasi::findOrFail($id);
            $organisasi->status = 'diarsipkan';
            $organisasi->save();

            return redirect()->back()->with('success', 'Organisasi ' . $organisasi->nama . ' berhasil diarsipkan.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengarsipkan data: ' . $e->getMessage());
        }
    }

    // 9. Fitur Mengembalikan Data dari Arsip
    public function pulihkan(string $id)
    {
        try {
            $organisasi = Organisasi::findOrFail($id);
            $organisasi->status = 'aktif';
            $organisasi->save();

            return redirect()->back()->with('success', 'Organisasi ' . $organisasi->nama . ' berhasil diaktifkan kembali.');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    // 10. Mengambil JSON berkas file internal Ormawa (AJAX)
// 10. Mengambil JSON berkas file internal Ormawa (AJAX)
    public function getDokumen($id)
    {
        $user = auth()->user();

        // Pengecekan kolom 'role' langsung
        $isAdmin = $user->role === 'admin';

        if (!$isAdmin) {
            $isAuthorized = \App\Models\AnggotaOrganisasi::where('id_organisasi', $id)
                ->where('id_user', $user->id)
                ->exists();
                
            if (!$isAuthorized) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
            }
        }

        $kegiatan = \App\Models\Kegiatan::with('dokumenKegiatans')
            ->where('id_organisasi', $id)
            ->orderBy('tanggal_kegiatan', 'desc')
            ->get();

        // Jika kegiatan kosong, kita kembalikan data kosong agar modal tidak error
        if ($kegiatan->isEmpty()) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $daftarKegiatan = [];
        foreach ($kegiatan as $k) {
            $dokumen = [];
            foreach ($k->dokumenKegiatans as $d) {
                // KUNCI PERBAIKAN: Bersihkan substring '/storage/' atau 'storage/' jika sudah ada di database 
                // agar saat digabungkan dengan asset() tidak menghasilkan url ganda (/storage/storage/)
                $cleanPath = ltrim($d->path_url, '/');
                if (strpos($cleanPath, 'storage/') === 0) {
                    $cleanPath = substr($cleanPath, 8); // hapus string 'storage/' di depan
                }

                $dokumen[] = [
                    'id' => $d->id,
                    'nama_file' => $d->nama_file,
                    'jenis_dokumen' => $d->jenis_dokumen, 
                    // Menghasilkan output bersih: storage/dokumen_kegiatan/nama_file.docx
                    'path_url' => 'storage/' . $cleanPath,
                    'tipe' => ucfirst(str_replace('_', ' ', $d->jenis_dokumen)),
                    'url' => asset('storage/' . $cleanPath)
                ];
            }

            $daftarKegiatan[] = [
                'id' => $k->id,
                'nama_kegiatan' => $k->judul_kegiatan,
                'judul_kegiatan' => $k->judul_kegiatan,
                'periode' => \Illuminate\Support\Carbon::parse($k->tanggal_kegiatan)->year,
                'dokumen' => $dokumen,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $daftarKegiatan
        ]);
    }

    // 11. Mengambil API data Anggota Aktif Ormawa tertentu (AJAX)
    public function getAnggota($id)
    {
        try {
            $userIds = DB::table('anggota_organisasis')
                ->where('id_organisasi', (int)$id)
                ->where('status', 'aktif')
                ->pluck('id_user')
                ->toArray();

            $anggota = DB::table('mahasiswas')
                ->whereIn('id_user', $userIds)
                ->select('id_user as id', 'nama', 'nim')
                ->orderBy('nama', 'asc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data'   => $anggota
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
                'data'    => [
                    ['id' => '', 'nama' => 'Error: ' . $e->getMessage(), 'nim' => '']
                ]
            ], 200);
        }
    }
}