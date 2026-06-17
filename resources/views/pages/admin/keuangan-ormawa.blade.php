@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Keuangan Seluruh Ormawa')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Pantauan keuangan semua ormawa SIMKOM Bali')

{{-- Isi Tombol / Aksi di Sebelah Kanan (Opsional) --}}
@section('topbar_actions')
   <a href="{{ route('admin.keuangan-ormawa.export') }}" 
    class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg:not([class*='size-'])]:size-4 shrink-0 [&amp;_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive border bg-background text-foreground hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 h-9 px-4 py-2 has-[&gt;svg]:px-3">
        
        {{-- Ikon Download Lucide --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-1">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" x2="12" y1="15" y2="3"></line>
        </svg> 
        
        Export Excel
    </a>
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-5">

    {{-- ========================================== --}}
    {{-- STATS CARDS SECTIONS                       --}}
    {{-- ========================================== --}}
    @php
        // Inisialisasi akumulasi global untuk seluruh Ormawa
        $globalPemasukan = 0;
        $globalPengeluaran = 0;

        // Lakukan pra-kalkulasi dari data variabel yang dikirim Controller
        foreach ($ormawaWithKeuangan as $ormawa) {
            $listKegiatan = is_array($ormawa) ? ($ormawa['kegiatan'] ?? []) : ($ormawa->kegiatan ?? []);

            foreach ($listKegiatan as $kegiatan) {
                $listKeuangan = is_array($kegiatan) ? ($kegiatan['keuangan_kegiatan'] ?? []) : ($kegiatan->keuanganKegiatan ?? []);
                
                foreach ($listKeuangan as $keuangan) {
                    $jenis = is_array($keuangan) ? $keuangan['jenis_transaksi'] : $keuangan->jenis_transaksi;
                    $nominal = is_array($keuangan) ? $keuangan['nominal'] : $keuangan->nominal;

                    if ($jenis === 'pemasukan') {
                        $globalPemasukan += (float) $nominal;
                    } elseif ($jenis === 'pengeluaran') {
                        $globalPengeluaran += (float) $nominal;
                    }
                }
            }
        }

        // Hitung Saldo Gabungan Akhir Keseluruhan
        $globalSaldo = $globalPemasukan - $globalPengeluaran;
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
        {{-- Card 1: Total Saldo Gabungan --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#1A2B5C] text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet w-5 h-5">
                        <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path>
                        <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-[#6B7280]">Total Saldo Gabungan</div>
                    
                    {{-- Kondisi Warna Dinamis untuk Saldo Gabungan --}}
                    @if($globalSaldo > 0)
                        <div class="text-lg font-bold text-[#22C55E]">
                            + Rp {{ number_format($globalSaldo, 0, ',', '.') }}
                        </div>
                    @elseif($globalSaldo < 0)
                        <div class="text-lg font-bold text-[#EF4444]">
                            - Rp {{ number_format(abs($globalSaldo), 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-lg font-bold text-[#1A2B5C]">
                            Rp {{ number_format($globalSaldo, 0, ',', '.') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card 2: Total Pemasukan --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#22C55E] text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-5 h-5">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                        <polyline points="16 7 22 7 22 13"></polyline>
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-[#6B7280]">Total Pemasukan</div>
                    <div class="text-lg font-bold text-[#22C55E]">
                        Rp {{ number_format($globalPemasukan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Total Pengeluaran --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#EF4444] text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down w-5 h-5">
                        <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                        <polyline points="16 17 22 17 22 11"></polyline>
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-[#6B7280]">Total Pengeluaran</div>
                    <div class="text-lg font-bold text-[#EF4444]">
                        Rp {{ number_format($globalPengeluaran, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- TABLE DATA ORMAWA SECTIONS                 --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-x-auto">
        <table class="w-full text-sm min-w-[760px]">
            <thead class="bg-[#F7F8FC] text-[#6B7280]">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold">Ormawa</th>
                    <th class="text-left px-5 py-3 font-semibold">Pembina</th>
                    <th class="text-right px-5 py-3 font-semibold">Pemasukan</th>
                    <th class="text-right px-5 py-3 font-semibold">Pengeluaran</th>
                    <th class="text-right px-5 py-3 font-semibold">Saldo</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop Utama untuk List Ormawa --}}
                @foreach($ormawaWithKeuangan as $ormawa)
                    @php
                        // 1. Inisialisasi awal nilai keuangan untuk ormawa ini
                        $totalPemasukan = 0;
                        $totalPengeluaran = 0;

                        // 2. Tarik data jika berwujud Object (Eloq) atau Array murni dari dump kamu
                        $listKegiatan = is_array($ormawa) ? ($ormawa['kegiatan'] ?? []) : ($ormawa->kegiatan ?? []);

                        // 3. Iterasi setiap kegiatan untuk menghitung akumulasi kas keuangan
                        foreach ($listKegiatan as $kegiatan) {
                            $listKeuangan = is_array($kegiatan) ? ($kegiatan['keuangan_kegiatan'] ?? []) : ($kegiatan->keuanganKegiatan ?? []);
                            
                            foreach ($listKeuangan as $keuangan) {
                                $jenis = is_array($keuangan) ? $keuangan['jenis_transaksi'] : $keuangan->jenis_transaksi;
                                $nominal = is_array($keuangan) ? $keuangan['nominal'] : $keuangan->nominal;

                                if ($jenis === 'pemasukan') {
                                    $totalPemasukan += (float) $nominal;
                                } elseif ($jenis === 'pengeluaran') {
                                    $totalPengeluaran += (float) $nominal;
                                }
                            }
                        }

                        // 4. Hitung Sisa Saldo Akhir Ormawa
                        $totalSaldo = $totalPemasukan - $totalPengeluaran;
                    @endphp

                    <tr class="border-t border-[#E5E7EB] hover:bg-[#F7F8FC]/50 transition-colors">
                        {{-- Nama Ormawa --}}
                        <td class="px-5 py-4 font-semibold text-[#1C1E2C]">
                            {{ is_array($ormawa) ? $ormawa['nama'] : $ormawa->nama }}
                        </td>
                        
                        {{-- Nama Pembina (Gunakan fallback karena tidak ada di dump data) --}}
                        <td class="px-5 py-4 text-[#6B7280]">
                            {{ is_array($ormawa) ? ($ormawa['pembina'] ?? 'Belum Diatur') : ($ormawa->pembina ?? 'Belum Diatur') }}
                        </td>
                        
                        {{-- Total Pemasukan --}}
                        <td class="px-5 py-4 text-right text-[#22C55E] font-semibold">
                            Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
                        </td>
                        
                        {{-- Total Pengeluaran --}}
                        <td class="px-5 py-4 text-right text-[#EF4444] font-semibold">
                            Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                        </td>
                        
                        {{-- Total Saldo Akhir --}}
                        @if($totalSaldo > 0)
                            {{-- Jika Saldo Positif (Hijau & Indikator +) --}}
                            <td class="px-5 py-4 text-right font-bold text-[#22C55E]">
                                + Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                            </td>
                        @elseif($totalSaldo < 0)
                            {{-- Jika Saldo Negatif (Merah & Indikator -) --}}
                            {{-- Gunakan abs() agar tanda minus bawaan number_format tidak double --}}
                            <td class="px-5 py-4 text-right font-bold text-[#EF4444]">
                                - Rp {{ number_format(abs($totalSaldo), 0, ',', '.') }}
                            </td>
                        @else
                            {{-- Jika Saldo Nol (Warna Netral / Biru Gelap Bawaanmu) --}}
                            <td class="px-5 py-4 text-right font-bold text-[#1A2B5C]">
                                Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection