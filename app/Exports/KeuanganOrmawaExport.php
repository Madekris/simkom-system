<?php

namespace App\Exports;

use App\Models\Organisasi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class KeuanganOrmawaExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping, 
    WithStyles, 
    ShouldAutoSize,
    WithColumnFormatting
{
    protected $id_organisasi;
    protected $tanggal_mulai;
    protected $tanggal_selesai;
    protected $nama_ormawa = '';
    private $rowNumber = 0;

    public function __construct($id_organisasi, $tanggal_mulai, $tanggal_selesai)
    {
        $this->id_organisasi = $id_organisasi;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
    }

    /**
     * 1. Mengambil koleksi data transaksi keuangan
     */
    public function collection()
    {
        $ormawaWithKeuangan = Organisasi::with(['kegiatan.keuanganKegiatan' => function($query) {
            if ($this->tanggal_mulai && $this->tanggal_selesai) {
                $query->whereBetween('created_at', [
                    Carbon::parse($this->tanggal_mulai)->startOfDay(), 
                    Carbon::parse($this->tanggal_selesai)->endOfDay()
                ]);
            }
        }])->find($this->id_organisasi);

        $allTransaksi = collect();
        
        if ($ormawaWithKeuangan) {
            $this->nama_ormawa = $ormawaWithKeuangan->nama; // Simpan nama ormawa untuk kop atas
            
            if ($ormawaWithKeuangan->kegiatan) {
                foreach ($ormawaWithKeuangan->kegiatan as $kegiatan) {
                    if ($kegiatan->keuanganKegiatan) {
                        foreach ($kegiatan->keuanganKegiatan as $item) {
                            $allTransaksi->push($item);
                        }
                    }
                }
            }
        }

        // Fallback tarik tanpa filter rentang tanggal jika kosong
        if ($allTransaksi->isEmpty()) {
            $ormawaTotal = Organisasi::with('kegiatan.keuanganKegiatan')->find($this->id_organisasi);
            if ($ormawaTotal && $ormawaTotal->kegiatan) {
                foreach ($ormawaTotal->kegiatan as $kegiatan) {
                    if ($kegiatan->keuanganKegiatan) {
                        foreach ($kegiatan->keuanganKegiatan as $item) {
                            $allTransaksi->push($item);
                        }
                    }
                }
            }
        }

        return $allTransaksi;
    }

    /**
     * 2. Judul Kolom Tabel Utama (Digeser ke Baris ke-5 karena Baris 1-3 dipakai Kop Laporan)
     */
    public function headings(): array
    {
        // Menyusun KOP Informasi Laporan di atas tabel utama
        $periode = 'Semua Periode';
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $periode = Carbon::parse($this->tanggal_mulai)->translatedFormat('j M Y') . ' s/d ' . Carbon::parse($this->tanggal_selesai)->translatedFormat('j M Y');
        }

        return [
            ['LAPORAN REKAPITULASI KEUANGAN'],
            [$this->nama_ormawa ? strtoupper($this->nama_ormawa) : 'ORGANISASI MAHASISWA'],
            ['Periode Jurnal: ' . $periode],
            [''], // Baris ke-4 sebagai Spacer Kosong yang Rapi
            [    // Baris ke-5 baru Header Tabel Utama Anda
                'NO',
                'TANGGAL',
                'KETERANGAN',
                'JENIS TRANSAKSI',
                'NOMINAL (RP)'
            ]
        ];
    }

    /**
     * 3. Mapping data ke kolom tabel masing-masing
     */
    public function map($item): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            Carbon::parse($item->created_at)->translatedFormat('j M Y'),
            $item->keterangan,
            $item->jenis_transaksi === 'pemasukan' ? 'Masuk' : 'Keluar',
            $item->nominal
        ];
    }

    /**
     * 4. Format Khusus Mata Uang Akuntansi untuk Kolom E (Nominal)
     */
    public function columnFormats(): array
    {
        return [
            'E' => '"Rp "#,##0'
        ];
    }

    /**
     * 5. Implementasi Desain Eksklusif Sesuai Standar SIMKOM
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // --- STYLING AREA KOP LAPORAN (Baris 1 s/d 3) ---
        // Merge judul kop dari kolom A sampai E
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');

        // Berikan format teks bold tengah untuk kop
        $sheet->getStyle('A1:A3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '1A2B5C'], // Warna Navy Identitas Utama SIMKOM
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getStyle('A3')->getFont()->setSize(10)->setItalic(true);

        // Atur tinggi baris Kop
        $sheet->getRowDimension(1)->setRowHeight(22);
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(16);
        $sheet->getRowDimension(4)->setRowHeight(12); // Baris pembatas kosong

        // --- STYLING HEADER TABEL UTAMA (Baris ke-5) ---
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Teks Putih
                'size' => 11
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A2B5C'] // Navy Header Background
            ]
        ]);
        $sheet->getRowDimension(5)->setRowHeight(26);

        // --- STYLING DATA TRANSAKSI (Baris ke-6 s/d baris terakhir) ---
        if ($highestRow >= 6) {
            // Berikan alignment per kolom data
            $sheet->getStyle('A6:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B6:B' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D6:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E6:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Pasang border grid tipis pada seluruh tabel data
            $sheet->getStyle('A5:E' . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                ],
            ]);

            // Pewarnaan teks kondisional (Hijau/Merah)
            for ($row = 6; $row <= $highestRow; $row++) {
                $jenis = $sheet->getCell('D' . $row)->getValue();
                if ($jenis === 'Masuk') {
                    $sheet->getStyle('D' . $row . ':E' . $row)->getFont()->getColor()->setRGB('16A34A');
                } else {
                    $sheet->getStyle('D' . $row . ':E' . $row)->getFont()->getColor()->setRGB('EF4444');
                }
                $sheet->getRowDimension($row)->setRowHeight(20);
            }

            // --- TAMBAHAN: MEMBUAT SUMMARY ROW TOTAL DI AKHIR TABEL ---
            $totalRow = $highestRow + 1;
            
            // Satukan kolom keterangan rekap dari A sampai D
            $sheet->mergeCells("A{$totalRow}:D{$totalRow}");
            $sheet->setCellValue("A{$totalRow}", 'SALDO AKHIR SAAT INI');
            
            // Gunakan rumus matematika Excel dinamis SUMIF untuk menghitung (Pemasukan - Pengeluaran)
            // Rumus: SUMIF(Kolom_Jenis, "Masuk", Kolom_Nominal) - SUMIF(Kolom_Jenis, "Keluar", Kolom_Nominal)
            $sheet->setCellValue("E{$totalRow}", "=SUMIF(D6:D{$highestRow},\"Masuk\",E6:E{$highestRow})-SUMIF(D6:D{$highestRow},\"Keluar\",E6:E{$highestRow})");

            // Desain Baris Total Akhir
            $sheet->getStyle("A{$totalRow}:E{$totalRow}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => '1A2B5C']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F3F4F6'] // Abu-abu terang elegan
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'E5E7EB'],
                    ],
                    'bottom' => [
                        'borderStyle' => Border::BORDER_DOUBLE, // Garis dua bawah standar akuntansi
                        'color' => ['rgb' => '1A2B5C'],
                    ]
                ]
            ]);
            
            $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getRowDimension($totalRow)->setRowHeight(24);
        }

        return [];
    }
}