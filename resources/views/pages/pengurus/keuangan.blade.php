@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Keuangan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Ringkasan & riwayat keuangan ' . ($ormawa->first()->nama ?? 'Ormawa') . ' (read-only)')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 flex flex-col h-full">
   @php
        // 1. Inisialisasi variabel counter keuangan dengan nilai awal 0
        $totalPemasukan = 0;
        $totalPengeluaran = 0;

        // 2. Lakukan kalkulasi data melalui nested loop terlebih dahulu (proses di latar belakang)
        foreach ($ormawaWithKeuangan->kegiatan as $ormawaKeuangan) {
            foreach ($ormawaKeuangan->keuanganKegiatan as $item) {
                if ($item->jenis_transaksi === 'pemasukan') {
                    $totalPemasukan += $item->nominal;
                } else {
                    $totalPengeluaran += $item->nominal;
                }
            }
        }

        // 3. Hitung sisa saldo saat ini
        $saldoSaatIni = $totalPemasukan - $totalPengeluaran;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#DCFCE7] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-6 h-6 text-[#16A34A]"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
            </div>
            <div>
                <div class="text-xs text-[#6B7280]">Total Pemasukan</div>
                <div class="font-bold text-[#16A34A] text-lg">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#FEE2E2] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down w-6 h-6 text-[#EF4444]"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline><polyline points="16 17 22 17 22 11"></polyline></svg>
            </div>
            <div>
                <div class="text-xs text-[#6B7280]">Total Pengeluaran</div>
                <div class="font-bold text-[#EF4444] text-lg">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-[#EFF6FF] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-credit-card w-6 h-6 text-[#1A2B5C]"><rect width="20" height="14" x="2" y="5" rx="2"></rect><line x1="2" x2="22" y1="10" y2="10"></line></svg>
            </div>
            <div>
                <div class="text-xs text-[#6B7280]">Saldo Saat Ini</div>
                <div class="font-bold text-[#1A2B5C] text-lg">Rp {{ number_format($saldoSaatIni, 0, ',', '.') }}</div>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#E5E7EB] flex items-center justify-between">
            <h3 class="font-bold text-[#1C1E2C]">Riwayat Transaksi</h3>
            <a href="{{ route('pengurus.keuangan.export', $ormawa->first()->id) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-[#F7F8FC] h-8 rounded-md gap-1.5 px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" x2="12" y1="15" y2="3"></line></svg> 
                Export
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#F7F8FC] text-[#6B7280]">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold">Tanggal</th>
                        <th class="text-left px-5 py-3 font-semibold">Keterangan</th>
                        <th class="text-left px-5 py-3 font-semibold">Jenis</th>
                        <th class="text-right px-5 py-3 font-semibold">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ormawaWithKeuangan->kegiatan as $ormawaKeuangan)
                        @foreach ( $ormawaKeuangan->keuanganKegiatan as $item)
                            <tr class="border-t border-[#E5E7EB] hover:bg-[#F7F8FC]/50 transition-colors">
                                <td class="px-5 py-4 text-[#6B7280]">
                                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('j M Y') }}
                                </td>
                                
                                <td class="px-5 py-4 text-[#1C1E2C] font-medium">
                                    {{ $item->keterangan }}
                                </td>
                                
                                <td class="px-5 py-4">
                                    @if($item->jenis_transaksi === 'pemasukan')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#16A34A]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-3 h-3"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#EF4444]">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down w-3 h-3"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline><polyline points="16 17 22 17 22 11"></polyline></svg>
                                            Keluar
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-5 py-4 text-right font-semibold {{ $item->jenis_transaksi === 'pemasukan' ? 'text-[#16A34A]' : 'text-[#EF4444]' }}">
                                    {{ $item->jenis_transaksi === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection