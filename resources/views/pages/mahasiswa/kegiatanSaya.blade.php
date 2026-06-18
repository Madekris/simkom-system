@extends('layouts.app')

@section('title', 'Kegiatan Saya')
@section('role_label', 'Mahasiswa')
@section('topbar_title', 'Kegiatan Saya')
@section('topbar_subtitle', 'Riwayat dan kegiatan mendatang')

@section('content')
{{-- Inisialisasi State AlpineJS untuk Pencarian, Filter Tahun, dan Modal Detail --}}
{{-- GANTI DARI BARIS AWAL X-DATA INI --}}
<div class="p-4 sm:p-6 lg:p-8 w-full max-w-full flex flex-col justify-start items-stretch box-border gap-8"
     x-data="{ 
        searchQuery: '',
        selectedYear: '',
        modalOpen: false, 
        loading: false,
        detail: {},
        
        // Perbaikan Inisialisasi Data Mendatang (Pakai Typecast String & Trim)
        kegiatanMendatang: {{ json_encode($kegiatanMendatang->map(function($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul_kegiatan,
                'ormawa' => $item->organisasi->nama ?? 'Umum',
                'tanggal_raw' => $item->tanggal_kegiatan,
                'tanggal_indo' => \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('d M Y'),
                'tahun' => trim((string)\Carbon\Carbon::parse($item->tanggal_kegiatan)->format('Y')),
                'status' => $item->status
            ];
        })) }},

        // Perbaikan Inisialisasi Data Riwayat (Pakai Typecast String & Trim)
        riwayatKegiatan: {{ json_encode($riwayatKegiatan->map(function($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul_kegiatan,
                'ormawa' => $item->organisasi->nama ?? 'Umum',
                'tanggal_raw' => $item->tanggal_kegiatan,
                'tanggal_indo' => \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('d M Y'),
                'tahun' => trim((string)\Carbon\Carbon::parse($item->tanggal_kegiatan)->format('Y')),
                'status' => $item->status
            ];
        })) }},

        // Fungsi Filter yang Jauh Lebih Aman & Longgar
        get filteredMendatang() {
            return this.kegiatanMendatang.filter(item => {
                const matchSearch = item.judul.toLowerCase().includes(this.searchQuery.toLowerCase().trim());
                
                // Jika dropdown 'Semua Tahun' (kosong), langsung loloskan tanpa cek tahun
                const matchYear = this.selectedYear === '' || 
                                  this.selectedYear === null || 
                                  this.selectedYear === undefined || 
                                  String(item.tahun) === String(this.selectedYear).trim();
                return matchSearch && matchYear;
            });
        },

        get filteredRiwayat() {
            return this.riwayatKegiatan.filter(item => {
                const matchSearch = item.judul.toLowerCase().includes(this.searchQuery.toLowerCase().trim());
                
                // Jika dropdown 'Semua Tahun' (kosong), langsung loloskan tanpa cek tahun
                const matchYear = this.selectedYear === '' || 
                                  this.selectedYear === null || 
                                  this.selectedYear === undefined || 
                                  String(item.tahun) === String(this.selectedYear).trim();
                return matchSearch && matchYear;
            });
        },

        openDetail(id) {
            this.modalOpen = true;
            this.loading = true;
            fetch(`/mahasiswa/kegiatan-saya/${id}`)
                .then(res => res.json())
                .then(data => {
                    this.detail = data;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                });
        }
     }">

    {{-- ── BAR BARU: UTILITY FILTER PENCARIAN ── --}}
    <div class="bg-white p-4 rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.02)] flex flex-col sm:flex-row items-center justify-between gap-4">
        {{-- Input Cari Nama Kegiatan --}}
        <div class="relative w-full sm:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </span>
            <input type="text" 
                   x-model="searchQuery"
                   placeholder="Cari nama kegiatan anda disini..." 
                   class="w-full pl-10 pr-4 py-2 text-sm bg-[#F7F8FC] border border-[#E5E7EB] rounded-lg focus:outline-none focus:border-[#1A2B5C] focus:bg-white text-[#1C1E2C] transition-all">
        </div>

        {{-- Dropdown Filter Tahun Kegiatan --}}
        <div class="w-full sm:w-48 flex items-center gap-2">
            <span class="text-xs font-semibold text-[#6B7280] whitespace-nowrap">Tahun:</span>
            <select x-model="selectedYear" 
                    class="w-full px-3 py-2 text-sm bg-[#F7F8FC] border border-[#E5E7EB] rounded-lg focus:outline-none focus:border-[#1A2B5C] focus:bg-white text-[#1C1E2C] transition-all">
                <option value="">Semua Tahun</option>
                @foreach($daftarTahun as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
    </div>


    {{-- ── BAGIAN 1: KEGIATAN MENDATANG ── --}}
    <div class="flex flex-col gap-3">
        <h2 class="text-base font-bold text-[#1C1E2C] flex items-center gap-2 px-1">
            <span class="w-2 h-4 bg-[#1A2B5C] rounded-full"></span>
            Kegiatan Mendatang (<span x-text="filteredMendatang.length"></span>)
        </h2>
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] w-full overflow-hidden block">
            <div class="w-full overflow-x-auto scrollbar-thin">
                <table class="w-full min-w-[900px] text-sm text-left border-collapse align-middle table-fixed">
                    <thead class="bg-[#F7F8FC] text-[#6B7280] select-none">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold w-[35%]">Nama Kegiatan</th>
                            <th class="px-6 py-3.5 font-semibold w-[35%]">Ormawa</th>
                            <th class="px-6 py-3.5 font-semibold w-[12%]">Tanggal</th>
                            <th class="px-6 py-3.5 font-semibold w-[10%]">Status</th>
                            <th class="px-6 py-3.5 font-semibold text-right w-[8%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        {{-- Render Data Menggunakan Alpine JS template loop --}}
                        <template x-for="item in filteredMendatang" :key="item.id">
                            <tr class="border-t border-[#E5E7EB] hover:bg-[#F7F8FC]/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-[#1C1E2C] break-words" x-text="item.judul"></td>
                                <td class="px-6 py-4 text-[#6B7280] break-words" x-text="item.ormawa"></td>
                                <td class="px-6 py-4 text-[#6B7280] whitespace-nowrap" x-text="item.tanggal_indo"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                                        Akan Datang
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button @click="openDetail(item.id)" class="inline-flex items-center justify-center text-sm font-medium transition-all h-8 rounded-md gap-1.5 px-3 text-[#1A2B5C] hover:bg-gray-100/80">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </template>
                        
                        {{-- State Jika Hasil Filter Kosong --}}
                        <tr x-show="filteredMendatang.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-[#6B7280]">
                                <span class="text-sm font-medium">Kegiatan mendatang tidak ditemukan berdasarkan pencarian.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ── BAGIAN 2: RIWAYAT KEGIATAN ── --}}
    <div class="flex flex-col gap-3">
        <h2 class="text-base font-bold text-[#1C1E2C] flex items-center gap-2 px-1">
            <span class="w-2 h-4 bg-[#1A2B5C] rounded-full"></span>
            Riwayat Kegiatan (<span x-text="filteredRiwayat.length"></span>)
        </h2>
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] w-full overflow-hidden block">
            <div class="w-full overflow-x-auto scrollbar-thin">
                <table class="w-full min-w-[900px] text-sm text-left border-collapse align-middle table-fixed">
                    <thead class="bg-[#F7F8FC] text-[#6B7280] select-none">
                        <tr>
                            <th class="px-6 py-3.5 font-semibold w-[35%]">Nama Kegiatan</th>
                            <th class="px-6 py-3.5 font-semibold w-[35%]">Ormawa</th>
                            <th class="px-6 py-3.5 font-semibold w-[12%]">Tanggal</th>
                            <th class="px-6 py-3.5 font-semibold w-[10%]">Status</th>
                            <th class="px-6 py-3.5 font-semibold text-right w-[8%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E7EB]">
                        <template x-for="item in filteredRiwayat" :key="item.id">
                            <tr class="border-t border-[#E5E7EB] hover:bg-[#F7F8FC]/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-[#1C1E2C] break-words" x-text="item.judul"></td>
                                <td class="px-6 py-4 text-[#6B7280] break-words" x-text="item.ormawa"></td>
                                <td class="px-6 py-4 text-[#6B7280] whitespace-nowrap" x-text="item.tanggal_indo"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <template x-if="item.status === 'selesai' || item.status === 'complete'">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#E5E7EB] text-[#374151]">Selesai</span>
                                    </template>
                                    <template x-if="item.status === 'berlangsung'">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#CCFBF1] text-[#0F766E]">Berlangsung</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button @click="openDetail(item.id)" class="inline-flex items-center justify-center text-sm font-medium transition-all h-8 rounded-md gap-1.5 px-3 text-[#1A2B5C] hover:bg-gray-100/80">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredRiwayat.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-[#6B7280]">
                                <span class="text-sm font-medium">Riwayat kegiatan tidak ditemukan berdasarkan pencarian.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- ── COMPONENT MODAL DETAIL ── --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none" x-show="modalOpen" x-cloak>
        <div class="fixed inset-0 bg-black/40 transition-opacity" @click="modalOpen = false"></div>
        <div class="relative w-full max-w-2xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-100 flex flex-col max-h-[90vh]"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-[#F7F8FC] rounded-t-2xl">
                <h3 class="text-lg font-bold text-[#1A2B5C]">Detail Informasi Kegiatan</h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                <div x-show="loading" class="py-12 flex flex-col items-center justify-center gap-3">
                    <div class="w-8 h-8 border-4 border-[#1A2B5C] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs text-gray-500 font-medium">Memuat data...</span>
                </div>

                <div x-show="!loading" class="space-y-6">
                    <div>
                        <span class="text-[10px] tracking-wider uppercase font-bold text-[#F5A623]" x-text="detail.ormawa"></span>
                        <h4 class="text-xl font-bold text-[#1C1E2C] mt-0.5" x-text="detail.judul"></h4>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-[#F7F8FC] p-4 rounded-xl border border-gray-100 text-sm">
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[#6B7280] text-xs">Tanggal Pelaksanaan</span>
                            <span class="font-semibold text-[#1C1E2C]" x-text="detail.tanggal"></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[#6B7280] text-xs">Waktu Pelaksanaan</span>
                            <span class="font-semibold text-[#1C1E2C]" x-text="detail.waktu"></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[#6B7280] text-xs">Lokasi Kegiatan</span>
                            <span class="font-semibold text-[#1C1E2C]" x-text="detail.lokasi"></span>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[#6B7280] text-xs">Kuota Tersedia</span>
                            <span class="font-semibold text-[#1C1E2C]" x-text="detail.kuota"></span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h5 class="font-bold text-sm text-[#1C1E2C]">Deskripsi Kegiatan</h5>
                        <p class="text-sm text-[#6B7280] leading-relaxed whitespace-pre-line" x-text="detail.deskripsi"></p>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 flex justify-end bg-[#F7F8FC] rounded-b-2xl">
                <button @click="modalOpen = false" class="px-4 py-2 text-sm font-semibold text-white bg-[#1A2B5C] hover:bg-[#0F1B3D] rounded-lg transition-colors shadow-sm">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>

</div>
@endsection