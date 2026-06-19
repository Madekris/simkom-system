@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Ormawa Binaan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Daftar organisasi yang Anda bina (read-only)')

@section('content')

<div class="p-4 sm:p-6 lg:p-8">
    <div class="overflow-hidden rounded-xl border border-[#E5E7EB] bg-white shadow-[0_2px_12px_rgba(0,0,0,0.04)]">
        
        <table class="w-full table-auto text-sm text-left">
            <thead class="bg-[#F7F8FC] text-[#6B7280] font-semibold">
                <tr>
                    <th class="px-5 py-3">Nama Ormawa</th>
                    <th class="px-5 py-3">Ketua</th>
                    <th class="px-5 py-3">Anggota</th>
                    <th class="px-5 py-3">Kegiatan Aktif</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-[#E5E7EB]">

                @forelse ($ormawa as $item)
                    @php
                        $o = $item->organisasi;
                        $ketua = $o->ketua->user->mahasiswa->nama ?? '-';
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="font-semibold text-[#1C1E2C]">{{ $o->nama }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-[#6B7280]">{{ $ketua }}</td>
                        <td class="px-5 py-4 text-[#6B7280]">{{ $o->anggotaOrganisasi->count() }}</td>
                        <td class="px-5 py-4 text-[#6B7280]">{{ $o->kegiatan->count() }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold 
                                {{ strtolower($o->status) === 'nonaktif' ? 'bg-red-100 text-red-800' : 'bg-[#DCFCE7] text-[#166534]' }}">
                                {{ $o->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('pembina.ormawa-binaan.index', ['detail-anggota' => $o->id]) }}" class="inline-flex h-8 items-center justify-center rounded-md px-3 text-sm font-medium text-[#1A2B5C] transition-all outline-none hover:bg-gray-100 focus-visible:ring-2 focus-visible:ring-[#1A2B5C]/20 disabled:pointer-events-none disabled:opacity-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye mr-1.5 h-4 w-4">
                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                Lihat Anggota
                            </a>
                        </td>
                    </tr>
                @empty
                    
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@if (request('detail-anggota'))
    <x-modal-detail-anggota :data="$ormawa->first()->organisasi->anggotaOrganisasi"/>
@endif

@endsection