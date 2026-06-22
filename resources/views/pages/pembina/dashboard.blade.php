@extends('layouts.app')

@section('topbar_title', 'Dashboard Utama')
@section('topbar_subtitle', 'Ringkasan ormawa yang Anda bina')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 flex flex-col h-full">

    <div class="bg-gradient-to-r from-[#1A2B5C] to-[#0F1B3D] rounded-xl p-6 text-white flex items-center justify-between">
        <div>
            <div class="text-sm text-white/70">Selamat datang,</div>
            <div class="text-2xl font-bold">{{ $namaUser }} 👋</div>
            <div class="text-sm text-white/70 mt-1">
                Anda membina <span class="text-[#F5A623] font-bold">{{ $totalOrmawaBinaan }} ormawa</span> 
                dengan <span class="text-[#F5A623] font-bold">{{ $totalKegiatanPending }} kegiatan</span> menunggu persetujuan.
            </div>
        </div>
        
        <a href="{{ route('pembina.persetujuan-kegiatan.index') }}" data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 outline-none h-9 px-4 py-2 bg-[#F5A623] hover:bg-[#D88E15] text-[#1A2B5C]">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-check w-4 h-4 mr-1">
                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <path d="m9 14 2 2 4-4"></path>
            </svg> 
            Review Sekarang
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Ormawa Binaan</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">{{ $totalOrmawaBinaan }}</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#1A2B5C] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 w-5 h-5"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path><path d="M10 6h4"></path><path d="M10 10h4"></path><path d="M10 14h4"></path><path d="M10 18h4"></path></svg>
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
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Menunggu Review</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">{{ $menungguReview }}</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#F5A623] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert w-5 h-5"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Disetujui Bulan Ini</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">{{ $disetujuiBulanIni }}</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#22C55E] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check w-5 h-5"><circle cx="12" cy="12" r="10"></circle><path d="m9 12 2 2 4-4"></path></svg>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch flex-1">
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 flex flex-col h-full">
            <h3 class="font-bold text-[#1C1E2C] mb-3">Ormawa yang Dibina</h3>
            <div class="space-y-3 flex-1">
                @forelse($listOrmawaBinaan as $ormawa)
                    <div class="flex items-center gap-3 p-4 rounded-lg bg-[#F7F8FC]">
                        <div class="flex-1">
                            <div class="font-semibold text-[#1C1E2C]">{{ $ormawa->nama }}</div>
                            <div class="text-xs text-[#6B7280]">
                                {{ $ormawa->total_anggota ?? 0 }} anggota · {{ $ormawa->kegiatan_aktif_count ?? 0 }} kegiatan aktif
                            </div>
                        </div>
                        <a href="{{ route('pembina.ormawa-binaan.index') }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all h-8 rounded-md px-3 text-[#1A2B5C] hover:bg-[#1A2B5C]/5">
                            Lihat
                        </a>
                    </div>
                @empty
                    <div class="text-center py-8 text-xs text-[#6B7280]">Belum ada organisasi binaan.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 flex flex-col h-full">
            <h3 class="font-bold text-[#1C1E2C] mb-3">Persetujuan Mendesak</h3>
            <div class="space-y-3 flex-1">
                @forelse($kegiatanMendesak as $kegiatan)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-[#FEF3C7]/40 border border-[#FDE68A]">
                        <div>
                            <div class="font-semibold text-sm text-[#1C1E2C]">{{ $kegiatan->judul_kegiatan }}</div>
                            <div class="text-xs text-[#6B7280]">
                                {{ $kegiatan->organisasi->nama }} · {{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('j M Y') }}
                            </div>
                        </div>
                        <a href="#" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all border h-8 rounded-md px-3 border-[#F5A623] text-[#92400E] bg-white hover:bg-[#FEF3C7]/60">
                            Review
                        </a>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center h-full min-y-[150px] py-6 text-center">
                        <div class="w-10 h-10 rounded-full bg-green-50 text-[#22C55E] flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path><path d="m9 12 2 2 4-4"></path></svg>
                        </div>
                        <div class="text-xs font-bold text-[#1C1E2C]">Tidak ada persetujuan mendesak</div>
                        <p class="text-[11px] text-[#6B7280]">Semua proposal kegiatan ORMAWA telah ditinjau.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection