@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Persetujuan Kegiatan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Review proposal kegiatan ormawa binaan')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-4">
    @forelse ($kegiatan as $k)
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 flex flex-col sm:flex-row items-start sm:items-center gap-5 transition-all hover:shadow-[0_4px_20px_rgba(0,0,0,0.06)]">
            
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#F5A623] to-[#D88E15] text-white flex items-center justify-center shrink-0 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clipboard-check w-6 h-6">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <path d="m9 14 2 2 4-4"></path>
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="font-bold text-[#1C1E2C] truncate text-base">{{ $k->judul_kegiatan }}</h3>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEF3C7] text-[#92400E]">
                        {{ $k->status }} </span>
                </div>
                
                <div class="text-xs text-[#6B7280] mt-2 flex flex-wrap gap-x-4 gap-y-1 items-center">
                    <span class="font-medium text-gray-700">{{ $k->organisasi->nama ?? 'Ormawa' }}</span>
                    <span class="flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar w-3.5 h-3.5 text-gray-400">
                            <path d="M8 2v4"></path>
                            <path d="M16 2v4"></path>
                            <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                            <path d="M3 10h18"></path>
                        </svg>
                        {{ \Carbon\Carbon::parse($k->tanggal_kegiatan)->translatedFormat('d M Y') }}
                    </span>
                    <span>Diajukan: {{ \Carbon\Carbon::parse($k->created_at)->translatedFormat('d M Y') }}</span>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end border-t sm:border-none pt-3 sm:pt-0 mt-2 sm:mt-0">
                <a href="" class="inline-flex h-8 items-center justify-center rounded-md border border-gray-200 bg-white px-3 text-sm font-medium text-gray-700 transition-all outline-none hover:bg-gray-50 focus-visible:ring-2 focus-visible:ring-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4 mr-1.5 text-gray-500">
                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    Detail
                </a>
                
                <form action="{{ route('pembina.setStatus.setStatus', $k->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Dibatalkan" id="">
                    <button type="submit" class="inline-flex h-8 items-center justify-center rounded-md border border-[#EF4444] bg-white px-3 text-sm font-medium text-[#EF4444] transition-all outline-none hover:bg-[#EF4444]/5 focus-visible:ring-2 focus-visible:ring-[#EF4444]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x w-4 h-4 mr-1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m15 9-6 6"></path>
                            <path d="m9 9 6 6"></path>
                        </svg>
                        Tolak
                    </button>
                </form>

                <form action="{{ route('pembina.setStatus.setStatus', $k->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Mendatang" id="">
                    <button type="submit" class="inline-flex h-8 items-center justify-center rounded-md bg-[#22C55E] px-3 text-sm font-medium text-white transition-all outline-none hover:bg-[#16A34A] focus-visible:ring-2 focus-visible:ring-[#22C55E]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check w-4 h-4 mr-1.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="m9 12 2 2 4-4"></path>
                        </svg>
                        Setujui
                    </button>
                </form>
            </div>

        </div>
    @empty
        <div class="flex flex-col items-center justify-center bg-white rounded-xl border border-[#E5E7EB] border-dashed p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-[#F7F8FC] rounded-full flex items-center justify-center mb-4 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox w-8 h-8">
                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                    <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                </svg>
            </div>
            <h4 class="font-bold text-[#1C1E2C] text-lg">Tidak Ada Pengajuan</h4>
            <p class="text-sm text-[#6B7280] mt-1 max-w-sm">Saat ini belum ada pengajuan kegiatan dengan status pending yang memerlukan persetujuan Anda.</p>
        </div>
    @endforelse
</div>
@endsection