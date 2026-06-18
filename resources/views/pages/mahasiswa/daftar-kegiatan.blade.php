@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Daftar Kegiatan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Seluruh kegiatan organisasi mahasiswa')


@section('content')
    <div class="p-4 sm:p-6 lg:p-8 space-y-5">

        <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-[#E5E7EB] bg-white p-4 shadow-[0_2px_12px_rgba(0,0,0,0.04)]">
            <h2 class="text-xl font-bold text-[#1C1E2C]">Daftar Kegiatan Organisasi</h2>

            <div class="relative w-full min-w-[200px] max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search h-4 w-4 text-[#6B7280]">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </div>
                
                <input type="text" name="search" id="search-ormawa" value="" placeholder="Cari nama ormawa atau kegiatan..." class="w-full rounded-md border border-[#E5E7EB] bg-[#F7F8FC] py-2 pl-9 pr-3 text-sm text-[#1C1E2C] outline-none transition-all focus:border-[#1A2B5C] focus:bg-white">
            </div>

        </div>
        @php
            // 3 Data Dummy berdasarkan struktur tabel kegiatans
            $kegiatanList = [
                [
                    'id' => 1,
                    'judul_kegiatan' => 'Workshop UI/UX Design & Prototyping bersama Figma',
                    'deskripsi' => 'Pelatihan intensif pembuatan design system, wireframe, hingga high-fidelity prototype yang interaktif menggunakan Figma untuk persiapan kompetisi nasional.',
                    'tanggal_kegiatan' => '2026-07-10',
                    'waktu_kegiatan' => '09:00:00',
                    'lokasi' => 'Lab Komputer 3, Gedung Utama Lt. 2',
                    'kuota_peserta' => 50,
                    'status' => 'Membuka Pendaftaran',
                    'nama_organisasi' => 'HIMSISFO', // Relasi dari id_organisasi
                    'nama_periode' => '2026/2027', // Relasi dari id_periode
                ],
                [
                    'id' => 2,
                    'judul_kegiatan' => 'Seminar Technopreneurship: Membangun Startup dari Kampus',
                    'deskripsi' => 'Temukan strategi validasi ide bisnis digital, mencari pendanaan awal (seed funding), serta kisah sukses alumni dalam membangun startup berbasis teknologi.',
                    'tanggal_kegiatan' => '2026-07-24',
                    'waktu_kegiatan' => '13:30:00',
                    'lokasi' => 'Aula Besar Kampus',
                    'kuota_peserta' => 150,
                    'status' => 'Membuka Pendaftaran',
                    'nama_organisasi' => 'BEM ITB',
                    'nama_periode' => '2026/2027',
                ],
                [
                    'id' => 3,
                    'judul_kegiatan' => 'Hackathon SIMKOM 2026: Sustainable Technology Solution',
                    'deskripsi' => 'Kompetisi coding 24 jam tingkat internal untuk menciptakan solusi perangkat lunak guna menyelesaikan masalah lingkungan hidup dan manajemen energi bersih.',
                    'tanggal_kegiatan' => '2026-08-05',
                    'waktu_kegiatan' => '08:00:00',
                    'lokasi' => 'Gedung Inkubator Bisnis Lt. 3',
                    'kuota_peserta' => 30,
                    'status' => 'Penuh',
                    'nama_organisasi' => 'Sistem Komputer',
                    'nama_periode' => '2026/2027',
                ]
            ];
        @endphp

        <div class="flex flex-col gap-4 w-full">
            @foreach($kegiatanList as $kegiatan)
                <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5 hover:shadow-lg transition flex flex-col md:flex-row items-start md:items-center gap-5">
                    
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-amber-200 bg-amber-100 font-bold text-xl text-amber-600 shadow-sm">
                        {{ Str::upper(Str::substr($kegiatan['judul_kegiatan'], 0, 1)) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-[#1C1E2C] text-base hover:text-[#1A2B5C] transition cursor-pointer">
                                {{ $kegiatan['judul_kegiatan'] }}
                            </h3>
                            
                            <span class="text-[10px] uppercase tracking-wide font-semibold px-2 py-0.5 rounded bg-[#F7F8FC] text-[#6B7280]">
                                {{ $kegiatan['nama_organisasi'] }}
                            </span>

                            @if($kegiatan['status'] == 'Membuka Pendaftaran')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                    {{ $kegiatan['status'] }}
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-[#6B7280] mt-1 line-clamp-2">
                            {{ $kegiatan['deskripsi'] }}
                        </p>

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-3 text-xs text-[#6B7280]">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-[#1A2B5C]"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($kegiatan['tanggal_kegiatan'])->translatedFormat('d F Y') }} • {{ \Carbon\Carbon::parse($kegiatan['waktu_kegiatan'])->format('H:i') }} WITA
                            </span>

                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-[#1A2B5C]"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 14.25-4.821Z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{ $kegiatan['lokasi'] }}
                            </span>

                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3.5 h-3.5 text-[#1A2B5C]"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Kuota: <span class="text-[#1C1E2C] font-semibold">{{ $kegiatan['kuota_peserta'] }} Peserta</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex sm:flex-row md:flex-col lg:flex-row gap-2 w-full md:w-auto shrink-0 justify-end mt-4 md:mt-0">
                        <a href="{{ route('mahasiswa.daftar-kegiatan.show', ['id' => 1]) }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-[#F7F8FC] h-9 rounded-lg px-4 w-full md:w-auto">
                            Detail
                        </a>
                        
                        @if($kegiatan['status'] == 'Membuka Pendaftaran')
                            <a href="/daftar-kegiatan/{{ $kegiatan['id'] }}" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all h-9 rounded-lg px-4 w-full md:w-auto bg-[#F5A623] hover:bg-[#D88E15] text-[#1A2B5C] font-semibold shadow-sm">
                                Daftar
                            </a>
                        @else
                            <button disabled class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium h-9 rounded-lg px-4 w-full md:w-auto bg-[#E5E7EB] text-[#9CA3AF] cursor-not-allowed">
                                Penuh
                            </button>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    </div>
@endsection