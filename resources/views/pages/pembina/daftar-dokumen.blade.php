@extends('layouts.app')

@section('topbar_title', 'Manajemen Dokumen')
@section('topbar_subtitle', 'Arsip dokumen ormawa')

@section('content')

<div class="p-4 sm:p-6 lg:p-8 space-y-6">

    {{-- Statistik Dinamis dari Database --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Card Proposal --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] p-5 shadow-sm">
            <div class="w-11 h-11 rounded-lg bg-[#1A2B5C] text-white flex items-center justify-center">
                <i class="far fa-file-alt fa-lg"></i>
            </div>
            <div class="mt-4 text-3xl font-bold text-[#1C1E2C]">
                {{ $countProposal ?? 0 }}
            </div>
            <div class="mt-1 text-sm text-[#6B7280]">
                Proposal
            </div>
        </div>

        {{-- Card LPJ --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] p-5 shadow-sm">
            <div class="w-11 h-11 rounded-lg bg-[#00C9A7] text-white flex items-center justify-center">
                <i class="far fa-file-check fa-lg"></i>
            </div>
            <div class="mt-4 text-3xl font-bold text-[#1C1E2C]">
                {{ $countLpj ?? 0 }}
            </div>
            <div class="mt-1 text-sm text-[#6B7280]">
                LPJ
            </div>
        </div>

        {{-- Card Konstitusi --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] p-5 shadow-sm">
            <div class="w-11 h-11 rounded-lg bg-[#F5A623] text-white flex items-center justify-center">
                <i class="fas fa-gavel fa-lg"></i>
            </div>
            <div class="mt-4 text-3xl font-bold text-[#1C1E2C]">
                {{ $countKonstitusi ?? 0 }}
            </div>
            <div class="mt-1 text-sm text-[#6B7280]">
                Konstitusi
            </div>
        </div>

        {{-- Card Notulen --}}
        <div class="bg-white rounded-xl border border-[#E5E7EB] p-5 shadow-sm">
            <div class="w-11 h-11 rounded-lg bg-[#7C3AED] text-white flex items-center justify-center">
                <i class="far fa-clipboard fa-lg"></i>
            </div>
            <div class="mt-4 text-3xl font-bold text-[#1C1E2C]">
                {{ $countNotulen ?? 0 }}
            </div>
            <div class="mt-1 text-sm text-[#6B7280]">
                Notulen
            </div>
        </div>

    </div>

    {{-- Tabel Dokumen Realtime --}}
    <div class="bg-white rounded-xl border border-[#E5E7EB] overflow-hidden shadow-sm">

        <div class="flex justify-end p-5 border-b">
            {{-- PERBAIKAN FIXED: Menggunakan url() langsung untuk tombol Upload --}}
            <a href="{{ url('/dokumen/create') }}" class="inline-flex items-center justify-center gap-2 text-white font-semibold shadow-sm transition-colors hover:bg-[#d88e15]" style="background-color: #f0a929; border-radius: 8px; height: 38px; padding: 0 16px; font-size: 0.875rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" x2="12" y1="3" y2="15"></line>
                </svg>
                Upload
            </a>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead class="bg-[#F9FAFB]">
                    <tr class="text-[#6B7280] font-semibold text-xs uppercase border-b border-[#E5E7EB]">
                        <th class="p-4" style="width: 40%;">Nama File</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Ukuran</th>
                        <th class="p-4">Tanggal Upload</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="text-sm divide-y divide-[#E5E7EB] text-gray-700">

                    @forelse($documents as $doc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    @if(Str::contains(Str::lower($doc->path_url), ['.xlsx', '.xls', 'csv']))
                                        <i class="far fa-file-excel text-emerald-600 fa-lg"></i>
                                    @elseif(Str::contains(Str::lower($doc->path_url), ['.jpg', '.jpeg', '.png']))
                                        <i class="far fa-file-image text-blue-500 fa-lg"></i>
                                    @else
                                        <i class="far fa-file-pdf text-red-500 fa-lg"></i>
                                    @endif
                                    <span class="truncate max-w-[280px]" title="{{ $doc->nama_file }}">{{ $doc->nama_file }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-gray-100 text-gray-600 capitalize">
                                    {{ str_replace('_', ' ', $doc->jenis_dokumen == 'laporan_kegiatan' ? 'LPJ' : $doc->jenis_dokumen) }}
                                </span>
                            </td>
                            <td class="p-4 text-gray-500 text-xs">
                                2.4 MB
                            </td>
                            <td class="p-4 text-gray-500 text-xs">
                                {{ $doc->created_at ? $doc->created_at->translatedFormat('j M Y') : '-' }}
                            </td>
                            <td class="p-4">
    <div class="flex items-center justify-center">
        <a href="{{ route('dokumen.download', $doc->id) }}" class="text-gray-400 hover:text-[#1A2B5C] transition-colors" title="Download Berkas">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" x2="12" y1="15" y2="3"></line>
            </svg>
        </a>
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 font-medium">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span>📁</span>
                                    <span>Belum ada data dokumen aktif yang diunggah.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection