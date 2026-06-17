{{--
    KOMPONEN: topbar.blade.php
    Digunakan di layouts/app.blade.php
    Mendukung: judul, subtitle, aksi kanan
    @yield('topbar_title'), @yield('topbar_subtitle'), @yield('topbar_actions')
--}}
<header class="bg-white border-b border-[#E5E7EB] px-4 sm:px-6 lg:px-8 py-4
               flex items-center justify-between gap-3 shrink-0 z-30 relative">

    {{-- KIRI: hamburger + judul halaman --}}
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

    {{-- KANAN: search + notif + avatar profil --}}
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">

        {{-- Slot aksi tambahan jika halaman tertentu butuh tombol ekstra --}}
        @yield('topbar_actions')

        {{-- Icon Notifikasi Lonceng Merah (Sesuai kode desain Anda) --}}
        <button data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium transition-all size-9 rounded-md relative hover:bg-gray-100 text-[#1C1E2C]">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell w-5 h-5">
                <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
            </svg>
            {{-- Dot Indikator Notifikasi Merah --}}
            <span class="absolute top-2 right-2 w-2 h-2 bg-[#EF4444] rounded-full"></span>
        </button>

        {{-- Avatar Inisial Nama Pengguna Secara Otomatis --}}
        <span data-slot="avatar" class="relative size-9 shrink-0 overflow-hidden rounded-full hidden sm:flex" title="{{ auth()->user()->name ?? 'User' }}">
            <span data-slot="avatar-fallback" class="flex size-full items-center justify-center rounded-full bg-[#1A2B5C] text-white text-xs font-bold uppercase tracking-wider">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name ?? 'U')[1] ?? '', 0, 1)) }}
            </span>
        </span>
        
    </div>
</header>
