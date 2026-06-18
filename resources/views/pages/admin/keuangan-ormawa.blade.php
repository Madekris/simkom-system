@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Keuangan Seluruh Ormawa')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Pantauan keuangan semua ormawa SIMKOM Bali')

{{-- Isi Tombol / Aksi di Sebelah Kanan dengan Dropdown Mode --}}
@section('topbar_actions')
<div class="relative inline-block text-left" id="dropdown-export-wrapper">
    {{-- Tombol Utama Pemicu Dropdown --}}
    <button onclick="toggleExportDropdown()" type="button"
        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all border bg-background text-foreground hover:bg-accent hover:text-accent-foreground dark:bg-input/30 dark:border-input dark:hover:bg-input/50 h-9 px-4 py-2 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]">
        
        {{-- Ikon Download Lucide --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-1">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" x2="12" y1="15" y2="3"></line>
        </svg> 
        
        Export Laporan
        
        {{-- Ikon Panah Bawah Lucide --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down w-4 h-4 ml-0.5">
            <path d="m6 9 6 6 6-6"></path>
        </svg>
    </button>

    {{-- Menu Pilihan Dropdown --}}
    <div id="exportDropdownMenu" class="hidden absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50 focus:outline-none">
        <div class="py-1">
            {{-- Pilihan 1: Excel --}}
            <a href="{{ route('admin.keuangan-ormawa.export', ['format' => 'excel']) }}" 
               class="group flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-3 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel (.xlsx)
            </a>
            
            {{-- Pilihan 2: PDF --}}
            <a href="{{ route('admin.keuangan-ormawa.export', ['format' => 'pdf']) }}" 
               class="group flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4 mr-3 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Export PDF (.pdf)
            </a>
        </div>
    </div>
</div>
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
                    <th class="text-right px-5 py-3 font-semibold">Aksi</th>
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
                        
                        // 5. Ekstrak data nama pembina secara aman
                        $namaPembina = $ormawa->pembina->user->pembina->nama ?? 'Belum diatur';
                    @endphp

                    <tr class="border-t border-[#E5E7EB] hover:bg-[#F7F8FC]/50 transition-colors">
                        {{-- Nama Ormawa --}}
                        <td class="px-5 py-4 font-semibold text-[#1C1E2C]">
                            {{ is_array($ormawa) ? $ormawa['nama'] : $ormawa->nama }}
                        </td>
                        
                        {{-- Nama Pembina --}}
                        <td class="px-5 py-4 text-[#6B7280]">
                            {{ $namaPembina  }}
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
                            <td class="px-5 py-4 text-right font-bold text-[#EF4444]">
                                - Rp {{ number_format(abs($totalSaldo), 0, ',', '.') }}
                            </td>
                        @else
                            {{-- Jika Saldo Nol --}}
                            <td class="px-5 py-4 text-right font-bold text-[#1A2B5C]">
                                Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                            </td>
                        @endif
                        
                        <td class="px-5 py-4 text-right text-[#EF4444] font-semibold">
                            <button onclick="openModal('modalBem{{ $ormawa->id }}')" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none h-8 rounded-md gap-1.5 px-3 text-[#1A2B5C] hover:bg-gray-100 disabled:pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4 mr-1">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg> 
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@foreach($ormawaWithKeuangan as $ormawa)
    <x-modal-detail-keuangan-ormawa 
        :id="'modalBem' . ($ormawa['id'] ?? $loop->iteration)"
        :nama="$ormawa['nama'] ?? 'Tanpa Nama'"
        :pembina="$namaPembina = $ormawa->pembina->user->pembina->nama ?? 'Belum diatur'"
        realisasiPersen="0"
        :riwayat="$ormawa->kegiatan"
    />
@endforeach

@push('scripts')
<script>
    // ==========================================
    // LOGIKA DROPDOWN MENU EXPORT GLOBAL (AKSI UTAMA)
    // ==========================================
    function toggleExportDropdown() {
        const menu = document.getElementById('exportDropdownMenu');
        menu.classList.toggle('hidden');
    }

    // ==========================================
    // LOGIKA DROPDOWN MENU EXPORT PER MODAL DETAIL
    // ==========================================
    function toggleModalExportDropdown(modalId) {
        const menu = document.getElementById('menuExportModal-' + modalId);
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // ==========================================
    // SISTEM PENGAMAN GLOBAL (CLICK OUTSIDE)
    // ==========================================
    window.addEventListener('click', function(e) {
        // 1. Pengaman Dropdown Global (Pojok Kanan Atas)
        const globalWrapper = document.getElementById('dropdown-export-wrapper');
        const globalMenu = document.getElementById('exportDropdownMenu');
        if (globalWrapper && globalMenu && !globalWrapper.contains(e.target)) {
            globalMenu.classList.add('hidden');
        }

        // 2. Pengaman Dropdown Di Dalam Modal Detail (Berdasarkan Pola ID Modal)
        const openModalDropdowns = document.querySelectorAll('[id^="menuExportModal-"]:not(.hidden)');
        openModalDropdowns.forEach(function(menu) {
            const modalId = menu.id.replace('menuExportModal-', '');
            const wrapper = document.getElementById('wrapper-export-modal-' + modalId);
            
            if (wrapper && !wrapper.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    });

    // ==========================================
    // LOGIKA MODAL DETAIL (DRIVER LAMA)
    // ==========================================
    function showDetailOrmawa(nama, pembina, saldo) {
        document.getElementById('modal-title').innerText = nama;
        document.getElementById('modal-pembina').innerText = pembina;
        document.getElementById('modal-saldo').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(saldo);
        openModal('dynamicOrmawaModal');
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');

        // Tambahan: Tutup otomatis dropdown ekspor di dalam modal jika modalnya ditutup
        const menuInsideModal = document.getElementById('menuExportModal-' + id);
        if (menuInsideModal) {
            menuInsideModal.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection