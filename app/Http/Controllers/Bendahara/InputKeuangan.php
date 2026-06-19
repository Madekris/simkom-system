<?php

namespace App\Http\Controllers\Bendahara;

use App\Exports\KeuanganBulanIniExport;
use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\KeuanganKegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan facade DomPDF sudah di-import

class InputKeuangan extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 1. Ambil ID organisasi dari user bendahara yang sedang login
        $idOrganisasi = Auth::user()->anggotaOrganisasi()->first()->id_organisasi;

        // 2. Ambil semua kegiatan milik organisasi tersebut beserta relasi keuangannya
        $kegiatanOrganisasi = Kegiatan::where('id_organisasi', $idOrganisasi)
            ->with('keuanganKegiatan')
            ->get();

        // 3. Gabungkan semua data transaksi keuangan dari seluruh kegiatan menjadi satu collection
        $semuaTransaksi = $kegiatanOrganisasi->flatMap(function ($kegiatan) {
            return $kegiatan->keuanganKegiatan;
        });

        // 4. Tangkap ID Kegiatan dari dropdown filter form (jika ada)
        $idKegiatan = $request->input('id_kegiatan');

        // 5. Jalankan filter HANYA JIKA user memilih kegiatan tertentu
        if ($idKegiatan) {
            $semuaTransaksi = $semuaTransaksi->where('id_kegiatan', $idKegiatan); 
        }

        // 6. Urutkan dari transaksi terbaru ke terlama
        $semuaTransaksi = $semuaTransaksi->sortByDesc('created_at');

        // 7. Lempar semua data ke satu view utama
        return view('pages.bendahara.input-keuangan', compact('kegiatanOrganisasi', 'semuaTransaksi'));
    }

    /**
     * Export data keuangan ke format Excel atau PDF berdasarkan parameter rute.
     */
    public function export($format)
    {
        // Mengambil nama bulan saat ini untuk penamaan file otomatis (Contoh: June_2026)
        $namaBulan = Carbon::now()->translatedFormat('F_Y'); 

        if ($format === 'pdf') {
            // 1. Ambil ID organisasi dari user bendahara yang sedang login
            $idOrganisasi = Auth::user()->anggotaOrganisasi()->first()->id_organisasi;

            // 2. Ambil semua kegiatan milik organisasi tersebut
            $kegiatanOrganisasi = Kegiatan::where('id_organisasi', $idOrganisasi)->get();
            
            // 3. Ambil semua transaksi dari kegiatan organisasi tersebut khusus pada bulan dan tahun berjalan
            $semuaTransaksi = KeuanganKegiatan::whereIn('id_kegiatan', $kegiatanOrganisasi->pluck('id'))
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->orderBy('created_at', 'asc') // Diurutkan dari yang terlama ke terbaru agar runtut saat dibaca di PDF
                ->get();

            $namaFile = 'Laporan_Keuangan_' . $namaBulan . '.pdf';

            // 4. Memanggil file view cetak yang baru saja Anda buat
            // Perhatikan path: pages.bendahara.cetak.export-pdf
            $pdf = Pdf::loadView('pages.bendahara.cetak.export-pdf', compact('semuaTransaksi', 'namaBulan'));
            
            // 5. Proses unduh file PDF
            return $pdf->download($namaFile);
        } 
        
        // Default / Jika memilih format excel
        $namaFile = 'Laporan_Keuangan_' . $namaBulan . '.xlsx';
        return Excel::download(new KeuanganBulanIniExport, $namaFile);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_kegiatan'     => 'required',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'nominal'         => 'required|numeric|min:0',
            'keterangan'      => 'nullable|string',
            'bukti'           => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'tanggal'         => 'required|date',
        ], [
            // Pesan Kustom untuk id_kegiatan
            'id_kegiatan.required'     => 'Kegiatan terkait wajib dipilih.',
            'id_kegiatan.exists'       => 'Kegiatan yang dipilih tidak valid atau tidak terdaftar.',

            // Pesan Kustom untuk jenis_transaksi
            'jenis_transaksi.required' => 'Jenis transaksi wajib ditentukan.',
            'jenis_transaksi.in'       => 'Jenis transaksi harus berupa pemasukan atau pengeluaran.',

            // Pesan Kustom untuk nominal
            'nominal.required'         => 'Jumlah nominal uang wajib diisi.',
            'nominal.numeric'          => 'Nominal harus berupa angka murni.',
            'nominal.min'              => 'Nominal tidak boleh bernilai negatif.',

            // Pesan Kustom untuk keterangan
            'keterangan.string'        => 'Format keterangan harus berupa teks.',

            // Pesan Kustom untuk bukti (file)
            'bukti.required'           => 'Bukti transaksi (foto/struk) wajib dilampirkan.',
            'bukti.file'               => 'Berkas yang diunggah harus berupa file valid.',
            'bukti.mimes'              => 'Bukti transaksi harus berformat: JPEG, JPG, PNG, atau PDF.',
            'bukti.max'                => 'Ukuran berkas bukti tidak boleh melebihi 5 MB.',

            // Pesan Kustom untuk tanggal
            'tanggal.required'         => 'Tanggal transaksi wajib diisi.',
            'tanggal.date'             => 'Format tanggal transaksi tidak valid.',
        ]);

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = 'bukti_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke disk 'public' (storage/app/public/assets/bukti_pembayaran)
            $file->storeAs('assets/bukti_pembayaran', $filename, 'public');
            
            // Simpan string ini ke database
            $filePath = 'assets/bukti_pembayaran/' . $filename;
        }

        $createdAtCustom = Carbon::parse($validatedData['tanggal'])->setTimeFrom(now());

        KeuanganKegiatan::create([
            'id_kegiatan'      => $validatedData['id_kegiatan'],
            'jenis_transaksi'  => $validatedData['jenis_transaksi'],
            'nominal'          => $validatedData['nominal'],
            'keterangan'       => $validatedData['keterangan'] ?? '-',
            'bukti_pembayaran' => $filePath,
            
            // Mengesampingkan otomatisasi Laravel dengan input kustom kita
            'created_at'       => $createdAtCustom, 
            'updated_at'       => now(), // updated_at tetap menggunakan waktu sekarang
        ]);

        return redirect()->back()->with('success', 'Transaksi keuangan berhasil dicatat!');
    }
}