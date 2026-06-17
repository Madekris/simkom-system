@extends('layouts.app')

@section('title', 'Dashboard Pembina')
@section('role_label', 'Pembina')

@section('topbar_title', 'Dashboard Utama')
@section('topbar_subtitle', 'Selamat datang kembali di Sistem SIMKOM')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto w-full">
    {{-- Grid Card Statistik Singkat --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
        <div class="bg-white p-5 rounded-xl border border-[#E5E7EB] shadow-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl">
                <i class="fa-solid fa-circle-dot animate-pulse"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-muted uppercase tracking-wider">Kegiatan Berlangsung</p>
                <h3 class="text-2xl font-bold text-navy mt-0.5">{{ $totalBerjalan }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-[#E5E7EB] shadow-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-navy/light flex items-center justify-center text-navy text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-muted uppercase tracking-wider">Total Riwayat Selesai</p>
                <h3 class="text-2xl font-bold text-navy mt-0.5">{{ $totalSelesai }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection