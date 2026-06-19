@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Keuangan Ormawa Binaan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Pantauan keuangan ormawa yang Anda bina (read-only)')

@section('topbar_actions')


    <form action="{{ route('pembina.keuangan-binaan.index') }}" method="GET" class="w-full sm:w-auto flex items-center gap-2">
        <div class="relative w-full sm:w-64">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#6B7280]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>
            </div>
            
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Cari keterangan..." 
                onkeyup="cariDenganJeda()"
                onfocus="this.setSelectionRange(this.value.length, this.value.length);"
                autofocus
                class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-[#E5E7EB] rounded-xl outline-none focus:border-[#1A2B5C] focus:ring-[#1A2B5C] transition-all text-[#1C1E2C]"
            >
        </div>

    
    </form>
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-5">
    @php

        $totalSaldoGabungan = $totalPemasukan - $totalPengeluaran;

    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#1A2B5C] text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-wallet w-5 h-5"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path></svg>
                </div>
                <div>
                    <div class="text-xs text-[#6B7280]">Total Saldo Gabungan</div>
                    <div class="text-lg font-bold text-[#1A2B5C]">Rp {{ number_format($totalSaldoGabungan, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#22C55E] text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-5 h-5"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline></svg>
                </div>
                <div>
                    <div class="text-xs text-[#6B7280]">Total Pemasukan</div>
                    <div class="text-lg font-bold text-[#22C55E]">Rp {{ number_format($totalPemasukan , 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-[#EF4444] text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down w-5 h-5"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline><polyline points="16 17 22 17 22 11"></polyline></svg>
                </div>
                <div>
                    <div class="text-xs text-[#6B7280]">Total Pengeluaran</div>
                    <div class="text-lg font-bold text-[#EF4444]">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

    </div>

    @forelse($ormawaWithKeuangan as $ormawa)
        @php
            // 1. Inisialisasi variabel untuk ormawa ini
            $pemasukanOrmawa = 0;
            $pengeluaranOrmawa = 0;
            $daftarTransaksi = [];

            // 2. Loop kegiatan untuk menghitung total dan mengumpulkan riwayat transaksi

            foreach ($ormawa->kegiatan as $kegiatanItem) {
                if ($kegiatanItem->keuanganKegiatan) {
                    foreach ($kegiatanItem->keuanganKegiatan as $keuangan) {
                        
                        $nominal = (float) ($keuangan['nominal'] ?? 0);
                        $jenis = $keuangan['jenis_transaksi'] ?? '';

                        if ($jenis === 'pemasukan') {
                            $pemasukanOrmawa += $nominal;
                        } elseif ($jenis === 'pengeluaran') {
                            $pengeluaranOrmawa += $nominal;
                        }

                        // Simpan semua transaksi ke dalam satu array untuk tabel
                        $daftarTransaksi[] = [
                            'tanggal_raw' => $keuangan['created_at'],
                            'tanggal'     => \Carbon\Carbon::parse($keuangan['created_at'])->translatedFormat('j M Y'),
                            'kategori'    => $jenis === 'pemasukan' ? 'Pemasukan' : 'Pengeluaran', // Bisa disesuaikan
                            'keterangan'  => $keuangan['keterangan'] ?? '-',
                            'jenis'       => $jenis,
                            'nominal'     => $nominal
                        ];
                    }
                }
            }
            

            // 3. Kalkulasi saldo akhir & urutkan transaksi berdasarkan tanggal terbaru
            $saldoOrmawa = $pemasukanOrmawa - $pengeluaranOrmawa;
            
            usort($daftarTransaksi, function($a, $b) {
                return strtotime($b['tanggal_raw']) - strtotime($a['tanggal_raw']);
            });
        @endphp

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div>
                        <div class="font-bold text-[#1C1E2C]">{{ $ormawa['nama'] }}</div>
                        <div class="text-xs text-[#6B7280]">Saldo: Rp {{ number_format($saldoOrmawa, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="flex gap-2 text-sm">
                    <span class="px-3 py-1 rounded-full bg-[#22C55E]/10 text-[#16A34A] font-semibold">+ Rp {{ number_format($pemasukanOrmawa, 0, ',', '.') }}</span>
                    <span class="px-3 py-1 rounded-full bg-[#EF4444]/10 text-[#EF4444] font-semibold">− Rp {{ number_format($pengeluaranOrmawa, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[600px]">
                    <thead class="bg-[#F7F8FC] text-[#6B7280]">
                        <tr>
                            <th class="text-left px-4 py-2 font-semibold">Tanggal</th>
                            <th class="text-left px-4 py-2 font-semibold">Kategori</th>
                            <th class="text-left px-4 py-2 font-semibold">Keterangan</th>
                            <th class="text-right px-4 py-2 font-semibold">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftarTransaksi as $trx)
                            <tr class="border-t border-[#E5E7EB]">
                                <td class="px-4 py-3 text-[#6B7280]">{{ $trx['tanggal'] }}</td>
                                <td class="px-4 py-3 text-[#6B7280]">{{ $trx['kategori'] }}</td>
                                <td class="px-4 py-3 text-[#1C1E2C]">{{ $trx['keterangan'] }}</td>
                                <td class="px-4 py-3 text-right font-semibold {{ $trx['jenis'] === 'pemasukan' ? 'text-[#22C55E]' : 'text-[#EF4444]' }}">
                                    {{ $trx['jenis'] === 'pemasukan' ? '+' : '−' }} Rp {{ number_format($trx['nominal'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t border-[#E5E7EB]">
                                <td colspan="4" class="px-4 py-4 text-center text-xs text-[#6B7280] bg-[#F7F8FC]/50">
                                    Belum ada riwayat transaksi keuangan pada ORMAWA ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-12 px-4 text-center border border-dashed border-[#E5E7EB] rounded-2xl bg-white shadow-[0_2px_12px_rgba(0,0,0,0.02)]">
            <div class="w-14 h-14 rounded-full bg-[#F7F8FC] text-[#6B7280] flex items-center justify-center mb-4 border border-[#E5E7EB]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
            </div>
            <p class="text-xs text-[#6B7280] max-w-sm mb-2">
                Tidak ada organisasi binaan
            </p>
        </div>
    @endforelse
</div>

@push('scripts')
<script>
    let timer;

    function cariDenganJeda() {
        // Hapus timer lama jika user masih terus mengetik
        clearTimeout(timer);

        // Buat timer baru, tunggu 500 milidetik sebelum submit
        timer = setTimeout(function() {
            document.getElementById('formSearch').submit();
        }, 500); 
    }

    // Mempertahankan posisi kursor fokus di akhir kata setelah halaman memuat ulang (reload)
    window.onload = function() {
        const input = document.getElementById('searchInput');
        if (input.value.length > 0) {
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);
        }
    };
</script>
@endpush
@endsection