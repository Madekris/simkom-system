@extends('layouts.app')

@section('content')
<div class="p-6 max-w-[1600px] mx-auto min-h-screen bg-gray-50/50">
    
    {{-- Notifikasi Alerts --}}
    <div id="js-alert" class="hidden mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl text-sm flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span id="js-alert-message" class="font-medium"></span>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-xl text-sm flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Header Page --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Ormawa</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola seluruh organisasi mahasiswa di SIMKOM Bali</p>
        </div>
        <div>
            <button type="button" onclick="openCreateModal()" class="bg-amber-500 hover:bg-amber-600 text-white font-medium px-4 py-2 rounded-xl transition duration-200 flex items-center gap-2 shadow-sm focus:outline-none">
                <span>+ Tambah Ormawa</span>
            </button>
        </div>
    </div>

    {{-- Filter Utilities --}}
    <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <div class="relative w-full sm:w-80">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" id="table-search" placeholder="Cari nama ormawa..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#F5A623]/20 focus:border-[#F5A623] transition-colors">
        </div>
    </div>

    {{-- TABEL UTAMA (ORMAWA AKTIF) --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
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
                        <td class="py-4 px-6 font-bold text-gray-900">{{ $o->nama }}</td>
                        <td class="py-4 px-6 text-gray-500">
                            @php $jenis = collect($jenis_organisasis)->firstWhere('id', $o->id_jenis_organisasi); @endphp
                            {{ $jenis->nama ?? 'UKM' }}
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $o->jumlah_anggota ?? '0' }} orang</td>
                        <td class="py-4 px-6 text-gray-500">{{ $o->ketua->nama ?? 'Belum Ditentukan' }}</td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">Aktif</span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="inline-flex items-center justify-end gap-3.5 text-gray-400">
                                <a href="{{ $o->ad_art ? asset('storage/' . $o->ad_art) : '#' }}" target="_blank" class="hover:text-gray-600 transition" title="Lihat Dokumen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                
                                <button type="button" onclick="lihatDetailOrmawa({{ json_encode($o) }})" class="hover:text-cyan-500 transition focus:outline-none" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                
                                <button type="button" onclick="openEditModal({{ json_encode($o) }})" class="hover:text-blue-500 transition focus:outline-none" title="Ubah Ormawa">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>

                                <button type="button" onclick="arsipkanOrmawa({{ $o->id }})" class="hover:text-[#e09516] transition focus:outline-none" title="Arsipkan Ormawa">
                                    <svg class="w-4 h-4 text-orange-600/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                </button>
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
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
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
</div>

@include('pages.admin.organisasi.create')
@include('pages.admin.organisasi.edit')

<script>
    // Bridge Functions untuk memastikan modal pemicu Create/Tambah berjalan lancar
    function openCreateModal() {
        const modal = document.getElementById('modal-create-ormawa');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function arsipkanOrmawa(id) {
        fetch(`/admin/organisasi/arsipkan/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`row-ormawa-${id}`);
                if (row) row.remove();

                const alertBox = document.getElementById('js-alert');
                const alertMsg = document.getElementById('js-alert-message');
                if (alertBox && alertMsg) {
                    alertMsg.textContent = data.message;
                    alertBox.classList.remove('hidden');
                }

                setTimeout(() => { window.location.reload(); }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses arsip.');
        });
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

    function lihatDetailOrmawa(data) {
        alert('Detail Ormawa:\n\nNama: ' + data.nama + '\nDeskripsi: ' + (data.deskripsi || '-'));
    }
</script>
@endsection