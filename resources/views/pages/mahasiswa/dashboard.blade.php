@extends('layouts.app')
@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- ── SAMBUTAN ─────────────────────────────────────────── --}}
    <div class="bg-gradient-to-r from-[#1A2B5C] to-[#0F1B3D] rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full bg-[#F5A623]/10 blur-2xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-6 opacity-10 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
            </svg>
        </div>
        <div class="relative">
            <div class="text-sm text-white/60 mb-1">Selamat datang kembali,</div>
            <h2 class="text-2xl font-bold">{{ auth()->user()->name ?? 'Mahasiswa' }} 👋</h2>
            <p class="text-white/70 text-sm mt-1">
                {{ auth()->user()->prodi ?? 'Sistem Informasi' }} · Semester {{ auth()->user()->semester ?? '?' }}
            </p>
            <div class="mt-4 flex gap-5">
                <div>
                    <div class="text-xl font-bold text-[#F5A623]">{{ $stats['kegiatan_diikuti'] ?? 3 }}</div>
                    <div class="text-xs text-white/60">Kegiatan Diikuti</div>
                </div>
                <div>
                    <div class="text-xl font-bold text-[#00C9A7]">{{ $stats['kegiatan_aktif'] ?? 1 }}</div>
                    <div class="text-xs text-white/60">Sedang Berlangsung</div>
                </div>
                <div>
                    <div class="text-xl font-bold text-white">{{ $stats['ormawa_aktif'] ?? 25 }}</div>
                    <div class="text-xs text-white/60">Ormawa Aktif</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STAT CARDS ─────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @include('components.stat-card', ['label' => 'Total Ormawa',  'value' => $stats['total_ormawa'] ?? '25',  'icon' => 'building',  'color' => 'navy'])
        @include('components.stat-card', ['label' => 'Kegiatan Aktif','value' => $stats['kegiatan_aktif'] ?? '8', 'icon' => 'calendar',  'color' => 'teal'])
        @include('components.stat-card', ['label' => 'Saya Ikuti',    'value' => $stats['kegiatan_diikuti'] ?? '3','icon' => 'check',     'color' => 'gold'])
        @include('components.stat-card', ['label' => 'Selesai',       'value' => $stats['kegiatan_selesai'] ?? '2','icon' => 'shield',    'color' => 'green'])
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