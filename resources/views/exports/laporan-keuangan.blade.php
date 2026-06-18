<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 14px; text-align: center;">
                LAPORAN DETAIL TRANSAKSI KEUANGAN SELURUH ORMAWA
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-style: italic;">
                Sistem Informasi Monitoring Kegiatan Organisasi Mahasiswa (SIMKOM)
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 10px;">
                Waktu Unduh: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WITA
            </th>
        </tr>
        <tr></tr> <tr>
            <th style="font-weight: bold; background-color: #1A2B5C; color: #ffffff; border: 1px solid #000000;">Nama Organisasi</th>
            <th style="font-weight: bold; background-color: #1A2B5C; color: #ffffff; border: 1px solid #000000;">Nama Kegiatan</th>
            <th style="font-weight: bold; background-color: #1A2B5C; color: #ffffff; border: 1px solid #000000;">Jenis Transaksi</th>
            <th style="font-weight: bold; background-color: #1A2B5C; color: #ffffff; border: 1px solid #000000; text-align: right;">Nominal (Rp)</th>
            <th style="font-weight: bold; background-color: #1A2B5C; color: #ffffff; border: 1px solid #000000;">Keterangan</th>
            <th style="font-weight: bold; background-color: #1A2B5C; color: #ffffff; border: 1px solid #000000; text-align: center;">Tanggal Transaksi</th>
        </tr>
    </thead>
    <tbody>
        @php 
            // Inisialisasi counter akumulasi kumulatif untuk baris ringkasan paling bawah
            $grandTotalPemasukan = 0;
            $grandTotalPengeluaran = 0;
        @endphp

        @foreach($ormawaWithKeuangan as $ormawa)
            @php $listKegiatan = $ormawa->kegiatan; @endphp
            
            @if($listKegiatan->isEmpty())
                {{-- Jika Ormawa belum punya kegiatan sama sekali --}}
                <tr>
                    <td style="border: 1px solid #cbd5e1;">{{ $ormawa->nama }}</td>
                    <td colspan="5" style="color: #6B7280; font-style: italic; border: 1px solid #cbd5e1;">Belum ada data kegiatan</td>
                </tr>
            @else
                @foreach($listKegiatan as $kegiatan)
                    @php $listKeuangan = $kegiatan->keuanganKegiatan; @endphp
                    
                    @if($listKeuangan->isEmpty())
                        {{-- Jika Kegiatan belum memiliki transaksi keuangan --}}
                        <tr>
                            <td style="border: 1px solid #cbd5e1;">{{ $ormawa->nama }}</td>
                            <td style="border: 1px solid #cbd5e1;">{{ $kegiatan->judul_kegiatan }}</td>
                            <td colspan="4" style="color: #6B7280; font-style: italic; border: 1px solid #cbd5e1;">Belum ada detail transaksi</td>
                        </tr>
                    @else
                        @foreach($listKeuangan as $keuangan)
                            @php 
                                // Pilah nominal ke masing-masing counter akuntansi global
                                $jenis = strtolower($keuangan->jenis_transaksi);
                                if ($jenis === 'pemasukan') {
                                    $grandTotalPemasukan += (float) $keuangan->nominal;
                                } elseif ($jenis === 'pengeluaran') {
                                    $grandTotalPengeluaran += (float) $keuangan->nominal;
                                }
                            @endphp
                            <tr>
                                {{-- Nama Organisasi --}}
                                <td style="border: 1px solid #cbd5e1;">{{ $ormawa->nama }}</td>
                                
                                {{-- Nama Kegiatan --}}
                                <td style="border: 1px solid #cbd5e1;">{{ $kegiatan->judul_kegiatan }}</td>
                                
                                {{-- Detail Transaksi --}}
                                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ ucfirst($keuangan->jenis_transaksi) }}</td>
                                <td style="border: 1px solid #cbd5e1; text-align: right;">{{ (float) $keuangan->nominal }}</td>
                                <td style="border: 1px solid #cbd5e1;">{{ $keuangan->keterangan ?? '-' }}</td>
                                <td style="border: 1px solid #cbd5e1; text-align: center;">{{ \Carbon\Carbon::parse($keuangan->created_at)->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach

        <tr></tr> <tr style="background-color: #F3F4F6; font-weight: bold;">
            <td colspan="3" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL MASUK GLOBAL :</td>
            <td style="border: 1px solid #000000; text-align: right; font-weight: bold; color: #16A34A;">{{ $grandTotalPemasukan }}</td>
            <td colspan="2" style="border: 1px solid #000000; background-color: #F3F4F6;"></td>
        </tr>
        <tr style="background-color: #F3F4F6; font-weight: bold;">
            <td colspan="3" style="border: 1px solid #000000; text-align: right; font-weight: bold;">TOTAL KELUAR GLOBAL :</td>
            <td style="border: 1px solid #000000; text-align: right; font-weight: bold; color: #DC2626;">{{ $grandTotalPengeluaran }}</td>
            <td colspan="2" style="border: 1px solid #000000; background-color: #F3F4F6;"></td>
        </tr>
        <tr style="background-color: #E5E7EB; font-weight: bold;">
            <td colspan="3" style="border: 1px solid #000000; text-align: right; font-weight: bold; font-size: 11px;">SALDO BERSIH GABUNGAN :</td>
            <td style="border: 1px solid #000000; text-align: right; font-weight: bold; font-size: 11px; color: #1A2B5C;">{{ $grandTotalPemasukan - $grandTotalPengeluaran }}</td>
            <td colspan="2" style="border: 1px solid #000000; background-color: #E5E7EB;"></td>
        </tr>
    </tbody>
</table>