@php
    $isActive = $active ?? false;
@endphp

<a href="{{ $href ?? '#' }}"
   class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all relative
          {{ $isActive
             ? 'bg-white/10 text-[#F5A623] font-semibold'
             : 'text-white/80 hover:bg-white/5 hover:text-white' }}">

    @if($isActive)
        <span class="absolute left-0 top-2 bottom-2 w-1 bg-[#F5A623] rounded-r"></span>
    @endif

    {!! $icon !!}

    {{-- <i class="{{ $icon ?? 'fas fa-chart-pie' }} w-4 h-4 shrink-0 flex items-center justify-center text-base"></i> --}}

    <span class="flex-1 text-left">{{ $label ?? 'Menu' }}</span>

    @if(isset($badge) && $badge)
        <span class="bg-[#F5A623] text-[#1A2B5C] text-[10px] px-1.5 py-0.5 rounded-full font-bold shrink-0">
            {{ $badge }}
        </span>
    @endif
</a>