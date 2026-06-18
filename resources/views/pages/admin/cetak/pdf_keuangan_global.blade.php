<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Keuangan Global Ormawa</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; line-height: 1.3; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1A2B5C; padding-bottom: 8px; }
        .header h2 { margin: 0; color: #1A2B5C; font-size: 15px; text-transform: uppercase; }
        .meta-info { font-size: 9px; font-style: italic; margin-bottom: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #94a3b8; padding: 6px 8px; text-align: left; }
        th { background-color: #1A2B5C; color: white; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Sistem Informasi Monitoring Kegiatan Organisasi Mahasiswa (SIMKOM)</h2>
        <div style="font-size: 11px; margin-top: 3px; color: #475569; font-weight: bold;">Laporan Detail Arus Kas Masuk & Keluar Seluruh Ormawa</div>
    </div>

    <div class="meta-info">
        Dicetak Oleh: Administrator SIMKOM <br>
        Waktu Unduh: {{ now()->translatedFormat('d F Y H:i') }} WITA
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 18%">Nama Organisasi</th>
                <th style="width: 22%">Nama Kegiatan</th>
                <th style="width: 12%" class="text-center">Jenis</th>
                <th style="width: 15%" class="text-right">Nominal (Rp)</th>
                <th style="width: 20%">Keterangan</th>
                <th style="width: 13%" class="text-center">Tanggal Buku</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $grandPemasukan = 0; 
                $grandPengeluaran = 0; 
            @endphp

            @foreach($ormawaWithKeuangan as $ormawa)
                @php $listKegiatan = $ormawa->kegiatan; @endphp
                
                @if($listKegiatan->isEmpty())
                    <tr>
                        <td class="font-bold">{{ $ormawa->nama }}</td>
                        <td colspan="5" style="color: #94a3b8; font-style: italic;">Belum ada riwayat kegiatan</td>
                    </tr>
                @else
                    @foreach($listKegiatan as $kegiatan)
                        @php $listKeuangan = $kegiatan->keuanganKegiatan; @endphp
                        
                        @if($listKeuangan->isEmpty())
                            <tr>
                                <td class="font-bold">{{ $ormawa->nama }}</td>
                                <td>{{ $kegiatan->judul_kegiatan }}</td>
                                <td colspan="4" style="color: #94a3b8; font-style: italic;">Belum ada transaksi keuangan</td>
                            </tr>
                        @else
                            @foreach($listKeuangan as $keuangan)
                                @php
                                    if(strtolower($keuangan->jenis_transaksi) === 'pemasukan') $grandPemasukan += $keuangan->nominal;
                                    if(strtolower($keuangan->jenis_transaksi) === 'pengeluaran') $grandPengeluaran += $keuangan->nominal;
                                @endphp
                                <tr>
                                    <td class="font-bold">{{ $ormawa->nama }}</td>
                                    <td>{{ $kegiatan->judul_kegiatan }}</td>
                                    <td class="text-center">{{ ucfirst($keuangan->jenis_transaksi) }}</td>
                                    <td class="text-right">{{ number_format($keuangan->nominal, 0, ',', '.') }}</td>
                                    <td>{{ $keuangan->keterangan ?? '-' }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($keuangan->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach

            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="3" class="text-right">TOTAL PEMASUKAN GLOBAL :</td>
                <td class="text-right" style="color: #16a34a;">Rp {{ number_format($grandPemasukan, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
            <tr style="background-color: #f1f5f9; font-weight: bold;">
                <td colspan="3" class="text-right">TOTAL PENGELUARAN GLOBAL :</td>
                <td class="text-right" style="color: #dc2626;">Rp {{ number_format($grandPengeluaran, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td colspan="3" class="text-right">SALDO BERSIH GABUNGAN :</td>
                <td class="text-right" style="color: #1A2B5C;">Rp {{ number_format($grandPemasukan - $grandPengeluaran, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

</body>
</html>