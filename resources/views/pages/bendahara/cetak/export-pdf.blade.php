<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - {{ str_replace('_', ' ', $namaBulan) }}</title>
    <style>
        /* Pengaturan Halaman PDF */
        @page {
            margin: 1.5cm;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
        }

        /* Header Style */
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 16pt;
            color: #111;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 10pt;
            color: #555;
        }

        /* Info Periode */
        .periode-info {
            margin-bottom: 15px;
            font-size: 10pt;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f2f2f2;
            color: #000;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 1px solid #666;
            font-size: 10pt;
        }

        td {
            padding: 8px;
            border: 1px solid #999;
            vertical-align: top;
            font-size: 9.5pt;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Badge Jenis Transaksi */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            border-radius: 3px;
        }

        .pemasukan {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .pengeluaran {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

        /* Ringkasan Total */
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        /* Tanda Tangan / Footer */
        .footer-ttd {
            margin-top: 40px;
            float: right;
            text-align: center;
            width: 250px;
        }

        .footer-ttd .space {
            height: 70px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Pertanggungjawaban Keuangan</h2>
        <h2>Organisasi Mahasiswa / Instansi</h2>
        <p>Generated otomatis melalui Sistem Informasi Keuangan Bendahara</p>
    </div>

    <div class="periode-info">
        <strong>Periode Laporan:</strong> {{ str_replace('_', ' ', $namaBulan) }} <br>
        <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
        <strong>Dicetak Oleh:</strong> {{ Auth::user()->name }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Kegiatan</th>
                <th width="12%">Jenis</th>
                <th width="28%">Keterangan</th>
                <th width="20%">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPemasukan = 0;
                $totalPengeluaran = 0;
            @endphp

            @forelse ($semuaTransaksi as $index => $transaksi)
                @php
                    if ($transaksi->jenis_transaksi === 'pemasukan') {
                        $totalPemasukan += $transaksi->nominal;
                    } else {
                        $totalPengeluaran += $transaksi->nominal;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d M Y') }}</td>
                    <td>{{ $transaksi->kegiatan->nama_kegiatan ?? 'Kegiatan Tidak Diketahui' }}</td>
                    <td class="text-center">
                        @if($transaksi->jenis_transaksi === 'pemasukan')
                            <span class="badge pemasukan">Masuk</span>
                        @else
                            <span class="badge pengeluaran">Keluar</span>
                        @endif
                    </td>
                    <td>{{ $transaksi->keterangan }}</td>
                    <td class="text-right">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="color: #888; font-style: italic;">
                        Tidak ada transaksi keuangan pada periode ini.
                    </td>
                </tr>
            @endforelse

            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pemasukan :</td>
                <td class="text-right" style="color: #155724;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>

            <tr class="total-row">
                <td colspan="5" class="text-right">Total Pengeluaran :</td>
                <td class="text-right" style="color: #721c24;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>

            <tr class="total-row" style="background-color: #e2e8f0;">
                <td colspan="5" class="text-right">Saldo Akhir (Surplus/Defisit) :</td>
                <td class="text-right">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer-ttd">
        <p>Mengetahui,</p>
        <p style="margin-top: -10px;">Bendahara Organisasi</p>
        <div class="space"></div>
        <p><strong><u>{{ Auth::user()->name }}</u></strong></p>
        <p style="margin-top: -15px; font-size: 9pt; color: #555;">NIP/ID: {{ Auth::user()->id }}</p>
    </div>

</body>
</html>