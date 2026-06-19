<?php

namespace App\Http\Controllers\Pengurus;

use App\Exports\KeuanganOrmawaExport;
use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan DomPDF di-import
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class Keuangan extends Controller
{
    public function index()
    {
        $idOrmawaUser = AnggotaOrganisasi::where('id_user', Auth::id())
            ->where('status', 'aktif')
            ->value('id_organisasi');

        if (!$idOrmawaUser) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar aktif di organisasi manapun.');
        }

        $ormawa = Organisasi::where('id', $idOrmawaUser)->get();
        $ormawaWithKeuangan = Organisasi::with('kegiatan.keuanganKegiatan')->find($idOrmawaUser);

        return view('pages.pengurus.keuangan', compact(
            'ormawa',
            'ormawaWithKeuangan'
        ));
    }
    
    /**
     * Memproses export dokumen berdasarkan ID Ormawa dan Format (PDF / Excel)
     */
    public function export(Request $request, string $id, string $format) // Ambil instance Request untuk menangkap input jika ada
    {
        // 1. Ambil data organisasi untuk penamaan file & verifikasi keamanan
        $ormawa = Organisasi::findOrFail($id);
        $slugNama = Str::slug($ormawa->nama);
        $timestamp = now()->format('Y-m-d');

        // 2. Logika Pemisahan Format
        if ($format === 'pdf') {
            
            // Ambil data lengkap beserta riwayat keuangannya untuk dilempar ke view PDF
            $ormawaWithKeuangan = Organisasi::with('kegiatan.keuanganKegiatan')->find($id);
            
            // Render ke view khusus PDF
            $pdf = Pdf::loadView('pages.pengurus.cetak.keuangan_pdf', compact('ormawa', 'ormawaWithKeuangan'))
                      ->setPaper('a4', 'portrait');
            
            $fileName = 'laporan-keuangan-' . $slugNama . '-' . $timestamp . '.pdf';
            return $pdf->download($fileName);

        } elseif ($format === 'excel') {
            
            // -- PERBAIKAN DI SINI --
            // Menyiapkan parameter ke-2 dan ke-3 yang dinilai dari request filter tanggal, 
            // atau menggunakan default jangka waktu (contoh: awal tahun ini sampai sekarang) jika kosong.
            $tanggalMulai = $request->input('tanggal_mulai', now()->startOfYear()->format('Y-m-d'));
            $tanggalSelesai = $request->input('tanggal_selesai', now()->format('Y-m-d'));

            $fileName = 'laporan-keuangan-' . $slugNama . '-' . $timestamp . '.xlsx';
            
            // Mengirimkan 3 buah argumen secara berurutan sesuai kebutuhan KeuanganOrmawaExport
            return Excel::download(new KeuanganOrmawaExport($id, $tanggalMulai, $tanggalSelesai), $fileName);
        }

        return redirect()->back()->with('error', 'Format ekspor tidak dikenali.');
    }
}