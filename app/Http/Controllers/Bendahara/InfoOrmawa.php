<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use App\Models\Organisasi;
use App\Models\Pembina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InfoOrmawa extends Controller
{
    public function index ()
    {
        $idOrganisasi = Auth::user()->anggotaOrganisasi()->first()->id_organisasi;
        $organisasi = Organisasi::with('jenisOrganisasi')->findOrFail($idOrganisasi);

        $totalAnggota = AnggotaOrganisasi::where('id_organisasi', $idOrganisasi)->count();

        $ketuaData = AnggotaOrganisasi::with('periode')
            ->where('id_organisasi', $idOrganisasi)
            ->where('jabatan', 'Ketua')
            ->first();

        $ketua = '-';
        if($ketuaData){
            $ketua = Mahasiswa::where('id_user', $ketuaData->id_user)->first();
        }

        $wakilData = AnggotaOrganisasi::with('periode')
            ->where('id_organisasi', $idOrganisasi)
            ->where('jabatan', 'Wakil Ketua')
            ->first();

        $wakil = '-';
        if($wakilData){
            $wakil = Mahasiswa::where('id_user', $wakilData->id_user)->first();
        }

        $sekreData = AnggotaOrganisasi::with('periode')
            ->where('id_organisasi', $idOrganisasi)
            ->where('jabatan', 'Sekretaris')
            ->first();

        $sekre = '-';
        if($sekreData){
            $sekre = Mahasiswa::where('id_user', $sekreData->id_user)->first();
        }

        $bendaharaData = AnggotaOrganisasi::with('periode')
            ->where('id_organisasi', $idOrganisasi)
            ->where('jabatan', 'Bendahara')
            ->first();
        
        $bendahara = '-';
        if($bendaharaData){
            $bendahara = Mahasiswa::where('id_user', $bendaharaData->id_user)->first();
        }


        $pembinaData = AnggotaOrganisasi::where('id_organisasi', $idOrganisasi)
            ->where('jabatan', 'Pembina')
            ->first();

        $pembina = '-';
        if($pembinaData){
            $namaPembina = Pembina::find($pembinaData->id_user);
            if($namaPembina){
                $pembina = $namaPembina->nama;
            }
        }
        

        if ($ketuaData && $ketuaData->periode) {
            // Cara 1: Menggunakan kurung kurawal tunggal di dalam string (Perbaikan typo 'tahun')
            $periode = "{$ketuaData->periode->tahun_mulai} / {$ketuaData->periode->tahun_selesai}";
            
            // Cara 2: Menggunakan konkatenasi titik (.) jika dirasa lebih rapi
            // $periode = $ketuaData->periode->tahunMulai . ' / ' . $ketuaData->periode->tahunSelesai;
        } else {
            // Jaga-jaga (Fallback) jika data ketua atau periode belum di-set di database
            $periode = date('Y') . '/' . (date('Y') + 1);
        }


        $kegiatanOrganisasi = Kegiatan::where('id_organisasi', $idOrganisasi)
            ->with('keuanganKegiatan')
            ->get();

        // dd($kegiatanOrganisasi->toArray());

        return view('pages.bendahara.info-ormawa', compact(
            'organisasi',
            'totalAnggota',
            'ketua',
            'wakil',
            'sekre',
            'bendahara',
            'pembina',
            'periode',
            'kegiatanOrganisasi'
        ));
    }
}
