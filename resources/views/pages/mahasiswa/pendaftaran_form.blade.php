@extends('layouts.app')

@section('content')
@php
    $current_org = $organisasi ?? $target_organisasi ?? null;
@endphp

<div class="p-6 max-w-4xl mx-auto">
    
    <div class="mb-5">
        <h1 class="text-2xl font-bold text-gray-900">Form Pendaftaran Anggota</h1>
        <p class="text-sm text-gray-500">{{ $current_org->nama ?? 'Nama Organisasi' }}</p>
    </div>

    @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('mahasiswa.organisasi.index') }}" class="text-xs text-gray-500 hover:text-gray-700 inline-flex items-center gap-1 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke daftar ormawa
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <form action="{{ route('mahasiswa.organisasi.store') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="id_organisasi" value="{{ $current_org->id ?? '' }}">

            <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                <div class="w-12 h-12 rounded-xl bg-[#1e293b] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                    {{ $current_org ? strtoupper(substr($current_org->nama, 0, 1)) : 'O' }}
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">{{ $current_org->nama ?? 'Nama Organisasi' }}</h2>
                    <p class="text-xs text-gray-500">Pendaftaran anggota baru periode {{ date('Y') }}/{{ date('Y')+1 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">NIM</label>
                    <input type="text" value="{{ auth()->user()->mahasiswa->nim ?? '-' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm text-gray-600 focus:outline-none cursor-not-allowed" readonly>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" value="{{ auth()->user()->mahasiswa->nama ?? auth()->user()->name }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm text-gray-600 focus:outline-none cursor-not-allowed" readonly>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Program Studi</label>
                    <input type="text" value="{{ auth()->user()->mahasiswa->programStudi->nama ?? '-' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm text-gray-600 focus:outline-none cursor-not-allowed" readonly>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Angkatan</label>
                    <input type="text" value="{{ auth()->user()->mahasiswa->angkatan ?? '2024' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2.5 text-sm text-gray-600 focus:outline-none cursor-not-allowed" readonly>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">No. WhatsApp Aktif <span class="text-red-500">*</span></label>
                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', auth()->user()->no_telepon ?? auth()->user()->mahasiswa->no_telepon ?? '') }}" placeholder="08xxxxxxxxxx" class="w-full border border-gray-200 rounded-xl p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition @error('no_whatsapp') border-red-500 @enderror">
                @error('no_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="alasan" class="block text-xs font-semibold text-gray-700 mb-1">Alasan Mengikuti Ormawa <span class="text-red-500">*</span></label>
                <textarea name="alasan" id="alasan" rows="4" placeholder="Ceritakan motivasi atau alasan Anda bergabung..." class="w-full border border-gray-200 rounded-xl p-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition @error('alasan') border-red-500 @enderror">{{ old('alasan') }}</textarea>
                @error('alasan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 flex items-start gap-2.5">
                <input type="checkbox" name="setuju_adart" id="setuju_adart" value="1" required class="mt-0.5 rounded border-gray-300 text-amber-500 focus:ring-amber-400 h-4 w-4">
                <label for="setuju_adart" class="text-xs text-gray-700 leading-tight select-none">
                    <span class="text-red-500">*</span> Saya telah membaca dan bersedia mematuhi <span class="font-bold">AD/ART {{ $current_org->nama ?? 'Organisasi' }}</span>.
                </label>
            </div>
            @error('setuju_adart') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('mahasiswa.organisasi.index') }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition flex items-center justify-center">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-sm shadow-sm transition">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
