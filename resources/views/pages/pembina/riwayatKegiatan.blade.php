@extends('layouts.app')

{{-- TAMBAHKAN BARIS INI AGAR SIDEBAR MEMBACA ROLE PEMBINA --}}
@section('role_label', 'Pembina')

@section('content')
<div class="p-6 w-full flex flex-col gap-6" 
     x-data="{
        searchQuery: '',
        selectedYear: '', {{-- Diubah ke '' (kosong) agar default langsung menampilkan 'Semua' tahun saat dimuat --}}
        modalOpen: false,
        loading: false,
        detail: {},

        // 1. SINKRONISASI DATA: Menyimpan data mentah dari Controller ke Alpine
        riwayatData: {{ json_encode($riwayatKegiatan->map(function($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul_kegiatan,
                'ormawa' => $item->organisasi->nama ?? 'Umum',
                'tanggal_formatted' => \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('d M Y'),
                'tahun' => \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('Y'),
                'status' => strtolower($item->status) {{-- Dipastikan menggunakan huruf kecil semua agar konsisten saat pengecekan --}}
            ];
        })) }},

        // 2. SINKRONISASI FILTER: Logika filter menggunakan array data yang benar (riwayatData)
        get filteredRiwayat() {
            return this.riwayatData.filter(item => {
                // Filter pencarian berdasarkan judul kegiatan
                const matchSearch = item.judul.toLowerCase().includes(this.searchQuery.toLowerCase());
                
                // Filter berdasarkan kecocokan tahun kegiatan
                const matchYear = this.selectedYear === '' || item.tahun === this.selectedYear;
                
                return matchSearch && matchYear;
            });
        },

        // Trigger memanggil modal detail API via AJAX
        openDetail(id) {
            this.modalOpen = true;
            this.loading = true;
            fetch(`/pembina/riwayat-kegiatan/${id}`)
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

    {{-- Header Page & Filter --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <div class="relative flex-1 max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </span>
            <input type="text" x-model="searchQuery" placeholder="Cari nama kegiatan anda disini..." 
                   class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-900 text-gray-700">
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-500 font-medium">Tahun:</span>
            <select x-model="selectedYear" class="border border-gray-200 rounded-lg p-2 text-sm bg-white focus:outline-none text-gray-700">
                <option value="">Semua</option>
                @foreach($daftarTahun as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Tabel Riwayat Kegiatan --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-50">
            <h3 class="text-sm font-bold text-gray-800">Riwayat Kegiatan ( <span x-text="filteredRiwayat.length"></span> )</h3>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full text-sm text-left border-collapse whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-400 font-semibold text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kegiatan</th>
                        <th class="px-6 py-4">Ormawa</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                    <template x-for="item in filteredRiwayat" :key="item.id">
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900" x-text="item.judul"></td>
                            <td class="px-6 py-4 text-gray-500" x-text="item.ormawa"></td>
                            <td class="px-6 py-4 text-gray-500" x-text="item.tanggal_formatted"></td>
                            <td class="px-6 py-4">
                                {{-- Kondisi Status Selesai / Complete --}}
                                <template x-if="item.status === 'selesai' || item.status === 'complete'">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        Selesai
                                    </span>
                                </template>
                                
                                {{-- Kondisi Status Berlangsung / Ongoing --}}
                                <template x-if="item.status === 'berlangsung' || item.status === 'ongoing'">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        Berlangsung
                                    </span>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="openDetail(item.id)" class="inline-flex items-center gap-1 text-xs font-bold text-blue-900 hover:text-blue-950 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredRiwayat.length === 0">
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">
                            Tidak ada riwayat kegiatan untuk kriteria pencarian ini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL DETAIL INTERAKSI --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none" x-show="modalOpen" x-cloak>
        <div class="fixed inset-0 bg-black/40 transition-opacity" @click="modalOpen = false"></div>
        
        <div class="relative w-full max-w-2xl mx-auto bg-white rounded-2xl shadow-xl flex flex-col max-h-[85vh]"
             x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            {{-- Header Modal --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-[#F7F8FC] rounded-t-2xl">
                <h3 class="text-lg font-bold text-[#1A2B5C]">Detail Informasi Kegiatan</h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Konten Dinamis --}}
            <div class="p-6 overflow-y-auto flex-1 space-y-6">
                {{-- Loading State --}}
                <div x-show="loading" class="py-12 flex flex-col items-center justify-center gap-2">
                    <div class="w-8 h-8 border-4 border-[#1A2B5C] border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-xs text-gray-400 font-medium">Memuat data...</span>
                </div>

                {{-- Konten Siap --}}
                <div x-show="!loading" class="space-y-6">
                    <div>
                        <span class="text-[10px] tracking-wider uppercase font-bold text-[#F5A623]" x-text="detail.ormawa"></span>
                        <h4 class="text-xl font-bold text-[#1C1E2C] mt-0.5" x-text="detail.judul"></h4>
                    </div>

                    {{-- Grid Informasi Logistik --}}
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
                            <span class="text-[#6B7280] text-xs">Kuota Maksimal</span>
                            <span class="font-semibold text-[#1C1E2C]" x-text="detail.kuota"></span>
                        </div>
                        <div class="flex flex-col gap-0.5 col-span-2 border-t border-gray-200/60 pt-2 mt-1">
                            <span class="text-[#6B7280] text-xs">Realisasi Peserta Terdaftar (Disetujui)</span>
                            <span class="font-bold text-emerald-600" x-text="detail.realisasi"></span>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="space-y-1">
                        <h5 class="font-bold text-sm text-[#1C1E2C]">Deskripsi Kegiatan</h5>
                        <p class="text-sm text-[#6B7280] leading-relaxed whitespace-pre-line" x-text="detail.deskripsi"></p>
                    </div>

                    {{-- Evaluasi (Monitoring Pembina) --}}
                    <div class="space-y-1.5 border-t border-gray-100 pt-4">
                        <h5 class="font-bold text-sm text-[#1C1E2C]">Catatan Evaluasi Lapangan Panitia</h5>
                        <p class="text-sm text-gray-600 leading-relaxed bg-[#FFFDF5] p-3 rounded-lg border border-amber-100 italic whitespace-pre-line" x-text="detail.evaluasi"></p>
                    </div>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="p-4 border-t border-gray-100 flex justify-end bg-[#F7F8FC] rounded-b-2xl">
                <button @click="modalOpen = false" class="px-5 py-2 text-sm font-semibold text-white bg-[#1A2B5C] hover:bg-[#0F1B3D] rounded-lg transition-colors shadow-sm">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>

</div>
@endsection