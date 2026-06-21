@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Semua Kegiatan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Daftar kegiatan dari seluruh ormawa')


@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-4">

    <form action="{{ request()->url() }}" method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex flex-wrap items-center gap-3">
        
        <div class="relative flex-1 min-w-[200px] max-w-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4 text-gray-400">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
            <input 
                type="text"
                name="search"
                placeholder="Cari kegiatan..." 
                class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg outline-none transition-all duration-200 focus:bg-white focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/10 text-gray-900"
                value="{{ request('search') }}"
            >
        </div>

        <div class="w-full sm:w-auto">
            <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg outline-none transition-all duration-200 focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/10 text-gray-700 cursor-pointer">
                <option value="">Semua Status</option>
                <option value="Mendatang" {{ request('status') == 'Mendatang' ? 'selected' : '' }}>Mendatang</option>
                <option value="Berlangsung" {{ request('status') == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="w-full sm:w-auto">
            <select name="ormawa_id" onchange="this.form.submit()" class="w-full px-3 py-2 text-sm bg-white border border-gray-200 rounded-lg outline-none transition-all duration-200 focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/10 text-gray-700 cursor-pointer">
                <option value="">Semua Ormawa</option>
                @foreach ($ormawa as $item)
                    <option value="{{ $item->id }}" {{ request('ormawa_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        @if(request()->anyFilled(['search', 'status', 'ormawa_id']))
            <a href="{{ request()->url() }}" class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-lg transition-all duration-200 hover:bg-rose-5, hover:text-rose-600 hover:border-rose-200 focus:ring-2 focus:ring-rose-500/10 text-center">
                Reset Filter
            </a>
        @endif

    </form>

    <div class="w-full overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
        <table class="w-full text-sm min-w-[640px] border-collapse text-left">
            
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Ormawa</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Judul Kegiatan</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold">Lokasi</th>
                    <th scope="col" class="px-6 py-3.5 font-semibold text-center">Status</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-200 text-gray-600 bg-white">
                @forelse ($kegiatan as $item)
                    <tr class="hover:bg-gray-50/70 transition-colors duration-150">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->organisasi->jenisOrganisasi->nama }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->judul_kegiatan }}</td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->translatedFormat('d M Y') }}
                        </td>                        
                        <td class="px-6 py-4">{{ $item->lokasi }}</td>
                        <td class="px-6 py-4 text-center">
                            <td class="px-6 py-4 text-center">
                                @if($item->status === 'Pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Pending
                                    </span>
                                @elseif($item->status === 'Mendatang')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        Mendatang
                                    </span>
                                @elseif($item->status === 'Berlangsung')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                                        Berlangsung
                                    </span>
                                @elseif($item->status === 'Selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                        Selesai
                                    </span>
                                @elseif($item->status === 'Dibatalkan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Belum ada data kegiatan tersedia</p>
                                <p class="text-xs text-gray-400">Semua kegiatan yang terdaftar akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection