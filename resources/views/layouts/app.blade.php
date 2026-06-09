<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMKOM') — SIMKOM Bali</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Manajemen Kegiatan Ormawa SIMKOM Bali')">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    {{-- Tailwind CSS CDN (ganti dengan vite jika sudah setup) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy:  { DEFAULT: '#1A2B5C', dark: '#0F1B3D', light: '#EFF1F8' },
                        teal:  { DEFAULT: '#00C9A7' },
                        gold:  { DEFAULT: '#F5A623', dark: '#D88E15' },
                        muted: '#6B7280',
                        surface: '#F7F8FC',
                    },
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                    boxShadow: { card: '0 2px 12px rgba(0,0,0,0.04)' }
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Alpine.js untuk interaktivitas (dropdown, toggle sidebar, dll) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Custom CSS tambahan --}}
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-[#F7F8FC]" x-data="{ sidebarOpen: false }">

    {{-- ============================================================
         LAYOUT UTAMA: SIDEBAR + KONTEN
    ============================================================ --}}
    <div class="flex h-screen overflow-hidden">

        {{-- ── OVERLAY MOBILE ────────────────────────────────────── --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            x-transition:enter="transition-opacity duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></div>

        {{-- ── SIDEBAR ─────────────────────────────────────────────
             Kirim data ke component via slot/include:
             @include('components.sidebar', ['role' => $role, 'user' => auth()->user()])
        ────────────────────────────────────────────────────────── --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:static inset-y-0 left-0 z-50
                   w-64 lg:w-60 bg-[#1A2B5C] text-white
                   flex flex-col h-full
                   transition-transform duration-200 ease-in-out
                   lg:translate-x-0"
        >
            {{-- Logo & Role Label --}}
            <div class="px-5 py-5 border-b border-white/10 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-lg bg-[#F5A623] flex items-center justify-center shrink-0">
                        {{-- Ikon GraduationCap --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#1A2B5C]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-bold tracking-tight text-sm">SIMKOM</div>
                        <div class="text-[10px] uppercase tracking-wider text-white/60">
                            @yield('role_label', 'Pengguna')
                        </div>
                    </div>
                </div>
                {{-- Tombol tutup sidebar (mobile) --}}
                <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white p-1 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            {{-- Profile singkat --}}
            @include('components.sidebar-profile')

            {{-- Navigasi --}}
            <nav class="flex-1 px-3 py-3 space-y-0.5 overflow-y-auto scrollbar-hide">
                @php
        $role = Auth::user()->role;
        $jsonPath = resource_path("json/menu/{$role}.json");
        $menus = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : [];
    @endphp

    @foreach($menus as $menu)
        @include('components.nav-item', [
            'label'  => $menu['label'],
            'icon'   => $menu['icon'],
            'active' => request()->routeIs($menu['route']),
            'href'   => Route::has($menu['route']) ? route($menu['route']) : '#',
            'badge'  => $menu['badge'] ?? null
        ])
    @endforeach
            </nav>

            {{-- Tombol Logout --}}
            
            <div class="px-3 py-3 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-white/80 hover:bg-white/5 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── AREA KONTEN UTAMA ────────────────────────────────── --}}
        <main class="flex-1 flex flex-col overflow-hidden w-full">

            {{-- ── TOPBAR ──────────────────────────────────────── --}}
            @include('components.topbar')

            {{-- ── KONTEN HALAMAN ──────────────────────────────── --}}
            <div class="flex-1 overflow-y-auto">
                @yield('content')
            </div>

        </main>
    </div>

    @stack('scripts')
</body>
</html>
