
@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Daftar Kegiatan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Seluruh kegiatan organisasi mahasiswa')


@section('content')

<div class="p-4 sm:p-6 lg:p-8 space-y-5">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('mahasiswa.daftar-kegiatan.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[#6B7280] hover:text-[#1A2B5C] transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar Kegiatan
        </a>
    </div>

    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
        
        <div class="h-3 bg-[#1A2B5C] w-full"></div>

        <div class="p-6 sm:p-8">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs uppercase tracking-wide font-bold px-2.5 py-1 rounded bg-[#F7F8FC] text-[#1A2B5C] border border-[#E5E7EB]">
                        {{ $daftarKegiatan->organisasi['nama'] }}
                    </span>
                    <span class="text-xs text-[#6B7280]">
                        Periode {{$daftarKegiatan->periode['tahun_mulai']}}/{{ $daftarKegiatan->periode['tahun_selesai'] }}
                    </span>
                </div>
                
                <h1 class="text-2xl sm:text-3xl font-bold text-[#1C1E2C] leading-tight">
                    {{ $daftarKegiatan['judul_kegiatan'] }}
                </h1>
            </div>

            <hr class="my-6 border-[#E5E7EB]">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div class="flex items-start gap-3">
                    <div class="p-2.5 rounded-lg bg-[#F7F8FC] border border-[#E5E7EB] text-[#1A2B5C] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Waktu Pelaksanaan</h4>
                        <p class="text-sm font-bold text-[#1C1E2C] mt-0.5">{{ \Carbon\Carbon::parse($daftarKegiatan['tanggal_kegiatan'])->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-[#6B7280]">{{ \Carbon\Carbon::parse($daftarKegiatan['waktu_kegiatan'])->format('H:i') }} - Selesai WITA</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-2.5 rounded-lg bg-[#F7F8FC] border border-[#E5E7EB] text-[#1A2B5C] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 14.25-4.821Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Lokasi / Tempat</h4>
                        <p class="text-sm font-bold text-[#1C1E2C] mt-0.5"> {{ $daftarKegiatan['lokasi'] }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 sm:col-span-2 md:col-span-1">
                    <div class="p-2.5 rounded-lg bg-[#F7F8FC] border border-[#E5E7EB] text-[#1A2B5C] shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider">Kuota Batas</h4>
                        <p class="text-sm font-bold text-[#1C1E2C] mt-0.5">{{ $daftarKegiatan['kuota_peserta'] }} Peserta</p>
                        @php
                            $sisa = $daftarKegiatan['kuota_peserta'] - $daftarKegiatan->pendaftaran_peserta_kegiatan_count
                        @endphp
                        <p class="text-xs text-[#166534] font-medium">Sisa {{ $sisa }} kursi tersedia</p>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-[#E5E7EB]">

            <div class="space-y-3">
                <h3 class="text-base font-bold text-[#1C1E2C]">Deskripsi Kegiatan</h3>
                <p class="text-sm text-[#6B7280] leading-relaxed text-justify">
                   {{ $daftarKegiatan['deskripsi'] }}
                </p>
            </div>

            {{-- <div class="mt-8 p-4 rounded-xl border border-blue-100 bg-blue-50/50">
                <h4 class="text-sm font-bold text-[#1A2B5C] flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    Catatan Evaluasi Kegiatan:
                </h4>
                <p class="text-xs text-[#6B7280] mt-1.5 leading-relaxed">
                    Belum ada data evaluasi resmi untuk kegiatan ini karena pelaksanaan masih berjalan.
                </p>
            </div> --}}

        </div>

        <div class="bg-[#F7F8FC] border-t border-[#E5E7EB] px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-center sm:text-left">
                <p class="text-xs text-[#6B7280]">Dipublikasikan pada</p>
                <p class="text-xs font-semibold text-[#1C1E2C]">
                    {{ \Carbon\Carbon::parse($daftarKegiatan['created_at'])->translatedFormat('d F Y') }}
                </p>
            </div>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
               <form action="{{ route('mahasiswa.daftar-kegiatan.daftar', $daftarKegiatan->id) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all h-9 rounded-lg px-4 w-full md:w-auto bg-[#F5A623] hover:bg-[#D88E15] text-white font-semibold shadow-sm">
                        Daftar
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection