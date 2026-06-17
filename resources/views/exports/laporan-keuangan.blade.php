<table>
    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #F7F8FC;">Nama Organisasi</th>
            <th style="font-weight: bold; background-color: #F7F8FC;">Nama Kegiatan</th>
            <th style="font-weight: bold; background-color: #F7F8FC;">Jenis Transaksi</th>
            <th style="font-weight: bold; background-color: #F7F8FC;">Nominal</th>
            <th style="font-weight: bold; background-color: #F7F8FC;">Keterangan</th>
            <th style="font-weight: bold; background-color: #F7F8FC;">Tanggal Transaksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ormawaWithKeuangan as $ormawa)
            @php $listKegiatan = $ormawa->kegiatan; @endphp
            
            @if($listKegiatan->isEmpty())
                {{-- Jika Ormawa belum punya kegiatan sama sekali --}}
                <tr>
                    <td>{{ $ormawa->nama }}</td>
                    <td colspan="5" style="color: #6B7280; italic: true;">Belum ada data kegiatan</td>
                </tr>
            @else
                @foreach($listKegiatan as $kegiatan)
                    @php $listKeuangan = $kegiatan->keuanganKegiatan; @endphp
                    
                    @if($listKeuangan->isEmpty())
                        {{-- Jika Kegiatan belum memiliki transaksi keuangan --}}
                        <tr>
                            <td>{{ $ormawa->nama }}</td>
                            <td>{{ $kegiatan->judul_kegiatan }}</td>
                            <td colspan="4" style="color: #6B7280; italic: true;">Belum ada detail transaksi</td>
                        </tr>
                    @else
                        @foreach($listKeuangan as $keuangan)
                            <tr>
                                {{-- Nama Organisasi --}}
                                <td>{{ $ormawa->nama }}</td>
                                
                                {{-- Nama Kegiatan --}}
                                <td>{{ $kegiatan->judul_kegiatan }}</td>
                                
                                {{-- Detail Transaksi --}}
                                <td>{{ ucfirst($keuangan->jenis_transaksi) }}</td>
                                <td>{{ (float) $keuangan->nominal }}</td>
                                <td>{{ $keuangan->keterangan ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($keuangan->created_at)->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>