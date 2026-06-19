@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Dashboard Pengurus')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', $ormawa->first()->nama)

@section('content')

<div class="p-4 sm:p-6 lg:p-8 space-y-6 h-full flex flex-col">
    <div class="bg-gradient-to-r from-[#1A2B5C] to-[#0F1B3D] rounded-xl p-6 text-white flex items-center justify-between">
        <div>
            <div class="text-sm text-white/70">Selamat pagi,</div>
            <div class="text-2xl font-bold">{{ $namaUser }} 👋</div>
            <div class="text-sm text-white/70 mt-1">
                Anda memiliki <span class="text-[#F5A623] font-bold">{{ $kegiatanAktif }} kegiatan aktif</span> 
                dan <span class="text-[#F5A623] font-bold">{{ $tugasPending }} kegiatan pending</span>
            </div>
        </div>
        
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell w-12 h-12 text-[#F5A623]">
            <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
            <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
        </svg>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Total Anggota</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">{{ $totalAnggota }}</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#1A2B5C] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Kegiatan Aktif</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">{{ $kegiatanAktif }}</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#00C9A7] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-range w-5 h-5"><rect width="18" height="18" x="3" y="4" rx="2"></rect><path d="M16 2v4"></path><path d="M3 10h18"></path><path d="M8 2v4"></path><path d="M17 14h-6"></path><path d="M13 18H7"></path><path d="M7 14h.01"></path><path d="M17 18h.01"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Selesai Bulan Ini</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">{{ $selesaiBulanIni }}</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#22C55E] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check w-5 h-5"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Dokumen</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">{{ $totalDokumen }}</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#F5A623] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-5 h-5"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                </div>
            </div>
        </div>

    </div>


    @php
        // 1. Ambil ID organisasi tempat mahasiswa aktif (Gunakan variabel ini jika belum dideklarasikan sebelumnya)
        
    @endphp

    <div class="grid flex-1 grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">
        
        <div class="bg-white h-full rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] lg:col-span-2 p-6 flex flex-col h-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#1C1E2C]">Kegiatan Mendatang</h3>
                <a href="#" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all h-8 rounded-md px-3 text-[#1A2B5C] hover:bg-[#1A2B5C]/5">
                    Lihat Semua
                </a>
            </div>
            
            <div class="space-y-3 flex-1">
                @forelse($kegiatanMendatang as $kegiatan)
                    @php
                        // Parsing tanggal menggunakan Carbon untuk kebutuhan badge kalender mini
                        $carbonDate = \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan);
                        $bulanAbbr  = strtoupper($carbonDate->translatedFormat('M')); // Hasil: JUN, JUL, etc.
                        $tanggalNum = $carbonDate->format('j'); // Hasil: 1, 12, 20
                    @endphp
                    
                    <div class="flex items-center gap-4 p-4 rounded-lg bg-[#F7F8FC]">
                        <div class="w-12 h-12 rounded-lg bg-[#1A2B5C] text-white flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] leading-none mb-0.5">{{ $bulanAbbr }}</span>
                            <span class="font-bold text-base leading-none">{{ $tanggalNum }}</span>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-[#1C1E2C] truncate">{{ $kegiatan->judul_kegiatan }}</div>
                            <div class="text-xs text-[#6B7280] flex items-center gap-3 mt-1 flex-wrap">
                                <span class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-3 h-3"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    {{ $carbonDate->translatedFormat('j M Y') }}
                                </span>
                                <span class="flex items-center gap-1 truncate">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-3 h-3"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    {{ $kegiatan->lokasi ?? 'Aula' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($kegiatan->status === 'Berlangsung')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#CCFBF1] text-[#0F766E] shrink-0">
                                Berlangsung
                            </span>
                        @elseif($kegiatan->status === 'Mendatang')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#E0E7FF] text-[#3730A3] shrink-0">
                                Mendatang
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 text-xs text-[#6B7280]">Belum ada agenda kegiatan mendatang.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border h-fit border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 flex flex-col h-full justify-between">
            <div>
                <h3 class="font-bold text-[#1C1E2C] mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href= "{{ route('pengurus.kegiatan.create') }}"class="inline-flex items-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all h-9 px-4 py-2 w-full justify-start bg-[#F5A623] hover:bg-[#D88E15] text-[#1A2B5C]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4 mr-1"><path d="M5 12h14"></path><path d="M12 5v14"></path></svg> 
                        Buat Kegiatan
                    </a>
                    <a href="{{ route('pengurus.verifikasi.index') }}" class="inline-flex items-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-[#F7F8FC] h-9 px-4 py-2 w-full justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4 mr-1"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> 
                        Verifikasi Anggota
                    </a>
                    <button class="inline-flex items-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-[#F7F8FC] h-9 px-4 py-2 w-full justify-start">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload w-4 h-4 mr-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" x2="12" y1="3" y2="15"></line></svg> 
                        Upload Dokumen
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection