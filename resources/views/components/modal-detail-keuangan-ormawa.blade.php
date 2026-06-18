@props([
    'id',
    'nama',
    'pembina',
    'realisasiPersen',
    'riwayat' => [] // Array berisi list transaksi
])


<div id="{{ $id }}" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeModal('{{ $id }}')"></div>

    <!-- Konten Box Modal -->
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-xl max-h-[90vh] flex flex-col relative z-10">
        
        <!-- Header -->
        <div class="flex items-center gap-3 px-6 py-4 border-b border-[#E5E7EB] shrink-0">
            <div class="w-10 h-10 rounded-xl bg-[#1A2B5C] text-white flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"></path><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"></path></svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-[#1C1E2C] truncate">{{ $nama }}</div>
                <div class="text-xs text-[#6B7280]">Pembina: {{ $pembina }}</div>
            </div>
            <button onclick="closeModal('{{ $id }}')" class="w-8 h-8 rounded-full hover:bg-[#F7F8FC] flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 text-[#6B7280]"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-5">
            <!-- Grid Statistik -->
            @php
                // 1. Inisialisasi variabel awal
                $pemasukan = 0;
                $pengeluaran = 0;
                $riwayatTransaksi = [];

                foreach ($riwayat as $kegiatan) {
                    if ($kegiatan->keuanganKegiatan){
                        foreach ($kegiatan->keuanganKegiatan as $keuangan) {
                          
                            $nominal = (float) ($keuangan['nominal'] ?? 0);
                            $jenis = $keuangan['jenis_transaksi'] ?? '';

                            // Pilah jenis transaksi untuk kalkulasi total
                            if ($jenis === 'pemasukan') {
                                $pemasukan += $nominal;
                                
                            } elseif ($jenis === 'pengeluaran') {
                                $pengeluaran += $nominal;
                            }
                                
                        }
                    }
                }
                    
                $saldo = $pemasukan - $pengeluaran;
                $realisasiPersen = $pemasukan > 0 ? round(($pengeluaran / $pemasukan) * 100) : 0;
            @endphp
            <div class="grid grid-cols-3 gap-3">
                <div class="rounded-xl bg-[#F7F8FC] p-3 text-center">
                    <div class="text-[10px] font-semibold text-[#6B7280] uppercase tracking-wider mb-1">Saldo</div>
                    <div class="font-bold text-sm text-[#1A2B5C]">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-xl bg-[#F7F8FC] p-3 text-center">
                    <div class="text-[10px] font-semibold text-[#6B7280] uppercase tracking-wider mb-1">Pemasukan</div>
                    <div class="font-bold text-sm text-[#22C55E]">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-xl bg-[#F7F8FC] p-3 text-center">
                    <div class="text-[10px] font-semibold text-[#6B7280] uppercase tracking-wider mb-1">Pengeluaran</div>
                    <div class="font-bold text-sm text-[#EF4444]">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div>
                <div class="flex justify-between text-xs text-[#6B7280] mb-1">
                    <span>Realisasi Pengeluaran</span>
                    <span>{{ $realisasiPersen }}% dari pemasukan</span>
                </div>
                <div class="h-2 rounded-full bg-[#E5E7EB] overflow-hidden">
                    <div class="h-full rounded-full bg-[#EF4444] transition-all" style="width: {{ $realisasiPersen }}%;"></div>
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div>
                <div class="font-bold text-sm text-[#1C1E2C] mb-3">Riwayat Transaksi</div>
                <div class="space-y-2">
                    {{-- <pre class="text-green-450 p-4 rounded-lg overflow-x-auto text-xs font-mono">
                        @json($riwayat, JSON_PRETTY_PRINT)
                    </pre> --}}
                    @forelse($riwayat as $trx)
                        @if ($trx->keuanganKegiatan)
                            
                            @forelse ( $trx->keuanganKegiatan as $dataKeuangan )
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#F7F8FC]">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $dataKeuangan->jenis_transaksi === 'pemasukan' ? 'bg-[#DCFCE7] text-[#16A34A]' : 'bg-[#FEE2E2] text-[#DC2626]' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                            @if($dataKeuangan->jenis_transaksi === 'pemasukan')
                                                <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline><polyline points="16 7 22 7 22 13"></polyline>
                                            @else
                                                <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline><polyline points="16 17 22 17 22 11"></polyline>
                                            @endif
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-[#1C1E2C] truncate">{{ $dataKeuangan['keterangan'] }}</div>
                                        <div class="text-xs text-[#6B7280]">{{ $dataKeuangan['created_at'] }}</div>
                                    </div>
                                    <div class="font-bold text-sm shrink-0 {{ $dataKeuangan->jenis_transaksi === 'pemasukan' ? 'text-[#16A34A]' : 'text-[#DC2626]' }}">
                                        {{ ($dataKeuangan->jenis_transaksi === 'pemasukan' ? '+' : '-') . 'Rp ' . number_format($dataKeuangan['nominal'], 0, ',', '.') }}
                                    </div>
                                </div>
                                
                            @empty
                            
                            @endforelse
                        @endif
                    @empty
                        <div class="text-center text-xs text-[#6B7280] py-4">Belum ada riwayat transaksi.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 border-t border-[#E5E7EB] shrink-0 flex justify-end gap-2">
            <button onclick="closeModal('{{ $id }}')" class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-[#E5E7EB] text-[#374151] hover:bg-gray-50 h-9 px-4">Tutup</button>
            <button class="inline-flex items-center justify-center rounded-md text-sm font-medium h-9 px-4 bg-[#1A2B5C] text-white hover:bg-[#1A2B5C]/90">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 mr-1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" x2="12" y1="15" y2="3"></line></svg> Export
            </button>
        </div>
    </div>
</div>