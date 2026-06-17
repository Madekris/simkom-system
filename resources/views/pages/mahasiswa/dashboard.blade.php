@extends('layouts.app')
@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- ── SAMBUTAN ─────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-[#1A2B5C] to-[#0F1B3D] rounded-2xl text-white relative overflow-hidden">
       
        <div class="absolute bottom-0 right-6 opacity-10 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>

        <div class="bg-gradient-to-r from-[#F5A623] to-[#E8901B] rounded-xl p-6 text-[#1A2B5C]">
            {{-- Menggunakan Nama User Dinamis --}}
            <div class="text-sm font-semibold">Halo, {{ $dataMahasiswa->nama }} 👋</div>
            
            <div class="text-2xl font-bold mt-1">Selamat datang di SIMKOM!</div>
            
            {{-- Menggunakan Hasil Count dari Controller --}}
            <div class="text-sm mt-1 text-[#1A2B5C]/80">
                Anda terdaftar di <strong>{{ $totalOrmawa }}</strong> ormawa dengan <strong>{{ $totalKegiatanMendatang }}</strong> kegiatan mendatang.
            </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ── KEGIATAN BERLANGSUNG ─────────────────────────── --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-[#E5E7EB] shadow-card p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-[#1C1E2C]">Kegiatan Berlangsung</h3>
                    <p class="text-xs text-[#6B7280] mt-0.5">Kegiatan yang sedang aktif saat ini</p>
                </div>
                <a 
                   class="text-xs text-[#1A2B5C] font-semibold hover:underline">Lihat semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($kegiatanAktif ?? [] as $k)
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-[#F7F8FC] hover:bg-[#EFF1F8] transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-[#1A2B5C] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F5A623]" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm text-[#1C1E2C] truncate">{{ $k->judul }}</div>
                            <div class="text-xs text-[#6B7280] mt-0.5">
                                {{ $k->ormawa->nama ?? '-' }} · {{ $k->lokasi }}
                            </div>
                            <div class="text-xs text-[#6B7280] mt-0.5">
                                {{ $k->tanggal_mulai?->format('d M Y') }}
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            @include('components.status-badge', ['status' => $k->status])
                            <a 
                               class="text-xs text-[#1A2B5C] hover:underline font-medium">Detail →</a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-sm text-[#6B7280]">
                        Tidak ada kegiatan berlangsung saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── KEGIATAN SAYA ────────────────────────────────── --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#1C1E2C]">Kegiatan Saya</h3>
                <a 
                   class="text-xs text-[#1A2B5C] font-semibold hover:underline">Lihat →</a>
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