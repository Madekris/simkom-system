@extends('layouts.app')

@section('topbar_title', 'Detail Organisasi')
@section('topbar_subtitle', $organisasi->nama)

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-5">
    <a href="{{ route('mahasiswa.organisasi.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800">
        <i class="fas fa-arrow-left text-xs"></i>
        Kembali ke daftar organisasi
    </a>

    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-5">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600 font-bold text-2xl shrink-0">
                    {{ strtoupper(substr($organisasi->nama, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $organisasi->nama }}</h1>
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-semibold rounded uppercase">
                            {{ $organisasi->jenisOrganisasi->nama ?? 'UKM' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                        {{ $organisasi->deskripsi ?: 'Belum ada deskripsi untuk organisasi ini.' }}
                    </p>
                </div>
            </div>

            <a href="{{ route('mahasiswa.organisasi.daftar', $organisasi->id) }}" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-semibold shadow-sm transition text-center">
                Daftar Anggota
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <section class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-3">Visi</h2>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $organisasi->visi ?: '-' }}</p>
        </section>

        <section class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <h2 class="font-bold text-gray-900 mb-3">Misi</h2>
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $organisasi->misi ?: '-' }}</p>
        </section>
    </div>

    <section class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
        <h2 class="font-bold text-gray-900 mb-3">Pengurus Inti</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="rounded-xl bg-gray-50 p-4">
                <div class="text-xs uppercase text-gray-400 font-semibold">Ketua</div>
                <div class="text-sm font-bold text-gray-800 mt-1">
                    {{ $organisasi->ketua?->user?->mahasiswa?->nama ?? $organisasi->ketua?->user?->name ?? 'Belum ditentukan' }}
                </div>
            </div>
            <div class="rounded-xl bg-gray-50 p-4">
                <div class="text-xs uppercase text-gray-400 font-semibold">Bendahara</div>
                <div class="text-sm font-bold text-gray-800 mt-1">
                    {{ $organisasi->bendahara?->user?->mahasiswa?->nama ?? $organisasi->bendahara?->user?->name ?? 'Belum ditentukan' }}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
