<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') — SIMKOM Bali</title>
    <meta name="description" content="Masuk ke sistem SIMKOM Bali untuk mengelola kegiatan ormawa Anda.">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex bg-[#F7F8FC]">

    {{-- ── PANEL KIRI — BRAND ──────────────────────────────────────── --}}
    <div class="hidden lg:flex w-1/2 bg-gradient-to-br from-[#1A2B5C] via-[#1A2B5C] to-[#0F1B3D]
                text-white flex-col justify-between p-12 relative overflow-hidden">
        {{-- Dekorasi blur --}}
        <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-[#F5A623]/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 rounded-full bg-[#00C9A7]/10 blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full bg-white/[0.02] pointer-events-none"></div>

        {{-- Logo --}}
        <div class="relative">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-[#F5A623] flex items-center justify-center shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#1A2B5C]" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-extrabold">SIMKOM</div>
                    <div class="text-xs text-white/60 uppercase tracking-wider">SIMKOM Bali</div>
                </div>
            </div>
        </div>

        {{-- Tagline & Stats --}}
        <div class="relative">
            <h2 class="text-4xl font-bold leading-tight">SELAMAT DATANG<br>SIMKOMERS!</h2>
            <p class="text-white/70 mt-4 text-lg max-w-md leading-relaxed">
                Satu Sistem, Seribu Aksi — Wadah Mahasiswa SIMKOM Bali untuk
                berorganisasi, berkarya, dan bertumbuh bersama.
            </p>
            <div class="mt-8 flex gap-8">
                <div>
                    <div class="text-3xl font-bold text-[#F5A623]">25+</div>
                    <div class="text-sm text-white/60 mt-0.5">Ormawa Aktif</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-[#00C9A7]">500+</div>
                    <div class="text-sm text-white/60 mt-0.5">Anggota</div>
                </div>
                <div>
                    <div class="text-3xl font-bold text-white">120+</div>
                    <div class="text-sm text-white/60 mt-0.5">Kegiatan</div>
                </div>
            </div>

            {{-- Decorative card --}}
           
        </div>

        {{-- Footer --}}
        <div class="relative text-xs text-white/40">© 2026 SIMKOM Bali — v1.0.0</div>
    </div>

    {{-- ── PANEL KANAN — FORM ──────────────────────────────────────── --}}
    <div class="flex-1 flex items-center justify-center p-4 sm:p-6 lg:p-12 overflow-y-auto">
        <div class="w-full max-w-md">

            {{-- Logo mobile only --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-[#1A2B5C] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#F5A623]" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xl font-extrabold text-[#1C1E2C]">SIMKOM</div>
                    <div class="text-[10px] text-[#6B7280] uppercase tracking-wider">SIMKOM Bali</div>
                </div>
            </div>

            {{-- Konten form (yield) --}}
            @yield('content')

        </div>
    </div>

    @stack('scripts')
</body>
</html>
