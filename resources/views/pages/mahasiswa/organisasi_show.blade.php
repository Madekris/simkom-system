@extends('layouts.app')

@section('topbar_title', 'Detail Organisasi')
@section('topbar_subtitle', $organisasi->nama)

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-5">
    <a href="{{ route('mahasiswa.organisasi.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-800">
        <i class="fas fa-arrow-left text-xs"></i>
        Kembali ke daftar organisasi
    </a>

    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
             <div class="w-16 h-16 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600 font-bold text-2xl shrink-0">
                {{ strtoupper(substr($organisasi->nama, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl font-bold text-[#1C1E2C]">{{ $organisasi->nama }}</h2>
                        <span class="text-[10px] uppercase tracking-wide font-semibold px-2 py-0.5 rounded bg-[#F7F8FC] text-[#6B7280]">
                            {{ $organisasi->jenisOrganisasi->nama ?? 'UKM' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                            Aktif
                        </span>
                    </div>

                    @php
                        $isMyOrg = in_array($organisasi->id, $myOrganisasi);
                    @endphp

                    @if (!$isMyOrg)
                        
                        <a href="{{ route('mahasiswa.organisasi.daftar', $organisasi->id) }}" 
                        class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold shadow-sm transition text-center whitespace-nowrap w-full sm:w-auto">
                            Daftar Anggota
                        </a>
                    @endif
                </div>
                <p class="text-sm text-[#6B7280] mt-2 line-clamp-2">
                   {{ $organisasi->deskripsi }}
                </p>

                
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6 pt-6 border-t border-[#E5E7EB]">
            <div>
                <div class="text-xs text-[#6B7280]">Total Anggota</div>
                <div class="text-xl font-bold text-[#1C1E2C] mt-1">{{ $organisasi->anggotaOrganisasi->count()}}</div>
            </div>

            <div>
                <div class="text-xs text-[#6B7280]">Ketua Umum</div>
                <div class="text-sm font-bold text-[#1C1E2C] mt-1">{{ $organisasi->ketua->user->mahasiswa->nama ?? '-'}}</div>
            </div>

            <div>
                <div class="text-xs text-[#6B7280]">Pembina</div>
                <div class="text-sm font-bold text-[#1C1E2C] mt-1">{{ $organisasi->pembina->user->pembina->nama ?? '-'}}</div>
            </div>

            <div>
                <div class="text-xs text-[#6B7280]">Periode</div>
                <div class="text-sm font-bold text-[#1C1E2C] mt-1">
                    {{ $organisasi->periode->first()->tahun_mulai ?? '-' }}/{{ $organisasi->periode->first()->tahun_selesai ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="flex gap-1 border-b border-[#E5E7EB] overflow-x-auto">
        <a href="?tab=informasi" 
        class="px-4 py-2.5 text-sm font-semibold whitespace-nowrap border-b-2 transition-all {{ request('tab', 'informasi') === 'informasi' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            Informasi
        </a>

        <a href="?tab=kegiatan" 
        class="px-4 py-2.5 text-sm font-semibold whitespace-nowrap border-b-2 transition-all {{ request('tab') === 'kegiatan' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            Kegiatan
        </a>

        <a href="?tab=pengurus" 
        class="px-4 py-2.5 text-sm font-semibold whitespace-nowrap border-b-2 transition-all {{ request('tab') === 'pengurus' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            Pengurus
        </a>

        <a href="?tab=ad_art" 
        class="px-4 py-2.5 text-sm font-semibold whitespace-nowrap border-b-2 transition-all {{ request('tab') === 'ad_art' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            AD/ART
        </a>
    </div>

    @if (request('tab', 'informasi') === 'informasi')
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 space-y-2">
                <h3 class="font-bold text-[#1C1E2C]">Tentang {{ $organisasi->nama }}</h3>
                <p class="text-sm text-[#6B7280] leading-relaxed">
                    {{ $organisasi->deskripsi }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-5 rounded-full bg-[#F5A623]"></div>
                        <h4 class="font-bold text-[#1C1E2C]">Visi</h4>
                    </div>
                    <p class="text-sm text-[#6B7280] leading-relaxed">
                        {{ $organisasi->visi ?? 'Visi belum diatur.' }}
                    </p>
                </div>

                <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 space-y-2">
                    <div class="flex items-center gap-2">
                        <div class="w-1.5 h-5 rounded-full bg-[#00C9A7]"></div>
                        <h4 class="font-bold text-[#1C1E2C]">Misi</h4>
                    </div>
                    <div class="text-sm text-[#6B7280] leading-relaxed whitespace-pre-line">
                        {{ $organisasi->misi ?? 'Misi belum diatur.' }}
                    </div>
                </div>
            </div>
        </div>
    
    @elseif (request('tab', 'informasi') === 'kegiatan')
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
            <h3 class="font-bold text-[#1C1E2C] mb-3">Kegiatan {{ $organisasi->nama }}</h3>
            
            <div class="space-y-3">
                @forelse ( $organisasi->kegiatan as $kegiatan )
                    <div class="flex items-center justify-between p-3 rounded-lg bg-[#F7F8FC] gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-5 h-5 text-[#1A2B5C] shrink-0">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <div class="truncate">
                                <div class="font-semibold text-sm text-[#1C1E2C] truncate">{{ $kegiatan->judul_kegiatan }}</div>
                                <div class="text-xs text-[#6B7280]">
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal_kegiatan)->translatedFormat('d M Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @switch($kegiatan->status)
                                @case('Pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-700">
                                        Pending
                                    </span>
                                    <button disabled class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium h-9 rounded-lg px-4 w-full md:w-auto bg-[#E5E7EB] text-[#9CA3AF] cursor-not-allowed">
                                        Daftar
                                    </button>
                                    @break

                                @case('Mendatang')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 border border-blue-200 text-blue-700">
                                        Mendatang
                                    </span>
                                    <form action="{{ route('mahasiswa.daftar-kegiatan.daftar', $kegiatan->id) }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all h-9 rounded-lg px-4 w-full md:w-auto bg-[#F5A623] hover:bg-[#D88E15] text-white font-semibold shadow-sm">
                                            Daftar
                                        </button>
                                    </form>
                                    @break

                                @case('Berlangsung')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 border border-teal-200 text-teal-700">
                                        Berlangsung
                                    </span>
                                    <button disabled class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium h-9 rounded-lg px-4 w-full md:w-auto bg-[#E5E7EB] text-[#9CA3AF] cursor-not-allowed">
                                        Daftar
                                    </button>
                                    @break

                                @case('Selesai')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 border border-gray-200 text-gray-700">
                                        Selesai
                                    </span>
                                    <button disabled class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium h-9 rounded-lg px-4 w-full md:w-auto bg-[#E5E7EB] text-[#9CA3AF] cursor-not-allowed">
                                        Daftar
                                    </button>
                                    @break

                                @case('Dibatalkan')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 border border-red-200 text-red-700">
                                        Dibatalkan
                                    </span>
                                    <button disabled class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium h-9 rounded-lg px-4 w-full md:w-auto bg-[#E5E7EB] text-[#9CA3AF] cursor-not-allowed">
                                        Daftar
                                    </button>
                                    @break

                                @default
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                        {{ $kegiatan->status }}
                                    </span>
                                    <button disabled class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium h-9 rounded-lg px-4 w-full md:w-auto bg-[#E5E7EB] text-[#9CA3AF] cursor-not-allowed">
                                        Daftar
                                    </button>
                            @endswitch

                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                        <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center border border-gray-100 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x w-6 h-6 text-[#6B7280]">
                                <rect width="18" height="18" x="3" y="4" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18M10 16l4-4M14 16l-4-4" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-semibold text-[#1C1E2C]">Belum Ada Kegiatan</h4>
                        <p class="text-xs text-[#6B7280] mt-1 max-w-sm">
                            Organisasi ini belum menjadwalkan atau mempublikasikan kegiatan dalam waktu dekat.
                        </p>
                    </div>
                @endforelse
                
            </div>
        </div>
    
    @elseif (request('tab', 'informasi') === 'pengurus')
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
            <h3 class="font-bold text-[#1C1E2C] mb-3">Susunan Pengurus</h3>
            
            <div class="space-y-3">
                @forelse($pengurusOrmawa as $pengurus)
                    @php
                        // Logika mengambil 1 huruf pertama dari kata pertama dan kata kedua untuk inisial avatar
                        $words = explode(' ', $pengurus['nama']);
                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp

                    <div class="flex items-center gap-3 p-3 rounded-lg bg-[#F7F8FC]">
                        <div class="w-10 h-10 rounded-full bg-[#1A2B5C] text-[#F5A623] flex items-center justify-center font-bold text-sm shrink-0">
                            {{ $initials }}
                        </div>
                        
                        <div>
                            <div class="font-semibold text-sm text-[#1C1E2C]">{{ $pengurus['nama'] }}</div>
                            <div class="text-xs text-[#6B7280]">{{ $pengurus['jabatan'] }}</div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-8 h-8 text-[#6B7280] mb-2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <h4 class="text-sm font-semibold text-[#1C1E2C]">Belum Ada Data Pengurus</h4>
                    </div>
                @endforelse
            </div>
        </div>
    @elseif (request('tab', 'informasi') === 'ad_art')
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 space-y-4">
    <div>
        <h3 class="font-bold text-[#1C1E2C] mb-1">Anggaran Dasar &amp; Anggaran Rumah Tangga</h3>
        <p class="text-sm text-[#6B7280] leading-relaxed">
            AD/ART merupakan landasan hukum internal Rade yang mengatur tata kelola organisasi, hak dan kewajiban anggota, serta mekanisme pengambilan keputusan.
        </p>
    </div>

    <div class="space-y-3">
        @if(isset($documents) && count($documents) > 0)
            <div class="flex items-center gap-4 p-4 rounded-lg bg-[#F7F8FC] border border-[#E5E7EB]">
                <div class="w-10 h-10 rounded-lg bg-[#EF4444]/10 text-[#EF4444] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-5 h-5"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm text-[#1C1E2C] truncate">AD_ART_Rade.pdf</div>
                    <div class="text-xs text-[#6B7280]">Anggaran Dasar &amp; Anggaran Rumah Tangga · 1.2 MB · 10 Sep 2025</div>
                </div>
                <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] h-8 rounded-md gap-1.5 px-3 bg-[#1A2B5C] hover:bg-[#0F1D3D] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-3.5 h-3.5 mr-1"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg> Lihat
                </button>
            </div>

            <div class="flex items-center gap-4 p-4 rounded-lg bg-[#F7F8FC] border border-[#E5E7EB]">
                <div class="w-10 h-10 rounded-lg bg-[#EF4444]/10 text-[#EF4444] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-5 h-5"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm text-[#1C1E2C] truncate">SOP_Rade.pdf</div>
                    <div class="text-xs text-[#6B7280]">Standar Operasional Prosedur · 0.8 MB · 10 Sep 2025</div>
                </div>
                <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] h-8 rounded-md gap-1.5 px-3 bg-[#1A2B5C] hover:bg-[#0F1D3D] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-3.5 h-3.5 mr-1"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg> Lihat
                </button>
            </div>
        @else
            <div class="py-8 text-center text-sm text-[#6B7280] bg-[#F7F8FC] border border-dashed border-[#D1D5DB] rounded-xl px-4">
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    width="24" 
                    height="24" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="1.5" 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    class="lucide lucide-file-text mx-auto mb-2.5 h-9 w-9 text-[#D1D5DB]"
                >
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                    <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                    <path d="M10 9H8"></path>
                    <path d="M16 13H8"></path>
                    <path d="M16 17H8"></path>
                </svg>
                
                <div class="mb-0.5 font-semibold text-[#374151]">
                    Belum ada dokumen AD/ART &amp; SOP
                </div>
                
                <div class="text-xs text-[#9CA3AF]">
                    Silakan upload dokumen melalui menu Edit Ormawa terlebih dahulu
                </div>
            </div>
        @endif
    </div>
</div>
    @endif
</div>

@endsection
