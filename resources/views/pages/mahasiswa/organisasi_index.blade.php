@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Daftar Organisasi')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Seluruh organisasi mahasiswa SIMKOM Bali')

{{-- Isi Tombol / Aksi di Sebelah Kanan (Opsional) --}}
@section('topbar_actions')
    <form action="{{ route('mahasiswa.organisasi.index') }}" method="GET" class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
            <i class="fas fa-search"></i>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama ormawa..." 
            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:bg-white transition">
    </form>
@endsection

@section('content')
<div class="p-6">

    <div class="space-y-4">
        @forelse($organisasi as $org)
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-full bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600 font-bold text-xl shrink-0 shadow-sm">
                        {{ strtoupper(substr($org->nama, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-lg font-bold text-gray-800">{{ $org->nama }}</h3>
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-semibold rounded uppercase tracking-wider">
                                {{ $org->jenisOrganisasi->nama ?? 'UKM' }}
                            </span>
                            @if(($org->status ?? 'aktif') === 'aktif')
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-xs font-medium rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                            @else
                                <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-xs font-medium rounded-full flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-1 max-w-3xl line-clamp-2">
                            {{ $org->deskripsi ?? 'Belum ada deskripsi profil untuk organisasi ini.' }}
                        </p>
                        <div class="flex gap-4 mt-2 text-xs text-gray-400">
                            <span><i class="fas fa-users mr-1"></i> {{ $org->anggota_count ?? '0' }} anggota</span>
                            <span>Pengurus: <span class="text-gray-600 font-medium">{{ $org->ketua?->user?->mahasiswa?->nama ?? $org->pengurus?->user?->mahasiswa?->nama ?? $org->ketua?->user?->name ?? $org->pengurus?->user?->name ?? 'Belum Ditentukan' }}</span></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end md:self-center shrink-0">
                    <a href="{{ route('mahasiswa.organisasi.show', $org->id) }}" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Detail</a>
                    
                    <a href="{{ route('mahasiswa.organisasi.daftar', $org->id) }}" 
                        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        Daftar
                    </a>        
                </div>

            </div>
        @empty
            <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center text-gray-400 text-sm">
                <i class="fas fa-folder-open text-2xl mb-2 block"></i>
                Organisasi tidak ditemukan atau belum ditambahkan.
            </div>
        @endforelse
    </div>
</div>
@endsection
