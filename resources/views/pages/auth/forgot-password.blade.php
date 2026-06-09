{{--
    HALAMAN LUPA PASSWORD
--}}
@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div>
    <a href="{{ route('login') }}"
       class="inline-flex items-center gap-1 text-sm text-[#6B7280] hover:text-[#1A2B5C] mb-5 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/></svg>
        Kembali ke Login
    </a>

    <h1 class="text-3xl font-bold text-[#1C1E2C]">Reset Password</h1>
    <p class="text-[#6B7280] mt-2">Masukkan NIM Anda, kami akan bantu reset password.</p>

    @if(session('status'))
        <div class="mt-4 flex items-center gap-2 p-4 rounded-xl bg-[#DCFCE7] text-[#166534] text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('status') }}
        </div>
    @else
        <form class="mt-6 space-y-4" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div>
                <label class="text-sm font-medium text-[#374151]" for="nim">NIM</label>
                <div class="relative mt-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#6B7280] pointer-events-none"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
                    </svg>
                    <input id="nim" name="nim" type="text" value="{{ old('nim') }}"
                           placeholder="Masukkan NIM Anda" required
                           class="w-full pl-9 pr-3 py-2.5 rounded-lg border border-[#E5E7EB] bg-white text-sm
                                  outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20 transition-all
                                  @error('nim') border-[#EF4444] @enderror">
                </div>
                @error('nim')<p class="mt-1 text-xs text-[#EF4444]">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                    class="w-full h-11 bg-[#1A2B5C] hover:bg-[#0F1B3D] text-white text-sm font-semibold
                           rounded-lg transition-all duration-200 hover:shadow-lg active:scale-[0.98]">
                Kirim Permintaan Reset
            </button>
        </form>
    @endif
</div>
@endsection
