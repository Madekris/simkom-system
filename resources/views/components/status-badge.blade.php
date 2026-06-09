{{--
    KOMPONEN: status-badge.blade.php
    Props: $status (string)
    Contoh: @include('components.status-badge', ['status' => $kegiatan->status])

    Status yang didukung:
    Aktif, Berlangsung, Selesai, Dibatalkan, Tidak Aktif, Menunggu, Dikonfirmasi, Pending, Surplus, Defisit
--}}
@php
    $styles = [
        'Aktif'        => 'bg-[#DCFCE7] text-[#166534]',
        'Berlangsung'  => 'bg-[#CCFBF1] text-[#0F766E]',
        'Selesai'      => 'bg-[#E5E7EB] text-[#374151]',
        'Dibatalkan'   => 'bg-[#FEE2E2] text-[#991B1B]',
        'Tidak Aktif'  => 'bg-[#E5E7EB] text-[#6B7280]',
        'Menunggu'     => 'bg-[#FEF3C7] text-[#92400E]',
        'Dikonfirmasi' => 'bg-[#DCFCE7] text-[#166534]',
        'Pending'      => 'bg-[#FEF3C7] text-[#92400E]',
        'Surplus'      => 'bg-[#DCFCE7] text-[#166534]',
        'Defisit'      => 'bg-[#FEE2E2] text-[#991B1B]',
        'Disetujui'    => 'bg-[#DCFCE7] text-[#166534]',
        'Ditolak'      => 'bg-[#FEE2E2] text-[#991B1B]',
        'Draft'        => 'bg-[#F3F4F6] text-[#6B7280]',
    ];
    $cls = $styles[$status ?? ''] ?? 'bg-[#E5E7EB] text-[#374151]';
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">
    {{ $status ?? '-' }}
</span>
