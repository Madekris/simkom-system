<?php

namespace App\Exports;

use App\Models\Kegiatan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KegiatanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate, $endDate, $ormawaId;

    public function __withParams($startDate, $endDate, $ormawaId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->ormawaId = $ormawaId;
        return $this;
    }

    public function collection()
    {
        $query = Kegiatan::with('organisasi')
            ->whereBetween('tanggal_kegiatan', [$this->startDate, $this->endDate]);

        if ($this->ormawaId) {
            $query->where('id_organisasi', $this->ormawaId);
        }

        return $query->orderBy('tanggal_kegiatan', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Kegiatan',
            'Organisasi / Ormawa',
            'Tanggal Pelaksanaan',
            'Lokasi',
            'Status',
        ];
    }

    public function map($kegiatan): array
    {
        return [
            $kegiatan->id,
            $kegiatan->judul_kegiatan,
            $kegiatan->organisasi->nama ?? 'Umum',
            \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('d F Y'),
            $kegiatan->lokasi,
            ucfirst($kegiatan->status),
        ];
    }
}