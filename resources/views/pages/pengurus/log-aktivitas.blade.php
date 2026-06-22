@extends('layouts.app')

@section('topbar_title', 'Log Aktivitas')
@section('topbar_subtitle', 'Riwayat tindakan yang Anda lakukan di sistem')

@section('topbar_actions')
    <a href="{{ route('pengurus.log-aktivitas.export-pdf') }}"
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

<div class="p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-[#F7F8FC] text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="w-[190px] text-left px-5 py-3 font-semibold tracking-wide">Waktu</th>
                        <th class="w-[110px] text-left px-5 py-3 font-semibold tracking-wide">Aksi</th>
                        <th class="w-[130px] text-left px-5 py-3 font-semibold tracking-wide">Kategori</th>
                        <th class="text-left px-5 py-3 font-semibold tracking-wide">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    @forelse($logs as $log)
                        @php
                            [$badgeLabel, $badgeClass] = $eventBadge[$log->event] ?? ['Aksi', 'bg-[#F3F4F6] text-[#374151]'];
                            $kategori = $kategoriMap[$log->log_name] ?? $log->log_name;
                        @endphp
                        <tr class="hover:bg-[#F9FAFB] transition-colors">
                            <td class="px-5 py-3.5 text-[#6B7280] whitespace-nowrap text-xs">
                                {{ $log->created_at->translatedFormat('j M Y, H:i') }} WITA
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
                            <td colspan="4" class="py-14 text-center">
                                <div class="flex flex-col items-center gap-2 text-[#6B7280]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-[#D1D5DB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada aktivitas yang tercatat</p>
                                    <p class="text-xs">Aktivitas Anda akan muncul di sini secara otomatis</p>
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
