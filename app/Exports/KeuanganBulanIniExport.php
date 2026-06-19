<?php

namespace App\Exports;

use App\Models\Kegiatan;
use App\Models\KeuanganKegiatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KeuanganBulanIniExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private $rowNumber = 0;
    private $totalPemasukan = 0;
    private $totalPengeluaran = 0;

    /**
     * Ambil data transaksi bulan ini berdasarkan organisasi user yang login
     */
    public function collection()
    {
        $idOrganisasi = Auth::user()->anggotaOrganisasi()->first()->id_organisasi;
        $kegiatanOrganisasi = Kegiatan::where('id_organisasi', $idOrganisasi)->get();
        
        return KeuanganKegiatan::whereIn('id_kegiatan', $kegiatanOrganisasi->pluck('id'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Definisikan baris judul / header tabel
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Kegiatan',
            'Jenis Transaksi',
            'Keterangan',
            'Nominal'
        ];
    }

    /**
     * Mapping data untuk tiap kolom & hitung total
     */
    public function map($transaksi): array
    {
        $this->rowNumber++;

        if ($transaksi->jenis_transaksi === 'pemasukan') {
            $this->totalPemasukan += $transaksi->nominal;
        } else {
            $this->totalPengeluaran += $transaksi->nominal;
        }

        return [
            $this->rowNumber,
            Carbon::parse($transaksi->created_at)->translatedFormat('d M Y'),
            $transaksi->kegiatan->nama_kegiatan ?? '-',
            ucfirst($transaksi->jenis_transaksi),
            $transaksi->keterangan,
            $transaksi->nominal, // Biarkan angka murni agar bisa diformat currency di Excel
        ];
    }

    /**
     * Styling Excel (Warna, Font, Border, Alignment, Custom Total Rows)
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->rowNumber + 1; // +1 karena baris pertama (Row 1) adalah Header

        // 1. Format Judul Atas / Informasi Tambahan (Opsional, disisipkan manual di atas jika perlu)
        // Di sini kita langsung fokus mempercantik tabel utama dari Row 1

        // Style untuk Header (Baris 1)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000'],
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F2F2F2'], // Abu-abu elegan senada dengan PDF
            ],
        ]);

        // Berikan tinggi ekstra pada header agar terlihat lega
        $sheet->getRowDimension(1)->setRowHeight(25);

        // 2. Styling Data Rows (Looping setiap baris data)
        for ($i = 2; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20); // Tinggi baris data murni
            
            // Kolom Jenis Transaksi (Kolom D)
            $jenisCell = $sheet->getCell('D' . $i)->getValue();

            // Format alignment default data
            $sheet->getStyle("A{$i}:B{$i}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$i}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$i}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            
            // Format angka menjadi mata uang Rupiah
            $sheet->getStyle("F{$i}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');

            // Beri warna teks khusus pada kolom "Jenis Transaksi" dan "Nominal" agar informatif
            if ($jenisCell === 'Pemasukan') {
                $sheet->getStyle("D{$i}")->getFont()->getColor()->setRGB('155724'); // Teks Hijau Tua
                $sheet->getStyle("F{$i}")->getFont()->getColor()->setRGB('155724');
            } elseif ($jenisCell === 'Pengeluaran') {
                $sheet->getStyle("D{$i}")->getFont()->getColor()->setRGB('721C24'); // Teks Merah Tua
                $sheet->getStyle("F{$i}")->getFont()->getColor()->setRGB('721C24');
            }
        }

        // 3. Tambahkan Baris Total secara Manual di akhir baris data Excel
        $rowTotalMasuk = $lastRow + 1;
        $rowTotalKeluar = $lastRow + 2;
        $rowSaldoAkhir = $lastRow + 3;

        // --- Baris Total Pemasukan ---
        $sheet->mergeCells("A{$rowTotalMasuk}:E{$rowTotalMasuk}");
        $sheet->setCellValue("A{$rowTotalMasuk}", "Total Pemasukan :");
        $sheet->setCellValue("F{$rowTotalMasuk}", $this->totalPemasukan);

        // --- Baris Total Pengeluaran ---
        $sheet->mergeCells("A{$rowTotalKeluar}:E{$rowTotalKeluar}");
        $sheet->setCellValue("A{$rowTotalKeluar}", "Total Pengeluaran :");
        $sheet->setCellValue("F{$rowTotalKeluar}", $this->totalPengeluaran);

        // --- Baris Saldo Akhir ---
        $sheet->mergeCells("A{$rowSaldoAkhir}:E{$rowSaldoAkhir}");
        $sheet->setCellValue("A{$rowSaldoAkhir}", "Saldo Akhir (Surplus/Defisit) :");
        $sheet->setCellValue("F{$rowSaldoAkhir}", $this->totalPemasukan - $this->totalPengeluaran);

        // Styling untuk 3 Baris Total di atas
        $sheet->getStyle("A{$rowTotalMasuk}:F{$rowSaldoAkhir}")->applyFromArray([
            'font' => ['bold' => true],
        ]);
        
        $sheet->getStyle("A{$rowTotalMasuk}:A{$rowSaldoAkhir}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("F{$rowTotalMasuk}:F{$rowSaldoAkhir}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Format Currency untuk Kolom Total F
        $sheet->getStyle("F{$rowTotalMasuk}:F{$rowSaldoAkhir}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');

        // Warna Khusus baris Saldo Akhir (Abu-abu kebiruan soft seperti PDF)
        $sheet->getStyle("A{$rowSaldoAkhir}:F{$rowSaldoAkhir}")->getFill()->applyFromArray([
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E2E8F0'],
        ]);

        // Warnai teks nominal total
        $sheet->getStyle("F{$rowTotalMasuk}")->getFont()->getColor()->setRGB('155724'); // Hijau
        $sheet->getStyle("F{$rowTotalKeluar}")->getFont()->getColor()->setRGB('721C24'); // Merah

        // 4. Berikan Border Tipis untuk semua Cell dari A1 hingga baris Saldo Akhir
        $sheet->getStyle("A1:F{$rowSaldoAkhir}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '999999'],
                ],
            ],
        ]);

        return [];
    }
}