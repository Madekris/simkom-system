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
                <svg xmlns="http://www.w3.org/2000/xl" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down w-6 h-6 text-[#EF4444]"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline><polyline points="16 17 22 17 22 11"></polyline></svg>
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
            
            {{-- PERUBAHAN: Container Dropdown Export --}}
            <div class="relative inline-block text-left" id="exportDropdownContainer">
                <button type="button" id="btnExportToggle" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-[#F7F8FC] h-8 rounded-md gap-1.5 px-3 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" x2="12" y1="15" y2="3"></line></svg> 
                    Export
                </button>

                {{-- Menu List Pilihan Format Dokumen --}}
                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-44 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50">
                    <div class="py-1">
                        {{-- Opsi PDF --}}
                        <a href="{{ route('pengurus.keuangan.export', ['id' => $ormawa->first()->id, 'format' => 'pdf']) }}" class="group flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 gap-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M9 15h3a1.5 1.5 0 0 0 0-3H9v6"/><path d="M12 12v3"/></svg>
                            Cetak ke PDF
                        </a>
                        {{-- Opsi Excel --}}
                        <a href="{{ route('pengurus.keuangan.export', ['id' => $ormawa->first()->id, 'format' => 'excel']) }}" class="group flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 gap-2 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 12h4v6"/><path d="M12 15H8"/></svg>
                            Unduh Excel
                        </a>
                    </div>
                </div>
            </div>
            {{-- AKHIR PERUBAHAN --}}

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
                    {{-- 1. Inisialisasi status data dengan penutup @endphp yang benar --}}
                    @php $hasData = false; @endphp

                    @foreach($ormawaWithKeuangan->kegiatan as $ormawaKeuangan)
                        @foreach ( $ormawaKeuangan->keuanganKegiatan as $item)
                            @php $hasData = true; @endphp
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

                    {{-- 2. Desain Fallback jika loop tidak menghasilkan data sama sekali --}}
                    @if(!$hasData)
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-4 bg-gray-50 rounded-full mb-4 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet">
                                            <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-2" />
                                            <path d="M16 11h.01" />
                                        </svg>
                                    </div>
                                    <h3 class="text-base font-bold text-[#1C1E2C] mb-1">Belum Ada Riwayat Transaksi</h3>
                                    <p class="text-sm text-[#6B7280] max-w-sm">
                                        Data transaksi pemasukan ataupun pengeluaran kas belum tercatat pada sistem ini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Skrip Pengendali Interaksi Tampilan Dropdown --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('btnExportToggle');
        const menu = document.getElementById('exportMenu');
        const container = document.getElementById('exportDropdownContainer');

        if (toggleBtn && menu) {
            // Event klik tombol untuk buka/tutup menu
            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });

            // Event klik di luar area komponen untuk menutup menu secara otomatis
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection