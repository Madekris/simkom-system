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


class AdminController extends Controller
{
    // 1. Halaman Dashboard Utama Admin
    public function dashboard()
    {
        $total_ormawa = Organisasi::count();
        $totalAnggota = AnggotaOrganisasi::where('status', 'aktif')->count();

        // dd($totalAnggota);
        $kegiatanAktif = Kegiatan::whereIn('status', ['Belangsung', 'Mendatang'])->count();
        $persetujuanKegiatan = Kegiatan::where('status', 'Pending');
        
        $ormawaNonaktif = Organisasi::where('status', 'nonaktif')->count();

        $tanggalMulai = Carbon::now()->subMonths(5)->startOfMonth(); // Awal bulan dari 6 bulan lalu (Januari 2026)
        $tanggalAkhir = Carbon::now()->endOfMonth();

        // Ambil data kegiatan dari bulan 1 (Jan) sampai 6 (Jun) di tahun berjalan
        $kegiatanPerBulan = Kegiatan::selectRaw("DATE_FORMAT(tanggal_kegiatan, '%Y-%m') as bulan_tahun, COUNT(*) as total")
            ->whereBetween('tanggal_kegiatan', [$tanggalMulai->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')])
            ->groupBy('bulan_tahun')
            ->orderBy('bulan_tahun', 'asc')
            ->pluck('total', 'bulan_tahun')
            ->toArray();

        $labels = [];
        // Menyusun data agar pas dengan 6 indeks baris chart (Jan-Jun) meskipun ada bulan yang kosong (0)
        $chartData = [];
        $berjalan = $tanggalMulai->copy();
        while ($berjalan <= $tanggalAkhir) {
            $key = $berjalan->format('Y-m'); // Format: 2026-01, 2026-02, dst
            
            // Masukkan nama bulan singkat ke dalam array Label Chart (Jan, Feb, Mar...)
            $labels[] = $berjalan->translatedFormat('M'); 
            
            // Masukkan total kegiatan (jika tidak ada kegiatan, otomatis 0)
            $chartData[] = $kegiatanPerBulan[$key] ?? 0;
            
            $berjalan->addMonth(); // Geser ke bulan berikutnya
        }
        // dd($persetujuanKegiatan->get());
        $kegiatanPending = Kegiatan::with('organisasi')->where('status', 'Pending')->get();

        $data = [
            'total_ormawa'       => $total_ormawa,
            'total_anggota'      => $totalAnggota, 
            'kegiatan_aktif'     => $kegiatanAktif,
            'pending'            => $persetujuanKegiatan->count(),
            'chartData'          => $chartData,
            'labels'             => $labels,
            'ormawa_aktif'      => ($total_ormawa - $ormawaNonaktif),
            'ormawa_nonaktif'   => $ormawaNonaktif,
            'kegiatan_pending'  => $kegiatanPending
        ];

        return view('pages.admin.organisasi.dashboardAdmin', $data);
    }

    public function persetujuan(Request $request, string $id)
    {
        $status = $request->input('status'); // Atau $request['status']

        // Melempar argumen dalam bentuk array []
        Kegiatan::where('id', $id)->update([
            'status' => $status
        ]);
        
        return redirect()->back()->with('success', 'Status kegiatan berhasil diperbarui menjadi ' . $status . '!');
    }

    // 2. Menampilkan daftar organisasi (Aktif & Diarsipkan terpisah)
    public function index()
    {
        // 1. Ambil data ormawa yang statusnya AKTIF saja untuk tabel utama
        $organisasis = Organisasi::with('ketua')->where('status', 'aktif')->get();
        
        // 2. Ambil data ormawa yang DIARSIPKAN untuk bagian bawah accordion
        $organisasis_diarsipkan = Organisasi::with('ketua')->where('status', 'diarsipkan')->get();
        
        // 3. Ambil data mahasiswa untuk keperluan modal dropdown
        $mahasiswas = DB::table('mahasiswas')->get(); 
        
        // PERBAIKAN TYPO: Mengubah 'jenis_organisasias' menjadi nama tabel database yang benar
        $jenis_organisasis = DB::table('jenis_organisasis')->get(); 

        // Kirim semua variabel ke view index
        return view('pages.admin.organisasi.index', compact(
            'organisasis', 
            'organisasis_diarsipkan', 
            'mahasiswas', 
            'jenis_organisasis'
        ));
    }

    // 3. Menampilkan form tambah
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

        // PERBAIKAN: Nama tabel dan nama kolom harus sesuai dengan database
// Di dalam DB::transaction pada AdminController.php


        DB::table('anggota_organisasis')->insert([
            'id_organisasi' => $organisasi->id,
            'id_user'       => $request->mahasiswa_id, 
            'jabatan'       => 'Ketua',
            'status'        => 'aktif',
            'id_periode'    => 1, // <--- TAMBAHKAN INI. Sesuaikan dengan ID periode yang sedang aktif
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        });

    return redirect()->route('admin.organisasi.index')->with('success', 'Organisasi baru berhasil ditambahkan!');
}

public function create()
{
    $mahasiswas = \App\Models\Mahasiswa::all(); // Pastikan Model-nya benar
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

            // Ambil ID periode aktif agar dinamis
            // Kode yang benar sesuai database Anda
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
                    'id_periode'    => $idPeriode, // <--- PENTING: Tambahkan ini agar tidak error 1364
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

    // 8. Fitur Mengarsipkan Data (Status berubah jadi 'diarsipkan')

    public function archive($id)
    {
        $org = Organisasi::findOrFail($id);
        $org->status = 'diarsipkan';
        $org->save();

        return response()->json([
            'success' => true,
            'message' => 'Data ' . $org->nama . ' berhasil diarsipkan.'
        ]);
    }

    // 9. Fitur Mengembalikan Data dari Arsip (Opsional)
    public function restoreFromArchive($id)
    {
        $org = Organisasi::findOrFail($id);
        $org->status = 'aktif';
        $org->save();

        return redirect()->route('admin.organisasi.index')->with('success', 'Data organisasi berhasil diaktifkan kembali!');
    }

    // 10. Alias untuk rute arsip (menghubungkan rute 'arsipkan' ke fungsi 'archive')
    public function arsipkan($id)
    {
        try {
            // Find data organisasi
            $organisasi = Organisasi::findOrFail($id);
            
            // Cukup ubah status organisasi menjadi diarsipkan
            // Ini menghindari error 'visi cannot be null' karena tidak memicu proses insert baru
            $organisasi->status = 'diarsipkan';
            $organisasi->save();

            return response()->json([
                'success' => true,
                'message' => 'Organisasi berhasil diarsipkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengarsipkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // 11. Alias untuk rute pulihkan (jika rute web.php memanggil 'pulihkan')
    public function pulihkan(string $id)
    {
        try {
            // Find data organisasi
            $organisasi = Organisasi::findOrFail($id);
            
            // Kembalikan status ke aktif
            $organisasi->status = 'aktif';
            $organisasi->save();

            // Mengembalikan ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Organisasi berhasil diaktifkan kembali.');
            
        } catch (\Exception $e) {
            // Mengembalikan ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }

    /**
     * PERBAIKAN: Mengambil daftar anggota organisasi yang aktif dalam bentuk struktur JSON pembungkus 'data'
     * Mendukung pembacaan Javascript modal agar dropdown tidak menampilkan pesan "Gagal memuat..."
     */
public function getAnggota($id)
    {
        try {
            // 1. Ambil id_user dari tabel anggota_organisasis (NAMA TABEL SUDAH DIPERBAIKI)
            $userIds = DB::table('anggota_organisasis')
                ->where('id_organisasi', (int)$id)
                ->where('status', 'aktif')
                ->pluck('id_user')
                ->toArray();

            // 2. Ambil data mahasiswa berdasarkan array id_user di atas
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