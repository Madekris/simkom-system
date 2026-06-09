{{--
    KOMPONEN: stat-card.blade.php
    Props (via @include atau Blade Component):
        $label  : string — label di atas angka
        $value  : string — nilai utama
        $icon   : string — nama ikon SVG inline (lihat ikon di bawah)
        $color  : string — navy|teal|gold|green|red|purple
        $change : string (opsional) — "+12%" atau "-3%"
        $trend  : string (opsional) — up|down

    Contoh:
    @include('components.stat-card', [
        'label' => 'Total Ormawa',
        'value' => '25',
        'icon'  => 'building',
        'color' => 'navy',
    ])
--}}

@php
    $colorMap = [
        'navy'   => ['bg' => 'bg-[#1A2B5C]',  'text' => 'text-white',         'ring' => 'ring-[#1A2B5C]/10'],
        'teal'   => ['bg' => 'bg-[#00C9A7]',  'text' => 'text-white',         'ring' => 'ring-[#00C9A7]/10'],
        'gold'   => ['bg' => 'bg-[#F5A623]',  'text' => 'text-[#1A2B5C]',    'ring' => 'ring-[#F5A623]/10'],
        'green'  => ['bg' => 'bg-[#22C55E]',  'text' => 'text-white',         'ring' => 'ring-[#22C55E]/10'],
        'red'    => ['bg' => 'bg-[#EF4444]',  'text' => 'text-white',         'ring' => 'ring-[#EF4444]/10'],
        'purple' => ['bg' => 'bg-[#7C3AED]',  'text' => 'text-white',         'ring' => 'ring-[#7C3AED]/10'],
    ];
    $c = $colorMap[$color ?? 'navy'];

    $icons = [
        'building' => '<path d="M2 20h20M4 20V10l8-6 8 6v10M10 20v-5h4v5"/>',
        'users'    => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
        'warning'  => '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>',
        'wallet'   => '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/>',
        'trending' => '<polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>',
        'check'    => '<polyline points="20 6 9 17 4 12"/>',
        'scroll'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
        'activity' => '<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>',
        'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
    ];
    $svg = $icons[$icon ?? 'activity'] ?? $icons['activity'];
@endphp

<div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5
            hover:shadow-md transition-shadow duration-200 group">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">{{ $label ?? 'Label' }}</div>
            <div class="text-2xl font-bold text-[#1C1E2C] mt-1 leading-tight">{{ $value ?? '0' }}</div>
            @if(isset($change))
                <div class="flex items-center gap-1 mt-1.5">
                    @if(($trend ?? 'up') === 'up')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[#22C55E]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
                        <span class="text-xs font-semibold text-[#22C55E]">{{ $change }}</span>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-[#EF4444]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        <span class="text-xs font-semibold text-[#EF4444]">{{ $change }}</span>
                    @endif
                    <span class="text-xs text-[#9CA3AF]">dari bulan lalu</span>
                </div>
            @endif
        </div>
        <div class="w-11 h-11 rounded-lg flex items-center justify-center shrink-0 {{ $c['bg'] }} {{ $c['text'] }}
                    group-hover:scale-110 transition-transform duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round">
                {!! $svg !!}
            </svg>
        </div>
    </div>
</div>
