<?php

namespace App\Exports;

use App\Models\Organisasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class KeuanganOrmawaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $idOrmawa;
    protected $startDate;
    protected $endDate;

    // Terima ID Ormawa beserta Filter Tanggal lewat constructor
    public function __construct($idOrmawa, $startDate, $endDate)
    {
        $this->idOrmawa = $idOrmawa;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        // Tarik data organisasi dengan filter rentang waktu langsung pada sub-relasi keuanganKegiatan
        $ormawa = Organisasi::with(['kegiatan' => function($query) {
            $query->with(['keuanganKegiatan' => function($q) {
                $q->whereBetween('created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
                  ->latest('created_at');
            }]);
        }])->find($this->idOrmawa);
        
        $flatTransactions = collect();

        if ($ormawa) {
            foreach ($ormawa->kegiatan as $kegiatan) {
                foreach ($kegiatan->keuanganKegiatan as $item) {
                    $item->nama_kegiatan = $kegiatan->judul_kegiatan;
                    $flatTransactions->push($item);
                }
            }
        }

        return $flatTransactions;
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal',
            'Nama Kegiatan',
            'Keterangan Transaksi',
            'Jenis',
            'Nominal (Rp)',
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            Carbon::parse($item->created_at)->translatedFormat('j M Y'),
            $item->nama_kegiatan,
            $item->keterangan ?? '-',
            ucfirst($item->jenis_transaksi),
            $item->nominal,
        ];
    }
}