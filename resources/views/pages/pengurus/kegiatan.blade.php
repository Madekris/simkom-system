@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Kegiatan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Kelola seluruh kegiatan ormawa Anda')

@section('topbar_actions')
<div class="flex items-center gap-2 sm:gap-3">
    <a href="{{ route('pengurus.kegiatan.create') }}" class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap rounded-md text-sm font-semibold transition-colors h-9 px-4 py-2 bg-[#F5A623] hover:bg-[#D88E15] text-[#1A2B5C]">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4">
            <path d="M5 12h14"></path>
            <path d="M12 5v14"></path>
        </svg>
        
        Buat Kegiatan
    </a>

    <div class="relative w-64 hidden xl:block">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280]">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.3-4.3"></path>
        </svg>

        <input 
            type="text" 
            placeholder="Cari..." 
            class="flex h-9 w-full min-w-0 rounded-md bg-[#F7F8FC] border-0 pl-9 pr-3 py-1 text-sm text-[#1C1E2C] placeholder-[#6B7280] outline-none transition-all focus:ring-1 focus:ring-[#1A2B5C] disabled:pointer-events-none disabled:opacity-50"
        >
    </div>

</div>
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-4">
    <div class="flex gap-2">
        <button class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-[#1A2B5C] text-white">
            Semua
        </button>

        <button class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-white text-[#6B7280] border border-[#E5E7EB] hover:border-[#1A2B5C]">
            Berlangsung
        </button>

        <button class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-white text-[#6B7280] border border-[#E5E7EB] hover:border-[#1A2B5C]">
            Selesai
        </button>

        <button class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors bg-white text-[#6B7280] border border-[#E5E7EB] hover:border-[#1A2B5C]">
            Dibatalkan
        </button>
    </div>



    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        @forelse ( $kegiatan as $item )
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 hover:shadow-lg transition cursor-pointer">
            <div>
                <div class="flex items-start justify-between">
                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#1A2B5C] to-[#0F1B3D] text-white flex flex-col items-center justify-center">
                        <span class="text-[10px]">JUN</span>
                        <span class="font-bold">12</span>
                    </div>
                    
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#CCFBF1] text-[#0F766E]">
                        Berlangsung
                    </span>
                </div>

                <h3 class="font-bold text-[#1C1E2C] mt-4">{{ $item->judul_kegiatan }}</h3>

                <div class="text-xs text-[#6B7280] flex items-center gap-3 mt-2">
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-map-pin w-3 h-3">
                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Aula SIMKOM
                    </span>

                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-3 h-3">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        80 peserta
                    </span>
                </div>

                <div class="mt-4 pt-4 border-t border-[#E5E7EB] flex items-center justify-between">
                    <div class="flex -space-x-2">
                        <div class="w-7 h-7 rounded-full bg-[#F5A623] border-2 border-white flex items-center justify-center text-[10px] font-bold text-[#1A2B5C]">A</div>
                        <div class="w-7 h-7 rounded-full bg-[#F5A623] border-2 border-white flex items-center justify-center text-[10px] font-bold text-[#1A2B5C]">S</div>
                        <div class="w-7 h-7 rounded-full bg-[#F5A623] border-2 border-white flex items-center justify-center text-[10px] font-bold text-[#1A2B5C]">R</div>
                        <div class="w-7 h-7 rounded-full bg-[#F5A623] border-2 border-white flex items-center justify-center text-[10px] font-bold text-[#1A2B5C]">M</div>
                    </div>

                    <button class="inline-flex items-center justify-center text-sm font-semibold text-[#1A2B5C] hover:text-[#0F1B3D] transition-colors h-8 rounded-md">
                        Detail →
                    </button>
                </div>
            </div>
        </div>
        @empty
            
        @endforelse

    </div>
</div>
@endsection