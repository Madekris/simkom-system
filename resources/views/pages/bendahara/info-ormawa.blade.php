@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Info Ormawa')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Data organisasi yang Anda kelola keuangannya (read-only)')

@section('content')
@php
    // 1. Pemetaan Data Utama dari Objek Model $organisasi
    $namaOrganisasi = $organisasi->nama;
    $deskripsiOrganisasi = $organisasi->deskripsi;
    $status = $organisasi->status; // bernilai 'aktif' dari database
    
    // Default fallback untuk data yang belum ada di tabel organisasi saat ini
    $kategori = $organisasi->jenisOrganisasi->nama; // Bisa disesuaikan dengan id_jenis_organisasi (misal jika 3 = UKM)

    // 3. Logika Otomatis Mengambil 2 Huruf Inisial Nama Organisasi (Contoh: "UKM Mapala" -> "UM")
    $words = explode(" ", $namaOrganisasi);
    $inisial = '';
    foreach (array_slice($words, 0, 2) as $w) {
        $inisial .= mb_substr($w, 0, 1);
    }
    $inisial = strtoupper($inisial);
@endphp

<div class="p-4 sm:p-6 lg:p-8 space-y-5">

    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
            
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-bold text-white text-2xl shrink-0 bg-gradient-to-br from-[#00C9A7] to-[#0F766E]">
                {{ $inisial }}
            </div>
    
            <div class="flex-1">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-xl font-bold text-[#1C1E2C]">{{ $namaOrganisasi }}</h2>
                    
                    <span class="text-[10px] uppercase tracking-wide font-semibold px-2 py-0.5 rounded bg-[#F7F8FC] text-[#6B7280]">
                        {{ $kategori }}
                    </span>
                    
                    @if(strtolower($status) === 'aktif')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                            Nonaktif
                        </span>
                    @endif
                </div>
                
                <p class="text-sm text-[#6B7280] mt-2">{{ $deskripsiOrganisasi }}</p>
                
            </div>
        </div>
    
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6 pt-6 border-t border-[#E5E7EB]">
            <div>
                <div class="text-xs text-[#6B7280]">Total Anggota</div>
                <div class="text-xl font-bold text-[#1C1E2C] mt-1">{{ number_format($totalAnggota, 0, ',', '.') }}</div>
            </div>
            <div>
                <div class="text-xs text-[#6B7280]">Ketua Umum</div>
                <div class="text-sm font-bold text-[#1C1E2C] mt-1">{{ $ketua->nama }}</div>
            </div>
            <div>
                <div class="text-xs text-[#6B7280]">Pembina</div>
                <div class="text-sm font-bold text-[#1C1E2C] mt-1">{{ $pembina }}</div>
            </div>
            <div>
                <div class="text-xs text-[#6B7280]">Periode</div>
                <div class="text-sm font-bold text-[#1C1E2C] mt-1">{{ $periode }}</div>
            </div>
        </div>
    </div>

    @php
        // 1. Dataset Anggota Inti (Dipasangkan ke Card Kiri)
        $anggotaInti = [
            [
                'nama' => $ketua->nama,
                'nim' => $ketua->nim,
                'jabatan' => 'Ketua',
                'is_leader' => true // Penanda khusus jika ingin memunculkan badge "Ketua" tambahan
            ],
            [
                'nama' => $wakil->nama ?? '-',
                'nim' => $wakil->nim ?? '-',
                'jabatan' => 'Wakil Ketua',
                'is_leader' => false
            ],
            [
                'nama' => $sekre->nama ?? '-',
                'nim' => $sekre->nim ?? '-',
                'jabatan' => 'Sekretaris',
                'is_leader' => false
            ],
            [
                'nama' => $bendahara->nama ?? '-',
                'nim' => $bendahara->nim ?? '-',
                'jabatan' => 'Bendahara',
                'is_leader' => false
            ],
        ];

        // 2. Dataset Kegiatan & Anggaran (Dipasangkan ke Card Kanan)
        $daftarKegiatan = [
            [
                'nama_kegiatan' => 'Workshop AI 2026',
                'status' => 'Berlangsung', // Pilihan: Berlangsung, Selesai, Dibatalkan
                'tanggal' => '12 Jun 2026',
                'anggaran' => 5000000
            ],
            [
                'nama_kegiatan' => 'Hackathon 48 Jam',
                'status' => 'Berlangsung',
                'tanggal' => '20 Jun 2026',
                'anggaran' => 4000000
            ],
            [
                'nama_kegiatan' => 'Studi Banding UI',
                'status' => 'Dibatalkan',
                'tanggal' => '05 Jul 2026',
                'anggaran' => 8000000
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-[#1C1E2C] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-4 h-4 text-[#1A2B5C]">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg> 
                    Anggota Inti
                </h3>
                <span class="text-xs text-[#6B7280]">{{ count($anggotaInti) }} ditampilkan</span>
            </div>

            <div class="space-y-2">
                @foreach($anggotaInti as $anggota)
                    @php
                        // Logika generate inisial nama otomatis (Andi Pratama -> AP)
                        $words = explode(" ", $anggota['nama']);
                        $avatarInisial = '';
                        foreach (array_slice($words, 0, 2) as $w) {
                            $avatarInisial .= mb_substr($w, 0, 1);
                        }
                        $avatarInisial = strtoupper($avatarInisial);
                    @endphp

                    <div class="flex items-center gap-3 p-3 rounded-lg bg-[#F7F8FC]">
                        <div class="w-10 h-10 rounded-full bg-[#1A2B5C] text-[#F5A623] flex items-center justify-center font-bold text-sm">
                            {{ $avatarInisial }}
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm text-[#1C1E2C] truncate">{{ $anggota['nama'] }}</div>
                            <div class="text-xs text-[#6B7280]">{{ $anggota['nim'] }} · {{ $anggota['jabatan'] }}</div>
                        </div>

                        {{-- Memunculkan badge orange pendukung jika bernilai true --}}
                        @if($anggota['is_leader'])
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#F5A623]/15 text-[#92400E]">
                                {{ $anggota['jabatan'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-[#1C1E2C] flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-range w-4 h-4 text-[#1A2B5C]">
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <path d="M8 2v4"></path>
                        <path d="M17 14h-6"></path>
                        <path d="M13 18H7"></path>
                        <path d="M7 14h.01"></path>
                        <path d="M17 18h.01"></path>
                    </svg> 
                    Kegiatan & Anggaran
                </h3>
                <span class="text-xs text-[#6B7280]">{{ count($daftarKegiatan) }} kegiatan</span>
            </div>

            <div class="space-y-3">
                @forelse($kegiatanOrganisasi as $kegiatan)
                    @php
                        // Mengonversi data ke object jika dilempar dari controller berbentuk array murni
                        $kegiatanObj = is_array($kegiatan) ? (object) $kegiatan : $kegiatan;
                        
                        // Hitung total anggaran dinamis dari relasi keuangan_kegiatan (pemasukan + pengeluaran jika total perputaran)
                        // Atau sesuaikan jika anggaran hanya dihitung dari nominal item tertentu
                        $keuangan = collect($kegiatanObj->keuanganKegiatan ?? []);
                        $totalPemasukan = $keuangan->where('jenis_transaksi', 'pemasukan')->sum('nominal');
                        $totalPengeluaran = $keuangan->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
                        $anggaranBersih = $totalPemasukan - $totalPengeluaran;
                    @endphp

                    <div class="p-3 rounded-lg bg-[#F7F8FC]">
                        <div class="flex justify-between gap-2">
                            {{-- Judul Kegiatan dari Database --}}
                            <div class="font-semibold text-sm text-[#1C1E2C] truncate" title="{{ $kegiatanObj->judul_kegiatan }}">
                                {{ $kegiatanObj->judul_kegiatan }}
                            </div>
                            
                            {{-- Penentuan Warna Badge Berdasarkan Status Asli Database --}}
                            @if($kegiatanObj->status === 'Berlangsung')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#CCFBF1] text-[#0F766E]">
                                    Berlangsung
                                </span>
                            @elseif($kegiatanObj->status === 'Mendatang')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#E0F2FE] text-[#0369A1]">
                                    Mendatang
                                </span>
                            @elseif($kegiatanObj->status === 'Selesai')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#FEE2E2] text-[#991B1B]">
                                    {{ $kegiatanObj->status ?? 'Dibatalkan' }}
                                </span>
                            @endif
                        </div>
                        
                       <div class="flex items-center justify-between mt-1 text-xs text-[#6B7280]">
                            {{-- Format Tanggal Indonesia --}}
                            <span>{{ \Carbon\Carbon::parse($kegiatanObj->tanggal_kegiatan)->translatedFormat('d M Y') }}</span>
                            
                            {{-- Hasil Kalkulasi Anggaran Relasi dengan Kondisi Warna & Indikator --}}
                            <span class="font-semibold {{ $anggaranBersih < 0 ? 'text-[#EF4444]' : 'text-[#1A2B5C]' }}">
                                {{-- Tampilkan tanda + atau - (jika minus, gunakan abs() agar tanda minus bawaan PHP tidak dobel) --}}
                                {{ $anggaranBersih < 0 ? '-' : '+' }} Rp {{ number_format(abs($anggaranBersih), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-xs text-[#6B7280]">
                        Belum ada agenda kegiatan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection