@extends('layouts.app')

@section('topbar_title', 'Log Aktivitas Sistem')
@section('topbar_subtitle', 'Audit trail seluruh tindakan pengguna di sistem')

@section('topbar_actions')
    <a href="{{ route('admin.log-aktivitas.export-pdf') }}?{{ http_build_query(request()->only(['log_name', 'dari', 'sampai'])) }}"
       class="inline-flex items-center justify-center text-sm font-medium transition-all h-9 rounded-lg px-4 gap-2 border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-[#F7F8FC] shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#6B7280]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Export PDF
    </a>
@endsection

@section('content')
@php
$kategoriMap = [
    'akun_user'            => 'Akun',
    'manajemen_organisasi' => 'Organisasi',
    'manajemen_kegiatan'   => 'Kegiatan',
    'anggota_organisasi'   => 'Anggota',
    'verifikasi_anggota'   => 'Verifikasi',
    'pendaftaran_kegiatan' => 'Pendaftaran',
    'dokumen_kegiatan'     => 'Dokumen',
    'input_keuangan'       => 'Keuangan',
];
$eventBadge = [
    'created' => ['Tambah',   'bg-[#DCFCE7] text-[#166534]'],
    'updated' => ['Perbarui', 'bg-[#EFF6FF] text-[#1E40AF]'],
    'deleted' => ['Hapus',    'bg-[#FEE2E2] text-[#991B1B]'],
];
@endphp

<div class="p-4 sm:p-6 lg:p-8 space-y-5">

    {{-- ── STAT CARDS ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#EFF1F8] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1A2B5C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-[#6B7280]">Log Hari Ini</p>
                <p class="text-2xl font-bold text-[#1C1E2C]">{{ number_format($totalHariIni) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#EFF6FF] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1E40AF]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-[#6B7280]">Log 7 Hari Terakhir</p>
                <p class="text-2xl font-bold text-[#1C1E2C]">{{ number_format($total7Hari) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#DCFCE7] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#166534]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-[#6B7280]">Total Semua Log</p>
                <p class="text-2xl font-bold text-[#1C1E2C]">{{ number_format($totalSemua) }}</p>
            </div>
        </div>
    </div>

    {{-- ── FILTER BAR ───────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.log-aktivitas.index') }}"
          class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] px-5 py-4">
        <div class="flex flex-wrap gap-3 items-end">

            <div class="flex flex-col gap-1 min-w-[160px]">
                <label class="text-xs font-medium text-[#6B7280]">Kategori Log</label>
                <select name="log_name"
                        class="h-9 rounded-lg border border-[#E5E7EB] text-sm px-3 text-[#1C1E2C] bg-white focus:outline-none focus:ring-2 focus:ring-[#1A2B5C]/20">
                    <option value="">Semua Kategori</option>
                    @foreach($logNames as $ln)
                        <option value="{{ $ln }}" {{ request('log_name') === $ln ? 'selected' : '' }}>
                            {{ $kategoriMap[$ln] ?? $ln }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-[#6B7280]">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}"
                       class="h-9 rounded-lg border border-[#E5E7EB] text-sm px-3 text-[#1C1E2C] bg-white focus:outline-none focus:ring-2 focus:ring-[#1A2B5C]/20">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-xs font-medium text-[#6B7280]">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                       class="h-9 rounded-lg border border-[#E5E7EB] text-sm px-3 text-[#1C1E2C] bg-white focus:outline-none focus:ring-2 focus:ring-[#1A2B5C]/20">
            </div>

            <div class="flex gap-2 ml-auto">
                <a href="{{ route('admin.log-aktivitas.index') }}"
                   class="h-9 inline-flex items-center px-4 rounded-lg border border-[#E5E7EB] text-sm text-[#6B7280] bg-white hover:bg-[#F7F8FC] transition-colors">
                    Reset
                </a>
                <button type="submit"
                        class="h-9 inline-flex items-center px-4 rounded-lg text-sm font-medium bg-[#1A2B5C] text-white hover:bg-[#0F1B3D] transition-colors">
                    Terapkan Filter
                </button>
            </div>
        </div>
    </form>

    {{-- ── TABLE ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-[#F7F8FC] text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="w-[175px] text-left px-5 py-3 font-semibold tracking-wide">Waktu</th>
                        <th class="w-[160px] text-left px-5 py-3 font-semibold tracking-wide">Pelaku</th>
                        <th class="w-[105px] text-left px-5 py-3 font-semibold tracking-wide">Aksi</th>
                        <th class="w-[125px] text-left px-5 py-3 font-semibold tracking-wide">Kategori</th>
                        <th class="text-left px-5 py-3 font-semibold tracking-wide">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($logs as $log)
                        @php
                            [$badgeLabel, $badgeClass] = $eventBadge[$log->event] ?? ['Aksi', 'bg-[#F3F4F6] text-[#374151]'];
                            $kategori = $kategoriMap[$log->log_name] ?? $log->log_name;
                            $causer   = $log->causer;
                        @endphp
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="px-5 py-3.5 text-[#6B7280] whitespace-nowrap text-xs">
                                {{ $log->created_at->translatedFormat('j M Y, H:i') }} WITA
                            </td>
                            <td class="px-5 py-3.5">
                                @if($causer)
                                    <p class="font-medium text-[#1C1E2C] leading-tight">{{ $causer->name }}</p>
                                    <p class="text-xs text-[#6B7280] capitalize mt-0.5">{{ $causer->role }}</p>
                                @else
                                    <span class="text-[#9CA3AF] text-xs italic">Sistem</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ $badgeLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#EFF1F8] text-[#1A2B5C]">
                                    {{ $kategori }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-[#1C1E2C]">
                                {{ $log->description }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-[#6B7280]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-sm font-medium">Tidak ada log yang sesuai filter</p>
                                    <p class="text-xs">Coba ubah rentang tanggal atau kategori log</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-5 py-4 border-t border-[#E5E7EB]">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
