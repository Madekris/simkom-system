<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan {{ $ormawa->nama ?? 'Ormawa' }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1c1e2c;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #1a2b5c;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            color: #1a2b5c;
            text-transform: uppercase;
            font-size: 18px;
        }
        .header p {
            margin: 4px 0 0 0;
            color: #6b7280;
            font-size: 11px;
        }
        .summary-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .summary-card {
            background-color: #f7f8fc;
            border: 1px solid #e5e7eb;
            padding: 12px;
            width: 31%;
            vertical-align: top;
        }
        .summary-title {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-data th {
            background-color: #f7f8fc;
            color: #6b7280;
            font-weight: bold;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        .table-data td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }
        .badge-masuk {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .badge-keluar {
            background-color: #fee2e2;
            color: #ef4444;
        }
        .text-masuk {
            color: #16a34a;
        }
        .text-keluar {
            color: #ef4444;
        }
        .footer-date {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    @php
        // Kalkulasi ulang total untuk manifest cetak PDF
        $totalPemasukan = 0;
        $totalPengeluaran = 0;
        foreach ($ormawaWithKeuangan->kegiatan as $kegiatan) {
            foreach ($kegiatan->keuanganKegiatan as $item) {
                if ($item->jenis_transaksi === 'pemasukan') {
                    $totalPemasukan += $item->nominal;
                } else {
                    $totalPengeluaran += $item->nominal;
                }
            }
        }
        $saldo = $totalPemasukan - $totalPengeluaran;
    @endphp

    {{-- Header Kop Laporan --}}
    <div class="header">
        <h2>Laporan Keuangan</h2>
        <h2>{{ $ormawaWithKeuangan->nama }}</h2>
        <p>Sistem Informasi Manajemen Komunitas & Organisasi Mahasiswa (SIMKOM)</p>
    </div>

    {{-- Widget Ringkasan Finansial Atas --}}
    <table class="summary-table">
        <tr>
            <td class="summary-card">
                <div class="summary-title">Total Pemasukan</div>
                <div class="summary-value text-masuk">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            </td>
            <td style="width: 3.5%;"></td> {{-- Spacer --}}
            <td class="summary-card">
                <div class="summary-title">Total Pengeluaran</div>
                <div class="summary-value text-keluar">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            </td>
            <td style="width: 3.5%;"></td> {{-- Spacer --}}
            <td class="summary-card" style="border-left: 3px solid #1a2b5c;">
                <div class="summary-title">Saldo Saat Ini</div>
                <div class="summary-value" style="color: #1a2b5c;">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <h3 style="margin-bottom: 8px; font-size: 13px; color: #1a2b5c;">Riwayat Transaksi Buku Kas</h3>
    
    {{-- Tabel Riwayat Transaksi Utama --}}
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 45%;">Keterangan</th>
                <th style="width: 15%; text-align: center;">Jenis</th>
                <th style="width: 20%; text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @php $hasRows = false; @endphp
            @foreach($ormawaWithKeuangan->kegiatan as $kegiatan)
                @foreach($kegiatan->keuanganKegiatan as $item)
                    @php $hasRows = true; @endphp
                    <tr>
                        <td style="color: #6b7280;">
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('j M Y') }}
                        </td>
                        <td style="font-weight: 500;">
                            {{ $item->keterangan }}
                        </td>
                        <td class="text-center">
                            @if($item->jenis_transaksi === 'pemasukan')
                                <span class="badge badge-masuk">Masuk</span>
                            @else
                                <span class="badge badge-keluar">Keluar</span>
                            @endif
                        </td>
                        <td class="text-right" style="font-weight: bold;">
                            <span class="{{ $item->jenis_transaksi === 'pemasukan' ? 'text-masuk' : 'text-keluar' }}">
                                {{ $item->jenis_transaksi === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            @endforeach

            @if(!$hasRows)
                <tr>
                    <td colspan="4" class="text-center" style="padding: 30px; color: #6b7280; font-style: italic;">
                        Tidak ada riwayat catatan data transaksi keuangan.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer-date">
        Dokumen dicetak otomatis oleh sistem pada: {{ now()->translatedFormat('l, j F Y H:i') }} WITA
    </div>

</body>
</html>