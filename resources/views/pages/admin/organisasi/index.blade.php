@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Daftar Ormawa')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Kelola seluruh organisasi mahasiswa di SIMKOM Bali')

@section('topbar_actions')
    <button type="button" onclick="openCreateModal()" class="bg-amber-500 hover:bg-amber-600 text-white font-medium px-4 py-2 rounded-xl transition duration-200 flex items-center gap-2 shadow-sm focus:outline-none">
        <span>+ Tambah Ormawa</span>
    </button>
@endsection

@section('content')
<div class="p-6 max-w-[1600px] mx-auto min-h-screen bg-gray-50/50" 
     x-data="{ 
        openDocModal: false, 
        ormawaKegiatans: [],
        isLoading: false,
        fetchDokumen(ormawaId) {
            this.isLoading = true;
            this.ormawaKegiatans = []; // Reset data lama sebelum memuat
            this.openDocModal = true;
            
            // KUNCI PERBAIKAN SINKRONISASI: Menghubungkan jalur URL internal yang tepat menuju web.php
            fetch(`/admin/organisasi/${ormawaId}/dokumen`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    if (res.status === 403) throw new Error('Sesi kedaluwarsa atau Anda tidak memiliki akses (403)');
                    if (res.status === 404) throw new Error('Endpoint URL rute berkas tidak ditemukan di server (404)');
                    throw new Error('Server mengalami kegagalan internal saat memproses berkas (500)');
                }
                return res.json();
            })
            .then(response => {
                console.log('Data sukses diterima dari database:', response);
                this.ormawaKegiatans = response.data || [];
                this.isLoading = false;
            })
            .catch(err => {
                console.error('Detail rincian log kesalahan jaringan:', err);
                alert(err.message);
                this.isLoading = false;
                this.openDocModal = false; // Menutup modal otomatis jika gagal memuat data berkas
            });
        }
     }">

    {{-- Filter Utilities --}}
    <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" id="table-search" onkeyup="searchTable()" placeholder="Cari nama ormawa..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-colors">
        </div>
    </div>

    {{-- TABEL UTAMA (ORMAWA AKTIF) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="mainOrmawaTable">
                <thead>
                    <tr class="text-gray-400 text-xs font-semibold tracking-wider uppercase bg-gray-50/70 border-b border-gray-100">
                        <th class="py-4 px-6">Nama Ormawa</th>
                        <th class="py-4 px-6">Jenis</th>
                        <th class="py-4 px-6">Anggota</th>
                        <th class="py-4 px-6">Ketua</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-[14px] text-gray-600">
                    @forelse($organisasis as $o)
                    <tr id="row-ormawa-{{ $o->id }}" class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 font-bold text-gray-900 ormawa-name-cell">{{ $o->nama }}</td>
                        <td class="py-4 px-6 text-gray-500">
                            @php $jenis = collect($jenis_organisasis)->firstWhere('id', $o->id_jenis_organisasi); @endphp
                            {{ $jenis->nama ?? 'UKM' }}
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $o->jumlah_anggota ?? '0' }} orang</td>
                        <td class="py-4 px-6 text-gray-500">{{ $o->ketua->nama ?? 'Belum Ditentukan' }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">Aktif</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-1">
                                
                                {{-- Tombol Pemicu Modal Dokumen Progress --}}
                                <button type="button" 
                                        @click="fetchDokumen({{ $o->id }})"
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors hover:bg-purple-50 hover:text-purple-600 rounded-md h-8 w-8 text-[#6B7280]" 
                                        title="Lihat Dokumen Progress">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                        <path d="M10 9H8"></path>
                                        <path d="M16 13H8"></path>
                                        <path d="M16 17H8"></path>
                                    </svg>
                                </button>
                                
                                <a href="{{ route('admin.organisasi.index', ['id' => $o->id, 'tab' => 'informasi']) }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none hover:bg-gray-100 hover:text-[#1A2B5C] rounded-md h-8 w-8 text-[#1A2B5C] cursor-pointer" title="Detail Ormawa">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                
                                <button type="button" onclick="openEditModal({{ json_encode($o) }})" data-slot="button" 
                                        class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none focus-visible:ring-2 focus-visible:ring-[#1A2B5C]/20 hover:bg-[#1A2B5C]/5 hover:text-[#1A2B5C] rounded-md h-8 w-8 text-[#1A2B5C]" 
                                        title="Edit Ormawa">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                        <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path>
                                    </svg>
                                </button>

                                <form action="{{ route('admin.organisasi.arsipkan', ['id' => $o->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan ormawa ini?')" class="inline">
                                    @csrf
                                    <button type="submit" data-slot="button" 
                                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-colors disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none focus-visible:ring-2 focus-visible:ring-[#92400E]/20 hover:bg-[#92400E]/5 hover:text-[#92400E] rounded-md h-8 w-8 text-[#92400E]" 
                                            title="Arsipkan Ormawa">
                                        <svg xmlns="http://www.w3.org/2000/xl" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                            <rect width="20" height="5" x="2" y="3" rx="1"></rect>
                                            <path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"></path>
                                            <path d="M10 12h4"></path>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 font-medium">Belum ada data organisasi terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- UTILITY AREA: LIST ORMAWA DIARSIPKAN --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden mb-8">
        <button onclick="toggleAccordion('accordion-archive')" class="w-full flex items-center justify-between p-5 bg-gray-50/50 hover:bg-gray-50 transition focus:outline-none">
            <div class="flex items-center gap-4">
                <div class="p-2.5 bg-orange-50 rounded-xl border border-orange-100 text-orange-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
                <div class="text-left">
                    <h3 class="font-bold text-gray-900 text-sm sm:text-base">Ormawa Diarsipkan</h3>
                    <p class="text-xs text-gray-400 mt-0.5"><span id="archive-count">{{ count($organisasis_diarsipkan) }}</span> item diarsipkan</p>
                </div>
            </div>
            <svg id="accordion-arrow" class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="accordion-archive" class="hidden border-t border-gray-100 overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-xs font-semibold tracking-wider uppercase bg-gray-50/30 border-b border-gray-100">
                        <th class="py-4 px-6">Nama Ormawa</th>
                        <th class="py-4 px-6">Jenis</th>
                        <th class="py-4 px-6">Ketua</th>
                        <th class="py-4 px-6 text-right">Opsi Pemulihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-[14px] text-gray-500">
                    @forelse($organisasis_diarsipkan as $arch)
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="py-4 px-6 font-semibold text-gray-800">{{ $arch->nama }}</td>
                        <td class="py-4 px-6">
                            @php $jenisArch = collect($jenis_organisasis)->firstWhere('id', $arch->id_jenis_organisasi); @endphp
                            {{ $jenisArch->nama ?? 'UKM' }}
                        </td>
                        <td class="py-4 px-6">{{ $arch->ketua->nama ?? '-' }}</td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('admin.organisasi.pulihkan', $arch->id) }}" class="inline-block px-3 py-1.5 bg-gray-100 hover:bg-[#F5A623] hover:text-white text-gray-600 rounded-lg text-xs font-semibold transition shadow-sm">
                                Aktifkan Kembali
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400 text-xs">Tidak ada data organisasi yang diarsipkan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL DOKUMEN PROGRESS --}}
    <div x-show="openDocModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4 transition-all"
         style="display: none;"
         x-transition
         x-cloak>
        
        <div class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl transition-all" @click.away="openDocModal = false">
            
            <div class="flex items-start justify-between border-b border-gray-100 p-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Dokumen Progress</h3>
                        <p class="text-sm text-gray-500">Proposal, LPJ, RAB, dan dokumen lainnya per kegiatan</p>
                    </div>
                </div>
                <button @click="openDocModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="max-h-[60vh] overflow-y-auto p-6 space-y-4 bg-gray-50/50">
                
                {{-- State Loading --}}
                <template x-if="isLoading">
                    <div class="text-center py-8 text-purple-600 font-medium">
                        Mendapatkan berkas dari server...
                    </div>
                </template>
                
                {{-- State Jika Data Berhasil Dimuat Tapi Kosong --}}
                <template x-if="!isLoading && ormawaKegiatans.length === 0">
                    <div class="text-center py-8 text-gray-400 text-xs italic bg-white border border-dashed rounded-xl">
                        Tidak ada riwayat berkas dokumen progress untuk ormawa ini.
                    </div>
                </template>

                {{-- Loop Kegiatan dan Dokumen Terkait --}}
                <template x-for="kegiatan in ormawaKegiatans" :key="kegiatan.nama_kegiatan">
                    <div x-data="{ expanded: true }" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm mb-4">
                        <div @click="expanded = !expanded" class="flex cursor-pointer items-center justify-between p-4 hover:bg-gray-50 transition-colors select-none">
                            <div>
                                <h4 class="font-bold text-gray-800" x-text="kegiatan.nama_kegiatan"></h4>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Periode Tahun <span x-text="kegiatan.periode"></span> · membawahi <span x-text="kegiatan.dokumen ? kegiatan.dokumen.length : 0"></span> berkas dokumen
                                </p>
                            </div>
                            <span class="text-gray-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </div>

                        <div x-show="expanded" class="border-t border-gray-100 divide-y divide-gray-100 bg-white">
                            <template x-for="doc in kegiatan.dokumen" :key="doc.nama_file">
                                <div class="flex items-center justify-between p-4 hover:bg-gray-50/50 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div class="text-red-500 bg-red-50 p-2 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-semibold text-gray-700" x-text="doc.nama_file"></h5>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider" 
                                              :class="{
                                                  'bg-blue-50 text-blue-600': doc.tipe.toUpperCase() === 'PROPOSAL',
                                                  'bg-amber-50 text-amber-600': doc.tipe.toUpperCase() === 'RAB',
                                                  'bg-emerald-50 text-emerald-600': doc.tipe.toUpperCase() === 'LPJ',
                                                  'bg-purple-50 text-purple-600': doc.tipe.toUpperCase() === 'DOKUMENTASI'
                                              }"
                                              x-text="doc.tipe">
                                        </span>
                                        {{-- SINKRONISASI TOTAL: Mengunci Root Domain & Membersihkan Tanda Slash Berlebih --}}
                                        <a :href="window.location.origin + '/' + doc.path_url.replace(/^\/+/, '')" target="_blank" class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 shadow-sm transition-colors">Lihat</a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

            </div>

            <div class="flex justify-end border-t border-gray-100 p-4 bg-white">
                <button @click="openDocModal = false" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm transition-all">Tutup</button>
            </div>
        </div>
    </div>

    @if (request('id') && isset($oView))
        <x-modal-detail-ormawa :data="$oView" />
    @endif

    @include('pages.admin.organisasi.create')
    @include('pages.admin.organisasi.edit')

</div>

<script>
    // Fungsi Pencarian Live untuk Tabel Utama
    function searchTable() {
        let input = document.getElementById("table-search").value.toLowerCase();
        let rows = document.querySelectorAll("#mainOrmawaTable tbody tr");
        
        rows.forEach(row => {
            let nameCell = row.querySelector(".ormawa-name-cell");
            if (nameCell) {
                let match = nameCell.textContent.toLowerCase().indexOf(input) > -1;
                row.style.display = match ? "" : "none";
            }
        });
    }

    function openCreateModal() {
        const modal = document.getElementById('modal-create-ormawa');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function toggleAccordion(id) {
        const content = document.getElementById(id);
        const arrow = document.getElementById('accordion-arrow');
        if(content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            arrow.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            arrow.classList.remove('rotate-180');
        }
    }
</script>
@endsection