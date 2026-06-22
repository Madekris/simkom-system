@extends('layouts.app')

@section('role_label', 'Pengurus')
@section('topbar_title', 'Kegiatan')
@section('topbar_subtitle', 'Kelola seluruh kegiatan ormawa Anda')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto">

    {{-- Header & Button Tambah --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        {{-- Filter Tabs --}}
        <div class="flex flex-wrap items-center gap-2 bg-gray-100/80 p-1 rounded-xl w-fit border border-gray-200/50">
            <a href="{{ route('pengurus.kegiatan.index', ['status' => 'semua']) }}" 
               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all {{ $statusFilter === 'semua' ? 'bg-[#1A2B5C] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Semua
            </a>
            <a href="{{ route('pengurus.kegiatan.index', ['status' => 'berlangsung']) }}" 
               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all {{ $statusFilter === 'berlangsung' ? 'bg-[#1A2B5C] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Berlangsung
            </a>
            <a href="{{ route('pengurus.kegiatan.index', ['status' => 'selesai']) }}" 
               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all {{ $statusFilter === 'selesai' ? 'bg-[#1A2B5C] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Selesai
            </a>
            <a href="{{ route('pengurus.kegiatan.index', ['status' => 'dibatalkan']) }}" 
               class="px-4 py-2 text-xs font-semibold rounded-lg transition-all {{ $statusFilter === 'dibatalkan' ? 'bg-[#1A2B5C] text-white shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Dibatalkan
            </a>
        </div>

        {{-- Button Buat Kegiatan --}}
        <a href="{{ route('pengurus.kegiatan.create') }}" 
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold px-4 py-2.5 rounded-lg shadow-sm transition-colors w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Buat Kegiatan
        </a>
    </div>

    {{-- Grid List Card Kegiatan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($kegiatan as $item)
            @php
                $statusDatabase = strtolower($item->status);
                if ($statusDatabase === 'dibatalkan') {
                    $badgeClass = 'bg-red-50 text-red-700 border-red-200';
                    $statusLabel = 'Dibatalkan';
                } elseif ($statusDatabase === 'selesai') {
                    $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                    $statusLabel = 'Selesai';
                } elseif ($statusDatabase === 'pending') {
                    $badgeClass = 'bg-gray-50 text-gray-700 border-gray-200';
                    $statusLabel = 'Pending';
                } elseif ($statusDatabase === 'mendatang') {
                    $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
                    $statusLabel = 'Mendatang';
                } else {
                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $statusLabel = 'Berlangsung';
                }
            @endphp

            <div class="bg-white rounded-xl border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.02)] p-5 flex flex-col justify-between hover:shadow-md transition-shadow relative">
                <div class="space-y-3.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 text-base line-clamp-1">
                            {{ $item->judul_kegiatan }}
                        </h3>
                        <p class="text-xs text-gray-500 mt-1.5 line-clamp-2 leading-relaxed">
                            {{ $item->deskripsi }}
                        </p>
                    </div>

                    <div class="pt-1.5 space-y-2 text-xs text-gray-600 border-t border-gray-50">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-400 shrink-0"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                            <span>{{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-400 shrink-0"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span>{{ \Carbon\Carbon::parse($item->waktu_kegiatan)->format('H:i') }} WITA</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-gray-400 shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span class="line-clamp-1">{{ $item->lokasi }}</span>
                        </div>
                    </div>
                </div>

                {{-- Trigger Button Detail --}}
                <div class="border-t border-gray-100 mt-4 pt-3 flex justify-end">
                    <button type="button" 
                            onclick="openDetailModal({{ json_encode($item) }}, '{{ $badgeClass }}', '{{ $statusLabel }}')"
                            data-update-url="{{ route('pengurus.kegiatan.update', $item->id) }}" {{-- <-- PASTIKAN STRUKTUR INI SAMA --}}
                            class="inline-flex items-center text-xs font-semibold text-[#1A2B5C] hover:underline gap-1 group">
                        Detail 
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-400 text-sm">
                Tidak ada riwayat kegiatan terdaftar untuk filter ini.
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL OVERLAY DETAIL POP-UP --}}
<div id="detailModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6 lg:p-8">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-40 backdrop-blur-[1px] transition-opacity" onclick="closeDetailModal()"></div>

        <div class="relative inline-block align-middle bg-white rounded-xl text-left shadow-xl transform transition-all w-full max-w-2xl border border-gray-200 flex flex-col max-h-[calc(100vh-3rem)] sm:max-h-[calc(100vh-4rem)]">
            <div class="overflow-y-auto p-5 sm:p-7 space-y-5 flex-1">
                <div class="space-y-2">
                    <span id="modalBadge" class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full border"></span>
                    <h3 id="modalJudul" class="text-lg sm:text-xl font-bold text-gray-900 leading-tight"></h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 sm:gap-4 py-3.5 px-4 bg-gray-50/70 rounded-xl border border-gray-100">
                    <div class="space-y-0.5">
                        <span class="block text-[10px] font-bold text-gray-400 tracking-wider uppercase">Tanggal</span>
                        <span id="modalTanggal" class="text-xs font-semibold text-gray-800 block"></span>
                    </div>
                    <div class="space-y-0.5">
                        <span class="block text-[10px] font-bold text-gray-400 tracking-wider uppercase">Waktu</span>
                        <span id="modalWaktu" class="text-xs font-semibold text-gray-800 block"></span>
                    </div>
                    <div class="space-y-0.5">
                        <span class="block text-[10px] font-bold text-gray-400 tracking-wider uppercase">Peserta Terestimasi</span>
                        <span id="modalPeserta" class="text-xs font-semibold text-gray-800 block"></span>
                    </div>
                </div>

                <div class="space-y-1">
                    <h4 class="text-xs font-bold text-gray-400 tracking-wider uppercase">Lokasi / Ruangan</h4>
                    <p id="modalLokasi" class="text-xs font-semibold text-gray-800"></p>
                </div>

                <div class="space-y-1.5">
                    <h4 class="text-xs font-bold text-gray-400 tracking-wider uppercase">Deskripsi Kegiatan</h4>
                    <p id="modalDeskripsi" class="text-xs text-gray-600 leading-relaxed text-justify whitespace-pre-line"></p>
                </div>

                <div id="modalEvaluasiContainer" class="p-4 bg-blue-50 border border-blue-100 rounded-xl space-y-1 hidden">
                    <h5 class="text-xs font-bold text-blue-900 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                        Catatan Evaluasi / Kendala
                    </h5>
                    <p id="modalEvaluasi" class="text-xs text-blue-800 leading-relaxed whitespace-pre-line"></p>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3.5 flex items-center justify-end gap-2.5 border-t border-gray-100 shrink-0 rounded-b-xl">
                <button type="button" onclick="closeDetailModal()"
                        class="inline-flex items-center justify-center rounded-lg text-xs font-semibold border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 h-9 px-4 transition-colors focus:outline-none min-w-[70px]">
                    Tutup
                </button>
                <button type="button" id="btnTriggerEdit"
                        class="inline-flex items-center justify-center rounded-lg text-xs font-semibold bg-[#1A2B5C] text-white hover:bg-[#1A2B5C]/90 h-9 px-4 shadow-sm transition-colors focus:outline-none min-w-[70px]">
                    Edit
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL OVERLAY EDIT POP-UP (Sesuai Persis dengan UI Dokumen Gambar Anda) --}}
<div id="editModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-40 backdrop-blur-[1px] transition-opacity" onclick="closeEditModal()"></div>

        <div class="relative inline-block align-middle bg-white rounded-xl text-left shadow-xl transform transition-all w-full max-w-lg border border-gray-200 flex flex-col max-h-[calc(100vh-3rem)] sm:max-h-[calc(100vh-4rem)]">
            
            {{-- Header Edit Modal --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#1A2B5C] flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Edit Kegiatan</h3>
                        <p id="editModalSub" class="text-[11px] text-gray-400 font-medium uppercase tracking-wide"></p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>

            {{-- Form Pengiriman Update Kegiatan --}}
            <form id="formEditKegiatan" method="POST" class="overflow-y-auto p-6 space-y-4 flex-1 text-xs">
                @csrf
                @method('PUT')

                {{-- Judul Kegiatan --}}
                <div class="space-y-1.5">
                    <label class="block font-bold text-gray-700">Judul Kegiatan *</label>
                    <input type="text" name="judul_kegiatan" id="editJudul" required
                           class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1A2B5C] focus:border-[#1A2B5C] bg-white text-gray-800">
                </div>

                {{-- Row Tanggal & Lokasi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block font-bold text-gray-700">Tanggal</label>
                        <input type="date" name="tanggal_kegiatan" id="editTanggal" required
                               class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1A2B5C] focus:border-[#1A2B5C] bg-white text-gray-800">
                    </div>
                    <div class="space-y-1.5">
                        <label class="block font-bold text-gray-700">Lokasi</label>
                        <input type="text" name="lokasi" id="editLokasi" required
                               class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1A2B5C] focus:border-[#1A2B5C] bg-white text-gray-800">
                    </div>
                </div>

                {{-- Estimasi Peserta --}}
                <div class="space-y-1.5">
                    <label class="block font-bold text-gray-700">Estimasi Peserta</label>
                    <input type="number" name="kuota_peserta" id="editPeserta" min="0" required
                        class="w-full h-10 px-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1A2B5C] focus:border-[#1A2B5C] bg-white text-gray-800">
                </div>

                {{-- Status Dropdown --}}
                <div class="space-y-1.5">
                    <label class="block font-bold text-gray-700">Status</label>
                    <div class="relative">
                        <select name="status" id="editStatus" required
                                class="w-full h-10 px-3 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-1 focus:ring-[#1A2B5C] focus:border-[#1A2B5C] bg-white text-gray-800">
                            <option value="Pending">Pending</option>
                            <option value="Berlangsung">Berlangsung</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                            <option value="Mendatang">Mendatang</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi Textarea --}}
                <div class="space-y-1.5">
                    <label class="block font-bold text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="editDeskripsi" rows="4" required
                              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-[#1A2B5C] focus:border-[#1A2B5C] bg-white text-gray-800 resize-none leading-relaxed"></textarea>
                </div>
            </form>

            {{-- Footer Edit Modal --}}
            <div class="bg-gray-50 px-6 py-3.5 flex items-center justify-end gap-2.5 border-t border-gray-100 shrink-0 rounded-b-xl">
                <button type="button" onclick="closeEditModal()"
                        class="inline-flex items-center justify-center rounded-lg text-xs font-semibold border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 h-9 px-4 transition-colors focus:outline-none min-w-[70px]">
                    Batal
                </button>
                <button type="submit" form="formEditKegiatan"
                        class="inline-flex items-center justify-center rounded-lg text-xs font-semibold bg-[#1A2B5C] text-white hover:bg-[#1A2B5C]/90 h-9 px-4 shadow-sm transition-colors focus:outline-none min-w-[70px] gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan
                </button>
            </div>

        </div>
    </div>
</div>

{{-- INTERAKSI JAVASCRIPT --}}
<script>
    // Variabel global untuk menyimpan data kegiatan aktif yang sedang dilihat/diedit
    let currentActiveData = null;

    function openDetailModal(data, badgeClass, label) {
        currentActiveData = data;
        const modal = document.getElementById('detailModal');
        
        // Ambil URL Update Laravel asli dari atribut data-update-url tombol yang diklik
        const triggerButton = event.currentTarget;
        const updateUrl = triggerButton.getAttribute('data-update-url');

        document.getElementById('modalJudul').textContent = data.judul_kegiatan;
        document.getElementById('modalDeskripsi').textContent = data.deskripsi;
        document.getElementById('modalLokasi').textContent = data.lokasi;
        document.getElementById('modalPeserta').textContent = data.kuota_peserta + " Peserta";
        
        if (data.waktu_kegiatan) {
            document.getElementById('modalWaktu').textContent = data.waktu_kegiatan.substring(0, 5) + " WITA";
        }

        // Format Tanggal
        const dateOpt = { year: 'numeric', month: 'short', day: 'numeric' };
        const formattedDate = new Date(data.tanggal_kegiatan).toLocaleDateString('id-ID', dateOpt);
        document.getElementById('modalTanggal').textContent = formattedDate;

        // Badge Status
        const modalBadge = document.getElementById('modalBadge');
        modalBadge.className = "inline-block text-xs font-semibold px-2.5 py-1 rounded-full border " + badgeClass;
        modalBadge.textContent = label;

        // Container Catatan Evaluasi
        const evalContainer = document.getElementById('modalEvaluasiContainer');
        if (data.evaluasi_kegiatan) {
            document.getElementById('modalEvaluasi').textContent = data.evaluasi_kegiatan;
            evalContainer.classList.remove('hidden');
        } else {
            evalContainer.classList.add('hidden');
        }

        // Oper URL resmi dari Laravel ke fungsi openEditModal
        document.getElementById('btnTriggerEdit').onclick = function() {
            closeDetailModal();
            openEditModal(currentActiveData, updateUrl); // <-- Kirim updateUrl ke sini
        };

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    function openEditModal(data, updateUrl) {
        const modal = document.getElementById('editModal');
        
        // Menggunakan URL updateUrl akurat yang bersumber dari route('pengurus.kegiatan.update')
        document.getElementById('formEditKegiatan').action = updateUrl;

        // Mengisi Data (*Pre-filled*) ke dalam elemen form input
        document.getElementById('editModalSub').textContent = data.organisasi ? data.organisasi.nama_organisasi : 'HIMA TI';
        document.getElementById('editJudul').value = data.judul_kegiatan;
        document.getElementById('editTanggal').value = data.tanggal_kegiatan;
        document.getElementById('editLokasi').value = data.lokasi;
        document.getElementById('editPeserta').value = data.kuota_peserta;
        document.getElementById('editDeskripsi').value = data.deskripsi;
        document.getElementById('editStatus').value = data.status;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Proteksi input peserta anti-minus
    document.addEventListener('DOMContentLoaded', function() {
        const inputPeserta = document.getElementById('editPeserta');
        if (inputPeserta) {
            inputPeserta.addEventListener('keypress', function(event) {
                if (event.key === '-' || event.key === 'e' || event.key === 'E') {
                    event.preventDefault();
                }
            });
            inputPeserta.addEventListener('input', function() {
                if (this.value < 0 || this.value === '-') {
                    this.value = 0;
                }
            });
        }
    });

    // Dukungan tombol 'Escape'
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailModal();
            closeEditModal();
        }
    });
</script>
@endsection