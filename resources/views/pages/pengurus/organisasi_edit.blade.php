@extends('layouts.app')

@section('topbar_title', 'Profil Organisasi')
@section('topbar_subtitle', 'Kelola informasi organisasi Anda')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    @if(session('success'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
        <div class="flex items-start gap-4 border-b border-gray-100 pb-5 mb-5">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600 font-bold text-xl shrink-0">
                {{ strtoupper(substr($organisasi->nama ?? 'O', 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $organisasi->nama ?? 'Organisasi' }}</h1>
                <p class="text-sm text-gray-500 mt-1">Perubahan profil ini akan tampil di halaman mahasiswa.</p>
            </div>
        </div>

        <form action="{{ route('pengurus.organisasi.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_organisasi" value="{{ $organisasi->id }}">

            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('deskripsi', $organisasi->deskripsi) }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="visi" class="block text-sm font-semibold text-gray-700 mb-1">Visi</label>
                <textarea id="visi" name="visi" rows="3" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('visi', $organisasi->visi) }}</textarea>
                @error('visi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="misi" class="block text-sm font-semibold text-gray-700 mb-1">Misi</label>
                <textarea id="misi" name="misi" rows="5" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('misi', $organisasi->misi) }}</textarea>
                @error('misi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="ad_art" class="block text-sm font-semibold text-gray-700 mb-1">AD/ART</label>
                <textarea id="ad_art" name="ad_art" rows="3" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">{{ old('ad_art', $organisasi->ad_art) }}</textarea>
                @error('ad_art') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="{{ route('pengurus.verifikasi.index') }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-sm shadow-sm transition">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
