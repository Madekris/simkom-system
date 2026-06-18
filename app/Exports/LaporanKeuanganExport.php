<?php

namespace App\Exports;

use App\Models\Organisasi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanKeuanganExport implements FromView, ShouldAutoSize
{
    // Variabel untuk menampung data koleksi ormawa yang akan diekspor
    protected $ormawaData;

    /**
     * Constructor untuk menerima data dinamis dari Controller.
     * Jika parameter dikosongkan, otomatis akan mengambil seluruh data organisasi.
     */
    public function __construct($ormawaData = null)
    {
        $this->ormawaData = $ormawaData;
    }

    /**
     * Lempar data dari database ke dalam view khusus Excel
     */
    public function view(): View
    {
        // Jika $this->ormawaData bernilai null (ekspor global), jalankan query default.
        // Jika ada isinya (ekspor per ormawa), gunakan data yang dikirim dari controller tersebut.
        $dataKeuangan = $this->ormawaData ?? Organisasi::with('kegiatan.keuanganKegiatan')->get();

        return view('exports.laporan-keuangan', [
            'ormawaWithKeuangan' => $dataKeuangan
        ]);
    }
}