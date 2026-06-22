<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi;
use App\Models\User;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function dashboard()
    {
        $total_ormawa = Organisasi::count();
        $totalAnggota = AnggotaOrganisasi::where('status', 'aktif')->count();

        $kegiatanAktif = Kegiatan::whereIn('status', ['Berlangsung', 'Mendatang'])->count();
        $persetujuanKegiatan = Kegiatan::where('status', 'Pending');

        $ormawaNonaktif = Organisasi::where('status', 'nonaktif')->count();

        $tanggalMulai = Carbon::now()->subMonths(5)->startOfMonth();
        $tanggalAkhir = Carbon::now()->endOfMonth();

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
            'total_ormawa'    => $total_ormawa,
            'total_anggota'   => $totalAnggota,
            'kegiatan_aktif'  => $kegiatanAktif,
            'pending'         => $persetujuanKegiatan->count(),
            'chartData'       => $chartData,
            'labels'          => $labels,
            'ormawa_aktif'    => ($total_ormawa - $ormawaNonaktif),
            'ormawa_nonaktif' => $ormawaNonaktif,
            'kegiatan_pending' => $kegiatanPending,
        ];

        return view('pages.admin.organisasi.dashboardAdmin', $data);
    }

    public function persetujuan(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Mendatang,Berlangsung,Selesai,Dibatalkan',
        ]);

        $kegiatan = Kegiatan::findOrFail($id);
        $status = $validated['status'];

        $kegiatan->status = $status;
        $kegiatan->save();

        activity('manajemen_kegiatan')
            ->performedOn($kegiatan)
            ->causedBy(Auth::user())
            ->log("Kegiatan '{$kegiatan->judul_kegiatan}' diubah statusnya menjadi {$status} oleh Admin");

        return redirect()->back()->with('success', "Status kegiatan '{$kegiatan->judul_kegiatan}' berhasil diperbarui menjadi {$status}.");
    }

    public function setujuiKegiatan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status = 'Berlangsung';
        $kegiatan->save();

        activity('manajemen_kegiatan')
            ->performedOn($kegiatan)
            ->causedBy(Auth::user())
            ->log("Kegiatan '{$kegiatan->judul_kegiatan}' disetujui oleh Admin");

        return back()->with('success', "Kegiatan '{$kegiatan->judul_kegiatan}' berhasil disetujui.");
    }

    public function tolakKegiatan($id)
    {
        $kegiatan = Kegiatan::findOrFail($id);
        $kegiatan->status = 'Dibatalkan';
        $kegiatan->save();

        activity('manajemen_kegiatan')
            ->performedOn($kegiatan)
            ->causedBy(Auth::user())
            ->log("Kegiatan '{$kegiatan->judul_kegiatan}' ditolak oleh Admin");

        return back()->with('success', "Kegiatan '{$kegiatan->judul_kegiatan}' berhasil ditolak.");
    }

    public function index(Request $request)
    {
        $organisasis = Organisasi::with('ketua')->where('status', 'aktif')->get();
        $organisasis_diarsipkan = Organisasi::with('ketua')->where('status', 'diarsipkan')->get();
        $mahasiswas = DB::table('mahasiswas')->get();
        $jenis_organisasis = DB::table('jenis_organisasis')->get();

        $oView = [];
        $idOrg = $request->id;

        if ($idOrg) {
            $oView = Organisasi::with(['jenisOrganisasi', 'ketua.user.mahasiswa', 'pembina.user.pembina', 'anggotaOrganisasi', 'periode', 'kegiatan'])
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

    public function edit($id)
    {
        $organisasis = Organisasi::with('ketua')->get();
        $organisasis_diarsipkan = Organisasi::with('ketua')->where('status', 'diarsipkan')->get();
        $organisasi = Organisasi::findOrFail($id);
        $mahasiswas = DB::table('mahasiswas')->get();
        $jenis_organisasis = DB::table('jenis_organisasis')->get();

        return view('pages.admin.organisasi.index', compact('organisasi', 'organisasis', 'organisasis_diarsipkan', 'mahasiswas', 'jenis_organisasis'));
    }

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

            $periodeAktif = DB::table('periode_kepengurusans')
                ->where('id_organisasi', $id)
                ->where('status_periode', 'aktif')
                ->first();

            if ($periodeAktif) {
                $idPeriode = $periodeAktif->id;
            } else {
                $idPeriode = DB::table('periode_kepengurusans')
                    ->where('id_organisasi', $id)
                    ->latest('id')
                    ->value('id') ?? 1;
            }

            if ($ketuaLama) {
                DB::table('anggota_organisasis')
                    ->where('id_organisasi', $id)
                    ->where('jabatan', 'Ketua')
                    ->update([
                        'id_user'    => $request->mahasiswa_id,
                        'status'     => 'aktif',
                        'id_periode' => $idPeriode,
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('anggota_organisasis')->insert([
                    'id_organisasi' => $id,
                    'id_user'       => $request->mahasiswa_id,
                    'jabatan'       => 'Ketua',
                    'status'        => 'aktif',
                    'id_periode'    => $idPeriode,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        });

        return redirect()->route('admin.organisasi.index')->with('success', 'Data organisasi berhasil diperbarui!');
    }

    public function toggleStatus($id)
    {
        $org = Organisasi::findOrFail($id);
        $org->status = ($org->status == 'aktif') ? 'nonaktif' : 'aktif';
        $org->save();

        return back()->with('success', 'Status organisasi berhasil diubah.');
    }

    public function archive($id)
    {
        $org = Organisasi::findOrFail($id);
        $org->status = 'diarsipkan';
        $org->save();

        return response()->json([
            'success' => true,
            'message' => 'Data ' . $org->nama . ' berhasil diarsipkan.',
        ]);
    }

    public function restoreFromArchive($id)
    {
        $org = Organisasi::findOrFail($id);
        $org->status = 'aktif';
        $org->save();

        return redirect()->route('admin.organisasi.index')->with('success', 'Data organisasi berhasil diaktifkan kembali!');
    }

    public function arsipkan(string $id)
    {
        try {
            $organisasi = Organisasi::findOrFail($id);
            $organisasi->status = 'diarsipkan';
            $organisasi->save();

            return redirect()->back()->with('success', 'Organisasi berhasil diarsipkan kembali.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengarsipkan data: ' . $e->getMessage());
        }
    }

    public function pulihkan(string $id)
    {
        try {
            $organisasi = Organisasi::findOrFail($id);
            $organisasi->status = 'aktif';
            $organisasi->save();

            return redirect()->back()->with('success', 'Organisasi berhasil diaktifkan kembali.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memulihkan data: ' . $e->getMessage());
        }
    }



public function getDokumen($id)
{
    try {
        // 1. Ambil semua kegiatan milik organisasi ini langsung dari tabel 'kegiatans'
        $kegiatans = DB::table('kegiatans')
            ->where('id_organisasi', $id)
            ->get();

        $response = [];

        // 2. Iterasi setiap kegiatan untuk mencari dokumennya
        foreach ($kegiatans as $kegiatan) {
            
            // Ambil semua dokumen dari tabel 'dokumen_kegiatans' berdasarkan id_kegiatan
            $dokumens = DB::table('dokumen_kegiatans')
                ->where('id_kegiatan', $kegiatan->id)
                ->get();

            $daftarDokumen = [];
            foreach ($dokumens as $doc) {
                // Normalisasi tipe untuk warna badge di UI Blade
                $tipeBadge = 'LAPORAN';
                $jenisStr = strtolower($doc->jenis_dokumen);
                if (str_contains($jenisStr, 'proposal')) {
                    $tipeBadge = 'PROPOSAL';
                } elseif (str_contains($jenisStr, 'rab')) {
                    $tipeBadge = 'RAB';
                }

                $daftarDokumen[] = [
                    'nama_file'  => $doc->nama_file,
                    'tipe'       => $tipeBadge,
                    'path_url'   => $doc->path_url,
                    // Format tanggal yang aman dari string database
                    'created_at' => $doc->created_at ? date('d M Y', strtotime($doc->created_at)) : '-'
                ];
            }

            // Masukkan ke response jika kegiatan ini memiliki minimal 1 dokumen
            if (count($daftarDokumen) > 0) {
                
                // Ambil tahun periode secara manual dari tabel periode jika ada relasinya
                $periode = DB::table('periode_kepengurusans')
                    ->where('id', $kegiatan->id_periode)
                    ->first();
                
                $thnMulai = $periode->tahun_mulai ?? '2025';
                $thnSelesai = $periode->tahun_selesai ?? '2026';

                $response[] = [
                    'nama_kegiatan' => $kegiatan->judul_kegiatan,
                    'periode'       => $thnMulai . '/' . $thnSelesai,
                    'dokumen'       => $daftarDokumen
                ];
            }
        }

        // Kembalikan data array murni ke AlpineJS
        return response()->json($response);

    } catch (\Exception $e) {
        // Jika ada kesalahan, log akan mencatat detail spesifiknya
        \Log::error('Gagal memuat dokumen lewat DB Query: ' . $e->getMessage());
        
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ], 500);
    }
}
}
