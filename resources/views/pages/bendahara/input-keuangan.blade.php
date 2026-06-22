@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Input Keuangan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Catat transaksi pemasukan & pengeluaran')

{{-- Isi Tombol / Aksi di Sebelah Kanan (Dropdown PDF & Excel) --}}
@section('topbar_actions')
    <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
        <button @click="open = !open" type="button"
            class="inline-flex items-center justify-center text-sm font-medium transition-all h-9 rounded-lg px-4 gap-2 border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-[#F7F8FC] shadow-sm cursor-pointer outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-[#1A2B5C]">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" x2="12" y1="15" y2="3"></line>
            </svg> 
            Export Bulan Ini
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-transform duration-200 text-gray-400" :class="open ? 'rotate-180' : ''"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        <div x-show="open" 
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 mt-2 w-44 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50" 
             style="display: none;">
            <div class="py-1">
                {{-- Pilihan Cetak PDF --}}
                <a href="{{ route('bendahara.input-keuangan.export', ['format' => 'pdf']) }}" 
                   class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 gap-2 font-medium decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M9 15h3a1.5 1.5 0 0 0 0-3H9v6"/><path d="M12 12v3"/></svg>
                    Cetak ke PDF
                </a>
                {{-- Pilihan Unduh Excel --}}
                <a href="{{ route('bendahara.input-keuangan.export', ['format' => 'excel']) }}" 
                   class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 gap-2 font-medium decoration-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 12h4v6"/><path d="M12 15H8"/></svg>
                    Unduh Excel
                </a>
            </div>
        </div>
    </div>
@endsection

@section('content')

@php
    $totalMasuk = 0;
    $totalKeluar = 0;

    foreach ($semuaTransaksi as $keuangan) {
        if ($keuangan->jenis_transaksi === 'pemasukan') {
            $totalMasuk += $keuangan->nominal;
        } elseif ($keuangan->jenis_transaksi === 'pengeluaran') {
            $totalKeluar += $keuangan->nominal;
        }
    }

    $selisih = $totalMasuk - $totalKeluar;
@endphp

<div class="p-4 sm:p-6 lg:p-8 grid grid-cols-3 gap-5">
    <div x-data="{ 
        tipeTransaksi: '{{ old('jenis_transaksi', 'pemasukan') }}',
        filePreview: '{{ session('old_bukti_path') && !Str::endsWith(session('old_bukti_path'), '.pdf') ? asset('storage/' . session('old_bukti_path')) : '' }}', 
        isPdf: {{ session('old_bukti_path') && Str::endsWith(session('old_bukti_path'), '.pdf') ? 'true' : 'false' }},
        fileName: '{{ session('old_bukti_name', '') }}',
        hasOldFile: {{ session('old_bukti_path') ? 'true' : 'false' }},
        
        handleFileChange(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            this.fileName = file.name;
            this.hasOldFile = false; 
            
            if (file.type === 'application/pdf') {
                this.isPdf = true;
                this.filePreview = '';
            } else if (file.type.startsWith('image/')) {
                this.isPdf = false;
                this.filePreview = URL.createObjectURL(file);
            }
        }
    }" 
    class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 col-span-1 h-fit">
        
        <h3 class="font-bold text-[#1C1E2C] mb-4">Tambah Transaksi</h3>

        <div class="grid grid-cols-2 gap-2 mb-4">
            <button type="button" 
                    @click="tipeTransaksi = 'pemasukan'"
                    :class="tipeTransaksi === 'pemasukan' 
                        ? 'bg-[#22C55E] text-white border-[#22C55E]' 
                        : 'bg-white text-[#6B7280] border-[#E5E7EB] hover:bg-[#F7F8FC]'"
                    class="px-3 py-2.5 rounded-lg text-sm font-semibold border inline-flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 8v8"></path>
                    <path d="m8 12 4 4 4-4"></path>
                </svg> 
                Pemasukan
            </button>

            <button type="button" 
                    @click="tipeTransaksi = 'pengeluaran'"
                    :class="tipeTransaksi === 'pengeluaran' 
                        ? 'bg-[#EF4444] text-white border-[#EF4444]' 
                        : 'bg-white text-[#6B7280] border-[#E5E7EB] hover:bg-[#F7F8FC]'"
                    class="px-3 py-2.5 rounded-lg text-sm font-semibold border inline-flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="m16 12-4-4-4 4"></path>
                    <path d="M12 16V8"></path>
                </svg> 
                Pengeluaran
            </button>
        </div>

        <form action="{{ route('bendahara.input-keuangan.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="flex flex-col gap-4">
            @csrf

            <input type="hidden" name="jenis_transaksi" :value="tipeTransaksi">
            @error('jenis_transaksi') <span class="text-xs text-[#EF4444] mt-1 block">{{ $message }}</span> @enderror

            <div>
                <label class="block text-sm font-medium text-[#1C1E2C]">Tanggal <span class="text-[#EF4444]">*</span></label>
                <input type="date" 
                    name="tanggal" 
                    value="{{ old('tanggal') }}" 
                    required
                    class="mt-1.5 w-full h-9 px-3 text-sm bg-white border border-[#E5E7EB] rounded-lg outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/10 transition-all @error('tanggal') border-[#EF4444] @enderror">
                @error('tanggal') <span class="text-xs text-[#EF4444] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1C1E2C]">Kegiatan Terkait</label>
                <select name="id_kegiatan" class="mt-1.5 w-full h-9 px-3 text-sm bg-white border border-[#E5E7EB] rounded-lg outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/10 transition-all @error('id_kegiatan') border-[#EF4444] @enderror">
                    <option value="" disabled {{ old('id_kegiatan') ? '' : 'selected' }}>Pilih Kegiatan</option>
                    @foreach($kegiatanOrganisasi as $kegiatan)
                        <option value="{{ $kegiatan['id'] }}" {{ old('id_kegiatan') == $kegiatan['id'] ? 'selected' : '' }}>
                            {{ $kegiatan['judul_kegiatan'] }}
                        </option>
                    @endforeach
                </select>
                @error('id_kegiatan') <span class="text-xs text-[#EF4444] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1C1E2C]">Jumlah (Rp) <span class="text-[#EF4444]">*</span></label>
                <input type="number" 
                    name="nominal" 
                    value="{{ old('nominal') }}" 
                    placeholder="0" 
                    required
                    class="mt-1.5 w-full h-9 px-3 text-sm bg-white border border-[#E5E7EB] rounded-lg outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/10 transition-all @error('nominal') border-[#EF4444] @enderror">
                @error('nominal') <span class="text-xs text-[#EF4444] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1C1E2C]">Keterangan</label>
                <textarea name="keterangan" 
                        rows="2" 
                        placeholder="Detail transaksi..." 
                        class="mt-1.5 w-full p-3 text-sm bg-white border border-[#E5E7EB] rounded-lg outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/10 transition-all resize-none @error('keterangan') border-[#EF4444] @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span class="text-xs text-[#EF4444] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-[#1C1E2C]">Bukti (Foto/Struk) <span class="text-[#EF4444]">*</span></label>
                
                <label class="mt-1.5 border-2 border-dashed rounded-lg p-4 text-center cursor-pointer transition-all group block {{ $errors->has('bukti') ? 'border-[#EF4444] bg-[#EF4444]/5' : 'border-[#E5E7EB] hover:border-[#1A2B5C] hover:bg-[#F7F8FC]' }}">
                    <input type="file" 
                        name="bukti" 
                        id="bukti" 
                        class="hidden" 
                        accept="image/png, image/jpeg, image/jpg, application/pdf"
                        @change="handleFileChange"
                    >

                    <div x-show="!filePreview && !isPdf">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 mx-auto mb-1 transition-colors {{ $errors->has('bukti') ? 'text-[#EF4444]' : 'text-[#6B7280] group-hover:text-[#1A2B5C]' }}">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" x2="12" y1="3" y2="15"></line>
                        </svg>
                        <span class="block text-xs font-medium transition-colors {{ $errors->has('bukti') ? 'text-[#EF4444]' : 'text-[#6B7280] group-hover:text-[#1A2B5C]' }}">
                            Klik untuk upload bukti (wajib)
                        </span>
                        <div class="text-[10px] mt-0.5 {{ $errors->has('bukti') ? 'text-[#EF4444]/80' : 'text-[#9CA3AF]' }}">JPG/PNG/PDF maks 5 MB</div>
                    </div>

                    <div x-show="filePreview" class="space-y-2" style="display: none;">
                        <img :src="filePreview" class="max-h-48 mx-auto rounded-lg object-contain shadow-sm border border-[#E5E7EB]">
                        <p class="text-xs text-[#22C55E] font-medium flex items-center justify-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span x-text="hasOldFile ? 'Menggunakan file tersimpan' : 'Gambar berhasil dipilih'"></span>
                        </p>
                        <span x-text="fileName" class="block text-[11px] text-[#6B7280] truncate max-w-xs mx-auto"></span>
                    </div>

                    <div x-show="isPdf" class="space-y-2 py-2" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-10 h-10 mx-auto text-[#EF4444]">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                            <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                        </svg>
                        <p class="text-xs text-[#22C55E] font-medium flex items-center justify-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            <span x-text="hasOldFile ? 'Menggunakan PDF tersimpan' : 'Dokumen PDF dipilih'"></span>
                        </p>
                        <span x-text="fileName" class="block text-xs font-semibold text-[#1C1E2C] truncate max-w-xs mx-auto"></span>
                    </div>
                </label>

                <template x-if="hasOldFile">
                    <div class="text-[11px] text-[#1A2B5C] mt-1 bg-[#1A2B5C]/5 p-2 rounded-lg text-center">
                        ℹ️ Berkas lama tersimpan aman. Klik kotak jika ingin mengganti berkas baru.
                    </div>
                </template>
                @error('bukti') <span class="text-xs text-[#EF4444] mt-1 block">{{ $message }}</span> @enderror
            </div>

            <button type="submit" 
                    :class="tipeTransaksi === 'pemasukan'
                        ? 'bg-[#22C55E] hover:bg-[#16A34A] focus:ring-[#22C55E]/20' 
                        : 'bg-[#EF4444] hover:bg-[#DC2626] focus:ring-[#EF4444]/20'"
                    class="w-full inline-flex items-center justify-center text-sm font-semibold h-9 rounded-lg text-white shadow-sm transition-all focus:ring-4 outline-none cursor-pointer">
                Simpan Transaksi
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 col-span-2">
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#1C1E2C]">Riwayat Transaksi</h3>
                
                <div class="flex items-center gap-2">
                    <form id="filterForm" action="{{ route('bendahara.input-keuangan.create') }}" method="GET">
                        <div class="relative">
                            <select name="id_kegiatan" 
                                    onchange="document.getElementById('filterForm').submit()"
                                    class="appearance-none bg-white border border-[#E5E7EB] text-sm font-medium text-[#1C1E2C] h-8 rounded-md pl-3 pr-8 outline-none focus:border-[#1A2B5C] focus:ring-[#1A2B5C] transition-all cursor-pointer">
                                <option value="">Semua Kegiatan</option>
                                @foreach($kegiatanOrganisasi as $kegiatan)
                                    <option value="{{ $kegiatan['id'] }}" {{ request('id_kegiatan') == $kegiatan['id'] ? 'selected' : '' }}>
                                        {{ $kegiatan['judul_kegiatan'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('bendahara.input-keuangan.create') }}"
                        class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all border border-[#E5E7EB] bg-white text-[#1C1E2C] hover:bg-gray-50 h-8 rounded-md gap-1.5 px-3 cursor-pointer outline-none decoration-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path></svg> 
                        Clear
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-3 mb-4">
            <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-4 bg-[#DCFCE7]/40 border-[#DCFCE7]">
                <div class="text-xs text-[#166534] font-semibold uppercase">Total Masuk</div>
                <div class="text-xl font-bold text-[#166534] mt-1">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-4 bg-[#FEE2E2]/40 border-[#FEE2E2]">
                <div class="text-xs text-[#991B1B] font-semibold uppercase">Total Keluar</div>
                <div class="text-xl font-bold text-[#991B1B] mt-1">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl border p-4 shadow-[0_2px_12px_rgba(0,0,0,0.04)] transition-all
                {{ $selisih >= 0 ? 'bg-[#DCFCE7]/40 border-[#DCFCE7]' : 'bg-[#FEE2E2]/40 border-[#FEE2E2]' }}">
                <div class="text-xs font-semibold uppercase {{ $selisih >= 0 ? 'text-[#166534]' : 'text-[#991B1B]' }}">
                    Selisih (Saldo)
                </div>
                <div class="text-xl font-bold mt-1 {{ $selisih >= 0 ? 'text-[#166534]' : 'text-[#991B1B]' }}">
                    {{ $selisih >= 0 ? '+' : '' }}Rp {{ number_format($selisih, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-[#F7F8FC] text-[#6B7280]">
                <tr>
                    <th class="text-left px-4 py-2 font-semibold">Tanggal</th>
                    <th class="text-left px-4 py-2 font-semibold">Kegiatan</th>
                    <th class="text-left px-4 py-2 font-semibold">Keterangan</th>
                    <th class="text-right px-4 py-2 font-semibold">Jumlah</th>
                    <th class="text-center px-4 py-2 font-semibold">Bukti</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($semuaTransaksi as $transaksi)
                    @php
                        $dataKegiatan = $kegiatan->where('id', $transaksi->id_kegiatan)->first();
                    @endphp

                    <tr class="border-t border-[#E5E7EB]">
                        <td class="px-4 py-3 text-[#6B7280]">{{ $transaksi['created_at'] }}</td>
                        <td class="px-4 py-3 text-[#6B7280]">{{ $dataKegiatan['judul_kegiatan'] ?? '-' }}</td>
                        <td class="px-4 py-3 font-semibold text-[#1C1E2C]">{{ $transaksi['keterangan'] }}</td>
                        
                        <td class="px-4 py-3 text-right font-bold {{ $transaksi['jenis_transaksi'] === 'pemasukan' ? 'text-[#22C55E]' : 'text-[#EF4444]' }}">
                            {{ $transaksi['jenis_transaksi'] === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($transaksi['nominal'], 0, ',', '.') }}
                        </td>
                        
                        <td class="px-4 py-3 text-center flex flex-row justify-center gap-1">
                            <button class="hover:bg-[#1A2B5C]/10 rounded-md p-1 text-[#1A2B5C] h-7 w-7 inline-flex items-center justify-center cursor-pointer border-0 bg-transparent" 
                                title="Lihat Bukti"
                                @click="$dispatch('open-preview', { 
                                    url: '{{ asset('storage/' . $transaksi['bukti_pembayaran']) }}', 
                                    isPdf: {{ Str::endsWith($transaksi['bukti_pembayaran'], '.pdf') ? 'true' : 'false' }}
                                })">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M13.234 20.252 21 12.3"></path><path d="m16 6-8.414 8.586a2 2 0 0 0 0 2.828 2 2 0 0 0 2.828 0l8.414-8.586a4 4 0 0 0 0-5.656 4 4 0 0 0-5.656 0l-8.415 8.585a6 6 0 1 0 8.486 8.486"></path></svg>
                            </button>
                            
                            <a href="{{ asset('storage/' . $transaksi['bukti_pembayaran']) }}" download="Bukti_{{ $transaksi['id'] }}" 
                               class="hover:bg-[#1A2B5C]/10 rounded-md p-1 text-[#1A2B5C] h-7 w-7 inline-flex items-center justify-center decoration-none" 
                               title="Download Bukti">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" x2="12" y1="15" y2="3"></line></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 bg-gray-50 rounded-full mb-4 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-12 h-12">
                                        <path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-2" />
                                        <path d="M16 11h.01" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-[#1C1E2C] mb-1">Belum Ada Transaksi</h3>
                                <p class="text-sm text-[#6B7280] max-w-sm">
                                    Riwayat pemasukan atau pengeluaran kas belum tercatat. Silakan gunakan menu di sebelah kiri untuk menambah transaksi baru.
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection