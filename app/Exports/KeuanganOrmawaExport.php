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

    // Terima ID Ormawa lewat constructor
    public function __construct($idOrmawa)
    {
        $this->idOrmawa = $idOrmawa;
    }

    /**
     * Ambil data relasi bertingkat dan ratakan (flatten) khusus untuk baris transaksi keuangan
     */
    public function collection()
    {
        $ormawa = Organisasi::with('kegiatan.keuanganKegiatan')->find($this->idOrmawa);
        
        $flatTransactions = collect();

        if ($ormawa) {
            foreach ($ormawa->kegiatan as $kegiatan) {
                foreach ($kegiatan->keuanganKegiatan as $item) {
                    // Masukkan informasi nama kegiatan ke dalam objek keuangan agar bisa diexport per baris
                    $item->nama_kegiatan = $kegiatan->judul_kegiatan;
                    $flatTransactions->push($item);
                }
            }
        }

        return $flatTransactions;
    }

    /**
     * Judul kolom paling atas di file Excel
     */
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

    /**
     * Mapping data fields dari objek ke kolom excel
     */
    public function map($item): array
    {
        return [
            $item->id,
            Carbon::parse($item->created_at)->translatedFormat('j M Y'),
            $item->nama_kegiatan,
            $item->keterangan ?? '-',
            ucfirst($item->jenis_transaksi), // Pemasukan / Pengeluaran
            $item->nominal,
        ];
    }
}
