{{-- Halaman Register Mahasiswa --}}
@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<div x-data="{ prodiOpen: false, semOpen: false, prodi: '', semester: '' }">

    <a href="{{ route('login') }}"
       class="inline-flex items-center gap-1 text-sm text-[#6B7280] hover:text-[#1A2B5C] mb-5 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/></svg>
        Kembali
    </a>

    <h1 class="text-3xl font-bold text-[#1C1E2C]">Buat Akun Baru</h1>
    <p class="text-[#6B7280] mt-2">Daftar sebagai mahasiswa SIMKOM Bali</p>

    <form class="mt-6 space-y-4" method="POST" action="{{ route('register') }}">
        @csrf

        {{-- NIM --}}
        <div>
            <label class="text-sm font-medium text-[#374151]" for="nim">NIM</label>
            <div class="relative mt-1.5">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280] pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                </svg>
                <input id="nim" name="nim" type="text" value="{{ old('nim') }}"
                       placeholder="220010xxx" required
                       class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                              outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20 transition-all
                              @error('nim') border-[#EF4444] @enderror">
            </div>
            @error('nim')<p class="mt-1 text-xs text-[#EF4444]">{{ $message }}</p>@enderror
        </div>

        {{-- Nama Lengkap --}}
        <div>
            <label class="text-sm font-medium text-[#374151]" for="name">Nama Lengkap</label>
            <div class="relative mt-1.5">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280] pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                <input id="name" name="name" type="text" value="{{ old('name') }}"
                       placeholder="Nama lengkap sesuai KTP" required
                       class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                              outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20 transition-all
                              @error('name') border-[#EF4444] @enderror">
            </div>
            @error('name')<p class="mt-1 text-xs text-[#EF4444]">{{ $message }}</p>@enderror
        </div>

        {{-- No Telepon --}}
        <div>
            <label class="text-sm font-medium text-[#374151]" for="phone">No. Telepon</label>
            <div class="relative mt-1.5">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280] pointer-events-none"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 11.39 19a19.45 19.45 0 0 1-6.39-6.39A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                </svg>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}"
                       placeholder="08xxxxxxxxxx" required
                       class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                              outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20 transition-all">
            </div>
        </div>

        {{-- Program Studi (dropdown Alpine) --}}
        <div>
            <label class="text-sm font-medium text-[#374151]">Program Studi</label>
            <div class="relative mt-1.5">
                <button type="button" @click="prodiOpen = !prodiOpen; semOpen = false"
                        class="w-full px-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm text-left
                               flex items-center justify-between outline-none focus:border-[#1A2B5C]
                               transition-all @error('prodi') border-[#EF4444] @enderror">
                    <span :class="prodi ? 'text-[#1C1E2C]' : 'text-[#9CA3AF]'"
                          x-text="prodi || 'Pilih program studi'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#6B7280] transition-transform duration-200"
                         :class="prodiOpen ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <input type="hidden" name="prodi" :value="prodi">
                <div x-show="prodiOpen" x-cloak @click.outside="prodiOpen = false"
                     class="absolute z-50 mt-1 w-full bg-white border border-[#E5E7EB] rounded-lg shadow-lg overflow-hidden"
                     style="display:none;">
                    @foreach(['Sistem Informasi','Teknik Informatika','Manajemen Informatika','Komputerisasi Akuntansi','Desain Komunikasi Visual'] as $p)
                        <button type="button"
                                @click="prodi = '{{ $p }}'; prodiOpen = false"
                                :class="prodi === '{{ $p }}' ? 'text-[#1A2B5C] font-semibold bg-[#F7F8FC]' : 'text-[#1C1E2C]'"
                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-[#F7F8FC] transition-colors">
                            {{ $p }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Semester --}}
        <div>
            <label class="text-sm font-medium text-[#374151]">Semester</label>
            <div class="relative mt-1.5">
                <button type="button" @click="semOpen = !semOpen; prodiOpen = false"
                        class="w-full px-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm text-left
                               flex items-center justify-between outline-none focus:border-[#1A2B5C] transition-all">
                    <span :class="semester ? 'text-[#1C1E2C]' : 'text-[#9CA3AF]'"
                          x-text="semester ? 'Semester ' + semester : 'Pilih semester'"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#6B7280] transition-transform duration-200"
                         :class="semOpen ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <input type="hidden" name="semester" :value="semester">
                <div x-show="semOpen" x-cloak @click.outside="semOpen = false"
                     class="absolute z-50 mt-1 w-full bg-white border border-[#E5E7EB] rounded-lg shadow-lg overflow-hidden"
                     style="display:none;">
                    <div class="grid grid-cols-4">
                        @foreach(['1','2','3','4','5','6','7','8'] as $s)
                            <button type="button"
                                    @click="semester = '{{ $s }}'; semOpen = false"
                                    :class="semester === '{{ $s }}' ? 'text-[#1A2B5C] font-bold bg-[#F7F8FC]' : 'text-[#1C1E2C]'"
                                    class="py-3 text-sm hover:bg-[#F7F8FC] transition-colors text-center border-r border-b border-[#F3F4F6]">
                                {{ $s }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-sm font-medium text-[#374151]" for="password">Password</label>
                <input id="password" name="password" type="password"
                       placeholder="••••••••" required
                       class="mt-1.5 w-full px-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                              outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20 transition-all
                              @error('password') border-[#EF4444] @enderror">
            </div>
            <div>
                <label class="text-sm font-medium text-[#374151]" for="password_confirmation">Konfirmasi</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       placeholder="••••••••" required
                       class="mt-1.5 w-full px-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                              outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20 transition-all">
            </div>
        </div>
        @error('password')<p class="text-xs text-[#EF4444]">{{ $message }}</p>@enderror

        {{-- Syarat --}}
        <label class="flex items-start gap-2 text-xs text-[#6B7280] cursor-pointer">
            <input type="checkbox" name="terms" required
                   class="mt-0.5 shrink-0 w-4 h-4 rounded border-[#E5E7EB] accent-[#1A2B5C]">
            Saya menyetujui syarat &amp; ketentuan SIMKOM Bali
        </label>

        <button type="submit"
                class="w-full h-11 bg-[#1A2B5C] hover:bg-[#0F1B3D] text-white text-sm font-semibold
                       rounded-lg transition-all duration-200 hover:shadow-lg hover:shadow-[#1A2B5C]/20
                       active:scale-[0.98]">
            Daftar
        </button>
    </form>
</div>
@endsection
