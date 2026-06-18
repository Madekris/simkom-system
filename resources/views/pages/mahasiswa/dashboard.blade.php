@extends('layouts.app')
@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6 flex flex-col h-full">

    {{-- ── SAMBUTAN ─────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-[#1A2B5C] to-[#2B3F7A] rounded-xl p-6 text-white">
        <div class="text-sm font-semibold opacity-80">
            Halo, {{ $dataMahasiswa->nama }} 👋
        </div>
        <div class="text-2xl font-bold mt-1">Selamat datang di SIMKOM!</div>
        <div class="text-sm mt-1 opacity-70">
            Anda terdaftar di <span class="font-bold">{{ $totalOrmawa }}</span> ormawa dengan <span class="font-bold">{{ $totalKegiatanMendatang }}</span> kegiatan mendatang.
        </div>
    </div>
    {{-- ── STAT CARDS ─────────────────────────────────────────── --}}
    
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
    
        {{-- Card 1: Ormawa Diikuti --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">
                        Ormawa Diikuti
                    </div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">
                        {{ $totalOrmawa ?? 0 }}
                    </div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#1A2B5C] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2 w-5 h-5">
                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                        <path d="M10 6h4"></path>
                        <path d="M10 10h4"></path>
                        <path d="M10 14h4"></path>
                        <path d="M10 18h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 2: Kegiatan Mendatang --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">
                        Kegiatan Mendatang
                    </div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">
                        {{ $totalKegiatanMendatang ?? 0 }}
                    </div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#00C9A7] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-range w-5 h-5">
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <path d="M8 2v4"></path>
                        <path d="M17 14h-6"></path>
                        <path d="M13 18H7"></path>
                        <path d="M7 14h.01"></path>
                        <path d="M17 18h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Card 3: Selesai Diikuti --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">
                        Selesai Diikuti
                    </div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">
                        {{ $totalSemuaKegiatanSelesai ?? 0 }}
                    </div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#22C55E] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check w-5 h-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <div class="flex flex-col md:flex-row gap-5 flex-1">

        {{-- ── KEGIATAN BERLANGSUNG ─────────────────────────── --}}
        <div class="flex-1 bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
            <h3 class="font-bold text-[#1C1E2C] mb-3">Ormawa Saya</h3>
            <div class="space-y-3">
                @foreach($ormawaMahasiswa as $ormawa)
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-[#F7F8FC]">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-amber-200 bg-amber-100 font-bold text-xl text-amber-600 shadow-sm">
                           {{ Str::upper(Str::substr($ormawa->organisasi->nama, 0, 1)) }}
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm text-[#1C1E2C] truncate">{{ $ormawa->organisasi->nama }}</div>
                            <div class="text-xs text-[#6B7280]">{{ ucfirst($ormawa->jabatan) }} sejak {{ \Carbon\Carbon::parse($ormawa->created_at)->format('Y') }}</div>
                        </div>
                        <a href="{{ route('mahasiswa.organisasi.show', ['id' => $ormawa->organisasi->id]) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all h-8 rounded-md px-3 bg-[#1A2B5C] hover:bg-[#0F1D3D] text-white shrink-0">
                            Lihat
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── KEGIATAN SAYA ────────────────────────────────── --}}
        <div class="bg-white flex-1 rounded-xl border border-[#E5E7EB] shadow-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#1C1E2C]">Kegiatan Saya</h3>
                <a href="{{ route('mahasiswa.kegiatan-saya') }}"
                   class="text-xs text-[#1A2B5C] font-semibold cursor-pointer hover:underline">Lihat →</a>
            </div>
            <div class="space-y-2.5">
                @forelse($kegiatanSaya ?? [] as $k)
                    <div class="p-3 rounded-lg bg-[#F7F8FC]">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-semibold text-[#1C1E2C] truncate">{{ $k->kegiatan->judul ?? $k->judul }}</div>
                                <div class="text-xs text-[#6B7280] mt-0.5">{{ $k->kegiatan->ormawa->nama ?? $k->ormawa ?? '-' }}</div>
                                <div class="text-xs text-[#9CA3AF] mt-0.5">{{ $k->kegiatan->tanggal_mulai?->format('d M Y') ?? $k->tgl }}</div>
                            </div>
                            @include('components.status-badge', ['status' => $k->status ?? 'Dikonfirmasi'])
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-sm text-[#6B7280]">
                        Belum ada kegiatan yang diikuti.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection