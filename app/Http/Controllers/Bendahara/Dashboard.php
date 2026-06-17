<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Organisasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function index () {
        $idOrganisasi = Auth::user()->anggotaOrganisasi()->first()->id_organisasi;

        $organisasi = Organisasi::find($idOrganisasi);

        $kegiatanOrganisasi = Kegiatan::where('id_organisasi', $idOrganisasi)
        ->with('keuanganKegiatan')
        ->get();

        // 3. Leburkan semua data transaksi dari seluruh kegiatan menjadi satu Collection tunggal
        $semuaTransaksi = $kegiatanOrganisasi->flatMap(function ($kegiatan) {
            return $kegiatan->keuanganKegiatan;
        });

        // --- PROSES PERHITUNGAN SALDO & BULANAN ---

        // A. Saldo Saat Ini (Total Masuk - Total Keluar dari awal waktu)
        $totalMasukSemua = $semuaTransaksi->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalKeluarSemua = $semuaTransaksi->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoSaatIni = $totalMasukSemua - $totalKeluarSemua;

        // B. Pemasukan & Pengeluaran Bulan Ini
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        $pemasukanBulanIni = $semuaTransaksi->filter(function ($item) use ($bulanIni, $tahunIni) {
            $tanggal = Carbon::parse($item->created_at);
            return $item->jenis_transaksi === 'pemasukan' && $tanggal->month === $bulanIni && $tanggal->year === $tahunIni;
        })->sum('nominal');

        $pengeluaranBulanIni = $semuaTransaksi->filter(function ($item) use ($bulanIni, $tahunIni) {
            $tanggal = Carbon::parse($item->created_at);
            return $item->jenis_transaksi === 'pengeluaran' && $tanggal->month === $bulanIni && $tanggal->year === $tahunIni;
        })->sum('nominal');

        $bulanLalu = Carbon::now()->subMonth()->month;
        $tahunBulanLalu = Carbon::now()->subMonth()->year;

        $pemasukanBulanLalu = $semuaTransaksi->filter(function ($item) use ($bulanLalu, $tahunBulanLalu) {
            $tanggal = Carbon::parse($item->created_at);
            return $item->jenis_transaksi === 'pemasukan' && $tanggal->month === $bulanLalu && $tanggal->year === $tahunBulanLalu;
        })->sum('nominal');

        $pengeluaranBulanLalu = $semuaTransaksi->filter(function ($item) use ($bulanLalu, $tahunBulanLalu) {
            $tanggal = Carbon::parse($item->created_at);
            return $item->jenis_transaksi === 'pengeluaran' && $tanggal->month === $bulanLalu && $tanggal->year === $tahunBulanLalu;
        })->sum('nominal');

        $saldoBulanLalu = $pemasukanBulanLalu - $pengeluaranBulanLalu;

        // D. Hitung Persentase Tren Saldo
        $trenSaldo = 0;
        if ($saldoBulanLalu > 0) {
            // Rumus kenaikan/penurunan persentase
            $trenSaldo = (($saldoSaatIni - $saldoBulanLalu) / $saldoBulanLalu) * 100;
            // Dibulatkan menjadi 1 angka di belakang koma (misal: 12.5)
            $trenSaldo = round($trenSaldo, 1); 
        } elseif ($saldoBulanLalu == 0 && $saldoSaatIni > 0) {
            // Jika bulan lalu kosong tapi bulan ini ada saldo, otomatis tren naik 100%
            $trenSaldo = 100; 
        }


        // --- PROSES GENERATE DATA GRAFIK 6 BULAN TERAKHIR ---

        $chartLabels = [];
        $chartMasukData = [];
        $chartKeluarData = [];

        // Looping mundur 6 kali (dari 5 bulan lalu sampai bulan ini)
        for ($i = 5; $i >= 0; $i--) {
            // Ambil objek bulan terkait
            $bulanTarget = Carbon::now()->subMonths($i);
            
            // 1. Generate Label Bulan (Format: Jan, Feb, Mar, dst.)
            $chartLabels[] = $bulanTarget->translatedFormat('M');

            $m = $bulanTarget->month;
            $y = $bulanTarget->year;

            // 2. Hitung Total Pemasukan di bulan & tahun target
            $totalMasukBulanIni = $semuaTransaksi->filter(function ($item) use ($m, $y) {
                $tanggal = Carbon::parse($item->created_at);
                return $item->jenis_transaksi === 'pemasukan' && $tanggal->month === $m && $tanggal->year === $y;
            })->sum('nominal');

            // 3. Hitung Total Pengeluaran di bulan & tahun target
            $totalKeluarBulanIni = $semuaTransaksi->filter(function ($item) use ($m, $y) {
                $tanggal = Carbon::parse($item->created_at);
                return $item->jenis_transaksi === 'pengeluaran' && $tanggal->month === $m && $tanggal->year === $y;
            })->sum('nominal');

            // Masukkan hasil perhitungan ke dalam array dataset chart
            $chartMasukData[] = $totalMasukBulanIni;
            $chartKeluarData[] = $totalKeluarBulanIni;
        }

        $transaksiTerbaru = $semuaTransaksi->sortByDesc('created_at')->take(4);
        
        return view('pages.bendahara.dashboard', compact(
            'organisasi',
            'saldoSaatIni',
            'pemasukanBulanIni',
            'pengeluaranBulanIni',
            'trenSaldo',
            'chartLabels',     
            'chartMasukData',  
            'chartKeluarData',
            'transaksiTerbaru'
        ));

    }
}
