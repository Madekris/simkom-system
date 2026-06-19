@extends('layouts.app')

@section('role_label', 'Pengurus')
@section('topbar_title', 'Detail Kegiatan')
@section('topbar_subtitle', $kegiatan->judul_kegiatan)

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto">

    {{-- Tombol Kembali --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('pengurus.kegiatan.index') }}" 
           class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            Kembali
        </a>
    </div>

    @php
        // Sinkronisasi status seeder untuk warna badge utama
        $statusDatabase = strtolower($kegiatan->status);
        if ($statusDatabase === 'dibatalkan') {
            $badgeClass = 'bg-red-50 text-red-700 border-red-200';
            $statusLabel = 'Dibatalkan';
        } elseif ($statusDatabase === 'selesai') {
            $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
            $statusLabel = 'Selesai';
        } elseif ($statusDatabase === 'pending') {
            $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
            $statusLabel = 'Pending';
        } else {
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            $statusLabel = 'Berlangsung';
        }
    @endphp

    {{-- Main Layout Grid: Kiri (Konten Utama) & Kanan (Anggaran & Komponen Inti) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        {{-- KARTU KIRI: DETAIL KONTEN UTAMA --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.02)] p-6 space-y-6">
            
            {{-- Header Blok Informasi --}}
            <div class="flex items-start justify-between gap-4">
                <div class="space-y-1.5">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $badgeClass }}">
                        {{ $statusLabel }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight">
                        {{ $kegiatan->judul_kegiatan }}
                    </h2>
                    <p class="text-xs font-medium text-gray-400">
                        {{ $kegiatan->organisasi->nama ?? 'HIMA Teknik Informatika' }}
                    </p>
                </div>
                
                {{-- Button Edit --}}
                <button class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                    Edit
                </button>
            </div>

            {{-- Row Logistik: Tanggal, Lokasi, Peserta --}}
            <div class="grid grid-cols-3 gap-4 py-4 px-4 bg-gray-50/50 rounded-xl border border-gray-100 text-center sm:text-left">
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-1">Tanggal</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 text-xs font-semibold text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-gray-400 shrink-0 hidden sm:block"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span>{{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-1">Lokasi</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 text-xs font-semibold text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-gray-400 shrink-0 hidden sm:block"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span class="truncate">{{ $kegiatan->lokasi }}</span>
                    </div>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 tracking-wider uppercase mb-1">Peserta</span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 text-xs font-semibold text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-gray-400 shrink-0 hidden sm:block"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>{{ $kegiatan->kuota_peserta }} / {{ $kegiatan->kuota_peserta + 20 }}</span>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="space-y-2">
                <h4 class="text-sm font-bold text-gray-900">Deskripsi</h4>
                <p class="text-xs text-gray-600 leading-relaxed text-justify">
                    {{ $kegiatan->deskripsi }}
                </p>
            </div>
            
            {{-- Jika ada evaluasi dari seeder --}}
            @if($kegiatan->evaluasi_kegiatan)
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl space-y-1.5">
                    <h5 class="text-xs font-bold text-blue-900 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                        Catatan Evaluasi Kegiatan
                    </h5>
                    <p class="text-xs text-blue-800 leading-relaxed">{{ $kegiatan->evaluasi_kegiatan }}</p>
                </div>
            @endif

        </div>

        {{-- BAGIAN KANAN: ANGGARAN & PENANGGUNG JAWAB --}}
        <div class="space-y-5">
            
            {{-- KARTU 1: ANGGARAN --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.02)] p-5 space-y-4">
                <h4 class="text-sm font-bold text-gray-900">Anggaran</h4>
                <div class="space-y-1">
                    <span class="text-lg font-bold text-gray-900">Rp 5.000.000</span>
                    <p class="text-[11px] text-gray-400 font-medium">Terpakai Rp 3.250.000</p>
                </div>
                {{-- Progress Bar Anggaran Komponen Hijau --}}
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-emerald-500 h-2 rounded-full shadow-sm" style="width: 65%"></div>
                </div>
            </div>

            {{-- KARTU 2: PENANGGUNG JAWAB --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-[0_2px_12px_rgba(0,0,0,0.02)] p-5 space-y-4">
                <h4 class="text-sm font-bold text-gray-900">Penanggung Jawab</h4>
                
                <div class="space-y-3.5">
                    {{-- Ketua Pelaksana --}}
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#1A2B5C] flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-sm">
                            AP
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-xs font-bold text-gray-900 truncate">Andi Pratama</h5>
                            <p class="text-[10px] font-medium text-gray-400">Ketua Pelaksana</p>
                        </div>
                    </div>

                    {{-- Sekretaris --}}
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#1A2B5C] flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-sm">
                            SD
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-xs font-bold text-gray-900 truncate">Sari Dewi</h5>
                            <p class="text-[10px] font-medium text-gray-400">Sekretaris</p>
                        </div>
                    </div>

                    {{-- Bendahara --}}
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#1A2B5C] flex items-center justify-center text-white font-bold text-xs shrink-0 shadow-sm">
                            MW
                        </div>
                        <div class="min-w-0">
                            <h5 class="text-xs font-bold text-gray-900 truncate">Made Wijaya</h5>
                            <p class="text-[10px] font-medium text-gray-400">Bendahara</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection