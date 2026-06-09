{{-- Halaman Login --}}
@extends('layouts.auth')

@section('title', 'Masuk ke SIMKOM')

@section('content')
<div x-data="{ showPwd: false }">
    <h1 class="text-3xl font-bold text-[#1C1E2C]">Selamat Datang</h1>
    <p class="text-[#6B7280] mt-2">Masuk untuk mengakses akun SIMKOM Anda</p>

    {{-- Alert error --}}
    @if($errors->any() || session('error'))
        <div class="mt-4 flex items-center gap-2 p-3 rounded-lg bg-[#FEE2E2] text-[#991B1B] text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ $errors->first() ?? session('error') }}
        </div>
    @endif

    <form class="mt-6 space-y-4" method="POST" action="{{ route('login') }}">
        @csrf

        {{-- NIM / NIP --}}
        <div>
            <label class="text-sm font-medium text-[#374151]" for="nim">NIM / NIP</label>
            <div class="relative mt-1.5">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280] pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                </svg>
                <input id="nim" name="nim" type="text"
                       value="{{ old('nim') }}"
                       placeholder="Masukkan NIM/NIP Anda"
                       autocomplete="username"
                       required
                       class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                              outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20
                              transition-all @error('nim') border-[#EF4444] @enderror">
            </div>
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between">
                <label class="text-sm font-medium text-[#374151]" for="password">Password</label>
                <a 
                   class="text-xs text-[#1A2B5C] hover:underline font-semibold">
                    Lupa password?
                </a>
            </div>
            <div class="relative mt-1.5">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280] pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input id="password" name="password"
                       :type="showPwd ? 'text' : 'password'"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       required
                       class="w-full pl-9 pr-24 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                              outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20
                              transition-all @error('password') border-[#EF4444] @enderror">
                <button type="button" @click="showPwd = !showPwd"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-[#6B7280]
                               hover:text-[#1A2B5C] font-medium transition-colors">
                    <span x-text="showPwd ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</span>
                </button>
            </div>
        </div>

        {{-- Ingat Saya --}}
        <label class="flex items-center gap-2 text-sm text-[#6B7280] cursor-pointer">
            <input type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-[#E5E7EB] accent-[#1A2B5C]">
            Ingat saya
        </label>

        {{-- Submit --}}
        <button type="submit"
                class="w-full h-11 bg-[#1A2B5C] hover:bg-[#0F1B3D] text-white text-sm font-semibold
                       rounded-lg transition-all duration-200 hover:shadow-lg hover:shadow-[#1A2B5C]/20
                       active:scale-[0.98]">
            Masuk
        </button>
    </form>

    <p class="text-center text-sm text-[#6B7280] mt-6">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-[#1A2B5C] font-semibold hover:underline">
            Daftar di sini
        </a>
    </p>
</div>
@endsection
