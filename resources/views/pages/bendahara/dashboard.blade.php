@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Dashboard Keuangan')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', $organisasi->nama)

@section('content')

<div class="p-4 sm:p-6 lg:p-8 space-y-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 bg-gradient-to-br from-[#1A2B5C] to-[#0F1B3D] text-white">
            <div class="text-xs uppercase tracking-wider text-white/70 font-semibold">
                Saldo Saat Ini
            </div>
            <div class="text-3xl font-bold mt-2">
                Rp {{ number_format($saldoSaatIni, 0, ',', '.') }}
            </div>
            
            {{-- Kondisi warna teks tren: hijau jika positif, merah jika negatif --}}
            <div class="flex items-center gap-1 text-xs mt-2 {{ $trenSaldo >= 0 ? 'text-[#22C55E]' : 'text-[#EF4444]' }}">
                @if($trenSaldo >= 0)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-up w-4 h-4">
                        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"></polyline>
                        <polyline points="16 7 22 7 22 13"></polyline>
                    </svg> 
                    +{{ $trenSaldo }}% dari bulan lalu
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trending-down w-4 h-4">
                        <polyline points="22 17 13.5 8.5 8.5 13.5 2 7"></polyline>
                        <polyline points="16 17 22 17 22 11"></polyline>
                    </svg>
                    {{ $trenSaldo }}% dari bulan lalu
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">
                        Pemasukan Bulan Ini
                    </div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">
                        Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}
                    </div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#22C55E] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-down w-5 h-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 8v8"></path>
                        <path d="m8 12 4 4 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">
                        Pengeluaran Bulan Ini
                    </div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">
                        Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}
                    </div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#EF4444] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-up w-5 h-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="m16 12-4-4-4 4"></path>
                        <path d="M12 16V8"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-[#1C1E2C]">Arus Kas 6 Bulan Terakhir</h3>
                <p class="text-xs text-[#6B7280]">Pemasukan vs Pengeluaran</p>
            </div>
            <div class="flex gap-4 text-xs font-medium">
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#22C55E]"></span> Masuk
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-3 h-3 rounded-full bg-[#EF4444]"></span> Keluar
                </span>
            </div>
        </div>

        <div class="relative w-full h-[280px]">
            <canvas id="arusKasChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
        <h3 class="font-bold text-[#1C1E2C] mb-3">Transaksi Terbaru</h3>
        
        <div class="space-y-3">
            {{-- Looping Data Dinamis dari Controller --}}
            @forelse($transaksiTerbaru as $transaksi)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        
                        {{-- Cek Kolom jenis_transaksi dari database --}}
                        @if($transaksi->jenis_transaksi === 'pemasukan')
                            <div class="w-9 h-9 rounded-full flex items-center justify-center bg-[#DCFCE7] text-[#22C55E]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-down w-4 h-4">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 8v8"></path>
                                    <path d="m8 12 4 4 4-4"></path>
                                </svg>
                            </div>
                        @else
                            <div class="w-9 h-9 rounded-full flex items-center justify-center bg-[#FEE2E2] text-[#EF4444]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-arrow-up w-4 h-4">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="m16 12-4-4-4 4"></path>
                                    <path d="M12 16V8"></path>
                                </svg>
                            </div>
                        @endif

                        <div>
                            {{-- Mengambil kolom keterangan sebagai judul --}}
                            <div class="text-sm font-semibold">{{ $transaksi->keterangan }}</div>
                            {{-- Mengubah created_at menjadi format Indonesia secara dinamis --}}
                            <div class="text-xs text-[#6B7280]">
                                {{ \Carbon\Carbon::parse($transaksi->created_at)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>

                    {{-- Menentukan warna teks, simbol (+/-), dan format rupiah angka nominal --}}
                    <div class="font-bold text-sm {{ $transaksi->jenis_transaksi === 'pemasukan' ? 'text-[#22C55E]' : 'text-[#EF4444]' }}">
                        {{ $transaksi->jenis_transaksi === 'pemasukan' ? '+' : '-' }}Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                    </div>
                </div>
            @empty
                {{-- Tampilan Cadangan Jika Organisasi Belum Memiliki Transaksi Sama Sekali --}}
                <div class="text-center py-6 text-sm text-[#6B7280]">
                    Belum ada riwayat transaksi keuangan.
                </div>
            @endforelse
        </div>
    </div>

</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('arusKasChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                // Mengambil array label bulan dari controller secara dinamis
                labels: @json($chartLabels), 
                datasets: [
                    {
                        label: 'Masuk',
                        // Mengambil array nominal pemasukan dinamis
                        data: @json($chartMasukData), 
                        borderColor: '#22C55E',
                        backgroundColor: '#22C55E',
                        borderWidth: 3,
                        tension: 0.3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#22C55E',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Keluar',
                        // Mengambil array nominal pengeluaran dinamis
                        data: @json($chartKeluarData), 
                        borderColor: '#EF4444',
                        backgroundColor: '#EF4444',
                        borderWidth: 3,
                        tension: 0.1,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#EF4444',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Dimatikan karena kita sudah pakai legend kustom HTML di atas agar gaya desain tidak berubah
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: true,
                            color: '#F1F2F6',
                            borderDash: [3, 3] // Efek garis putus-putus
                        },
                        ticks: {
                            color: '#6B7280',
                            font: { size: 12 }
                        }
                    },
                    y: {
                        grid: {
                            display: true,
                            color: '#F1F2F6',
                            borderDash: [3, 3]
                        },
                        ticks: {
                            color: '#6B7280',
                            font: { size: 12 },
                            // Format label Y axis menjadi format jutaan ringkas (Contoh: 1.5jt, 3jt)
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000) + 'jt';
                                }
                                return value;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection