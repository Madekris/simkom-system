{{--
    KOMPONEN: topbar.blade.php
    Digunakan di layouts/app.blade.php
    Mendukung: judul, subtitle, aksi kanan
    @yield('topbar_title'), @yield('topbar_subtitle'), @yield('topbar_actions')
--}}
<header class="bg-white border-b border-[#E5E7EB] px-4 sm:px-6 lg:px-8 py-4
               flex items-center justify-between gap-3 shrink-0 z-30 relative">

    {{-- KIRI: hamburger + judul --}}
    <div class="flex items-center gap-3 min-w-0">
        {{-- Hamburger (mobile) --}}
        <button @click="sidebarOpen = true"
                class="lg:hidden p-2 -ml-2 rounded-md hover:bg-[#F7F8FC] text-[#1C1E2C] transition-colors"
                aria-label="Buka menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6"  x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <div class="min-w-0">
            <h1 class="text-lg sm:text-xl font-bold text-[#1C1E2C] truncate leading-tight">
                @yield('topbar_title', 'Dashboard')
            </h1>
            @hasSection('topbar_subtitle')
                <p class="text-xs sm:text-sm text-[#6B7280] mt-0.5 truncate">
                    @yield('topbar_subtitle')
                </p>
            @endif
        </div>
    </div>

    {{-- KANAN: aksi + search + notif + avatar --}}
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">

        {{-- Slot aksi tambahan (tombol tambah, export, dll) --}}
        @yield('topbar_actions')

        {{-- Notifikasi --}}

        {{-- Avatar --}}
        <div class="hidden sm:flex w-9 h-9 rounded-full bg-[#1A2B5C] text-white text-xs
                    font-bold items-center justify-center shrink-0 cursor-pointer
                    hover:ring-2 hover:ring-[#F5A623] transition-all"
             title="{{ auth()->user()->name ?? '' }}">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name ?? 'U')[1] ?? '', 0, 1)) }}
        </div>
    </div>
</header>
