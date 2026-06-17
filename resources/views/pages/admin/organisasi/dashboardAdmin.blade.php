@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Dashboard Admin')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Ringkasan sistem SIMKOM secara keseluruhan')

{{-- Isi Tombol / Aksi di Sebelah Kanan (Opsional) --}}
@section('topbar_actions')

@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-6">

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Total Ormawa</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">25</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#1A2B5C] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building-2 w-5 h-5">
                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"></path>
                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"></path>
                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"></path>
                        <path d="M10 6h4"></path>
                        <path d="M10 10h4"></path>
                        <path d="M10 14h4"></path>
                        <path d="M10 18h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Total Anggota</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">542</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#00C9A7] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Kegiatan Aktif</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">18</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#F5A623] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-range w-5 h-5">
                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                        <path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <path d="M8 2v4"></path>
                        <path d="M17 14h-6"></path>
                        <path d="M13 18H7"></path>
                        <path d="M7 14h.01"></path>
                        <path d="M17 18h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs text-[#6B7280] uppercase tracking-wide font-medium">Persetujuan Pending</div>
                    <div class="text-2xl font-bold text-[#1C1E2C] mt-1">7</div>
                </div>
                <div class="w-11 h-11 rounded-lg flex items-center justify-center bg-[#EF4444] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-triangle-alert w-5 h-5">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"></path>
                        <path d="M12 9v4"></path>
                        <path d="M12 17h.01"></path>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] lg:col-span-2 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-[#1C1E2C]">Tren Kegiatan 6 Bulan Terakhir</h3>
                    <p class="text-xs text-[#6B7280]">Total kegiatan terselenggara per bulan</p>
                </div>
                <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none border bg-background text-foreground hover:bg-accent h-8 rounded-md gap-1.5 px-3 border-[#E5E7EB]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-funnel w-4 h-4 mr-1">
                        <path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path>
                    </svg> Filter
                </button>
            </div>
    
            <div class="relative w-full" style="height: 260px;">
                <canvas id="trenKegiatanChart"></canvas>
            </div>
        </div>
    
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-[#1C1E2C]">Status Ormawa</h3>
                <p class="text-xs text-[#6B7280]">Distribusi keaktifan</p>
            </div>
    
            <div class="flex flex-col items-center justify-center flex-1 py-6 gap-6">
                
                <div class="relative w-36 h-36">
                    <canvas id="statusOrmawaChart"></canvas>
                    
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold text-[#1C1E2C]">25</span>
                        <span class="text-[10px] text-[#6B7280]">Total</span>
                    </div>
                </div>
    
                <div class="w-full space-y-2">
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[#F7F8FC]">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full shrink-0 bg-[#22C55E]"></span>
                            <span class="text-sm text-[#374151]">Aktif</span>
                        </div>
                        <span class="font-bold text-[#1C1E2C]">22</span>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-[#F7F8FC]">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full shrink-0 bg-[#9CA3AF]"></span>
                            <span class="text-sm text-[#374151]">Tidak Aktif</span>
                        </div>
                        <span class="font-bold text-[#1C1E2C]">3</span>
                    </div>
                </div>
    
            </div>
        </div>

    </div>


    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6">
        <h3 class="font-bold text-[#1C1E2C] mb-3">Persetujuan Pending</h3>
        <div class="space-y-3">
            
            <!-- Item Pending 1 -->
            <div class="flex items-center justify-between p-3 rounded-lg bg-[#F7F8FC]">
                <div>
                    <div class="font-semibold text-sm text-[#1C1E2C]">Workshop AI 2026</div>
                    <div class="text-xs text-[#6B7280]">BEM · 5 Jun 2026</div>
                </div>
                <div class="flex gap-2">
                    <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none border rounded-md px-3 border-[#EF4444] text-[#EF4444] h-8 bg-white hover:bg-red-50">Tolak</button>
                    <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none rounded-md px-3 bg-[#22C55E] hover:bg-[#16A34A] text-white h-8">Setujui</button>
                </div>
            </div>

            <!-- Item Pending 2 -->
            <div class="flex items-center justify-between p-3 rounded-lg bg-[#F7F8FC]">
                <div>
                    <div class="font-semibold text-sm text-[#1C1E2C]">Lomba Coding Internal</div>
                    <div class="text-xs text-[#6B7280]">HIMA TI · 8 Jun 2026</div>
                </div>
                <div class="flex gap-2">
                    <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none border rounded-md px-3 border-[#EF4444] text-[#EF4444] h-8 bg-white hover:bg-red-50">Tolak</button>
                    <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none rounded-md px-3 bg-[#22C55E] hover:bg-[#16A34A] text-white h-8">Setujui</button>
                </div>
            </div>

            <!-- Item Pending 3 -->
            <div class="flex items-center justify-between p-3 rounded-lg bg-[#F7F8FC]">
                <div>
                    <div class="font-semibold text-sm text-[#1C1E2C]">Konser Akhir Tahun</div>
                    <div class="text-xs text-[#6B7280]">Ormawa Musik · 15 Jun 2026</div>
                </div>
                <div class="flex gap-2">
                    <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none border rounded-md px-3 border-[#EF4444] text-[#EF4444] h-8 bg-white hover:bg-red-50">Tolak</button>
                    <button data-slot="button" class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium transition-all outline-none rounded-md px-3 bg-[#22C55E] hover:bg-[#16A34A] text-white h-8">Setujui</button>
                </div>
            </div>

        </div>
    </div>

</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('trenKegiatanChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Total Kegiatan',
                        // Menyesuaikan data tinggi grafik pada SVG Recharts bawaan asli Anda
                        data: [8, 12, 15, 10, 18, 14], 
                        backgroundColor: '#1A2B5C', // Warna Navy bawaan
                        borderPenetration: 0,
                        borderWidth: 0,
                        borderRadius: {
                            topLeft: 6,
                            topRight: 6,
                            bottomLeft: 0,
                            bottomRight: 0
                        },
                        borderSkipped: 'bottom',
                        barPercentage: 0.6, // Mengatur kelebaran bar agar terlihat proporsional
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Sembunyikan label dataset bawaan karena sudah diwakili header
                        },
                        tooltip: {
                            backgroundColor: '#FFFFFF',
                            titleColor: '#1C1E2C',
                            bodyColor: '#6B7280',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 10,
                            boxPadding: 4,
                            usePointStyle: true,
                            // Kustomisasi bayangan & font pada tooltip agar senada dengan UI
                            titleFont: {
                                weight: 'bold',
                                size: 13
                            },
                            bodyFont: {
                                size: 12
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false, // Menghilangkan garis vertikal di dalam chart
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6B7280', // Warna abu-abu teks sumbu X
                                font: {
                                    size: 12
                                }
                            }
                        },
                        y: {
                            min: 0,
                            max: 20, // Batas maksimal sesuai sumbu Y bawaan data Anda
                            ticks: {
                                stepSize: 5, // Kelipatan rentang nilai sumbu Y (0, 5, 10, 15, 20)
                                color: '#6B7280',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: '#F3F4F6', // Garis horizontal halus di dalam grafik
                                drawBorder: false
                            }
                        }
                    }
                }
            });


            const ctx2 = document.getElementById('statusOrmawaChart').getContext('2d');
        
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Aktif', 'Tidak Aktif'],
                    datasets: [{
                        data: [22, 3],
                        backgroundColor: [
                            '#22C55E', // Hijau Aktif
                            '#9CA3AF'  // Abu-abu Tidak Aktif
                        ],
                        borderWidth: 0, // Menghilangkan border putih bawaan antar potongan chart
                        hoverOffset: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '83%', // Mengatur ketebalan lingkaran (semakin besar %, semakin tipis lingkarannya)
                    plugins: {
                        legend: {
                            display: false // Menyembunyikan legenda bawaan Chart.js karena kita pakai HTML kustom di bawah
                        },
                        tooltip: {
                            backgroundColor: '#FFFFFF',
                            titleColor: '#1C1E2C',
                            bodyColor: '#6B7280',
                            borderColor: '#E5E7EB',
                            borderWidth: 1,
                            padding: 8,
                            boxPadding: 4,
                            usePointStyle: true,
                            titleFont: { weight: 'bold' }
                        }
                    }
                }
            });
        });
    </script>
@endpush
@endsection