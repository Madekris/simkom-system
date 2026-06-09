{{--
    KOMPONEN: notif-dropdown.blade.php
    Dropdown notifikasi di topbar, powered by Alpine.js
    Cara pakai: @include('components.notif-dropdown')
--}}
<div class="relative" x-data="{ open: false }">
    {{-- Tombol Bell --}}
    <button @click="open = !open"
            class="relative p-2 rounded-lg hover:bg-[#F7F8FC] text-[#6B7280] hover:text-[#1C1E2C] transition-colors"
            aria-label="Notifikasi">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
        {{-- Badge unread (dari session/controller) --}}
        @php $unreadCount = auth()->user()->unreadNotifications->count() ?? 0; @endphp
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 min-w-[16px] h-4 bg-[#EF4444] rounded-full
                         text-[9px] text-white font-bold flex items-center justify-center px-0.5">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Overlay klik luar --}}
    <div x-show="open" x-cloak @click.outside="open = false"
         class="fixed inset-0 z-40" style="display:none;"></div>

    {{-- Dropdown panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
         class="absolute right-0 top-full mt-2 z-50 w-80 bg-white rounded-xl shadow-xl
                border border-[#E5E7EB] overflow-hidden origin-top-right"
         style="display:none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-[#E5E7EB]">
            <div class="flex items-center gap-2">
                <span class="font-bold text-[#1C1E2C] text-sm">Notifikasi</span>
                @if($unreadCount > 0)
                    <span class="bg-[#EF4444] text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">
                        {{ $unreadCount }}
                    </span>
                @endif
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-xs text-[#1A2B5C] hover:underline font-semibold">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        {{-- List notifikasi --}}
        <div class="max-h-[340px] overflow-y-auto divide-y divide-[#F3F4F6]">
            @forelse(auth()->user()->notifications->take(10) ?? [] as $notif)
                <a href="{{ $notif->data['url'] ?? '#' }}"
                   class="flex gap-3 items-start px-4 py-3 hover:bg-[#F7F8FC] transition-colors
                          {{ $notif->read_at ? '' : 'bg-[#EFF6FF]' }}">
                    <div class="w-2 h-2 rounded-full mt-2 shrink-0
                                {{ $notif->read_at ? 'bg-transparent' : 'bg-[#1A2B5C]' }}"></div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm leading-snug {{ $notif->read_at ? 'text-[#374151]' : 'font-semibold text-[#1C1E2C]' }}">
                            {{ $notif->data['title'] ?? 'Notifikasi' }}
                        </div>
                        <div class="text-xs text-[#6B7280] mt-0.5">{{ $notif->data['desc'] ?? '' }}</div>
                        <div class="text-[10px] text-[#9CA3AF] mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-10 text-center text-sm text-[#6B7280]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-2 text-[#D1D5DB]"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    Tidak ada notifikasi
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-[#E5E7EB] text-center">
            <a href="{{ route('notifications.index') }}"
               class="text-xs text-[#1A2B5C] font-semibold hover:underline">
                Lihat semua notifikasi →
            </a>
        </div>
    </div>
</div>
