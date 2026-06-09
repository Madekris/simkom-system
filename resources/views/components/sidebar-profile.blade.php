{{--
    KOMPONEN: sidebar-profile.blade.php
    Gunakan di dalam sidebar (layouts/app.blade.php)
    Variabel tersedia dari Auth::user() atau dari @yield di child view
--}}
<div class="px-4 py-4 border-b border-white/10">
    <div class="flex items-center gap-3">
        {{-- Avatar --}}
        <div class="relative shrink-0">
            <div class="w-11 h-11 rounded-full bg-[#F5A623] text-[#1A2B5C] font-bold
                        flex items-center justify-center text-sm overflow-hidden border-2 border-[#F5A623]/30">
                @if(Auth::user()->photo ?? false)
                    <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                         alt="{{ Auth::user()->name }}"
                         class="w-full h-full object-cover">
                @else
                    {{-- Inisial nama --}}
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name ?? 'User')[1] ?? '', 0, 1)) }}
                @endif
            </div>
            {{-- Indikator online --}}
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-[#22C55E] rounded-full border-2 border-[#1A2B5C]"></span>
        </div>

        {{-- Info user --}}
        <div class="min-w-0 flex-1">
            <div class="text-sm font-semibold truncate leading-tight">
                {{ Auth::user()->name ?? 'Pengguna' }}
            </div>
            <div class="text-[11px] text-white/50 truncate mt-0.5">
                {{ Auth::user()->nim ?? Auth::user()->nip ?? '' }}
            </div>
        </div>
    </div>
</div>
