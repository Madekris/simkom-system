@extends('layouts.app')

@section('content')
<div class="flex-1 min-h-screen bg-[#F7F8FC]">
    

    <div class="p-6 lg:p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
            <p class="text-gray-500 text-sm mt-1">Ringkasan sistem SIMKOM secara keseluruhan</p>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @include('components.stat-card', ['title' => 'Total Ormawa', 'value' => $total_ormawa, 'icon' => 'ormawa'])
            @include('components.stat-card', ['title' => 'Total Anggota', 'value' => $total_anggota, 'icon' => 'anggota'])
            @include('components.stat-card', ['title' => 'Kegiatan Aktif', 'value' => $kegiatan_aktif, 'icon' => 'kegiatan'])
            @include('components.stat-card', ['title' => 'Persetujuan Pending', 'value' => $pending_dokumen, 'icon' => 'pending'])
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm lg:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="font-bold text-gray-900">Tren Kegiatan 6 Bulan Terakhir</h3>
                        <p class="text-xs text-gray-500">Total kegiatan terselenggara per bulan</p>
                    </div>
                    <button class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Filter
                    </button>
                </div>
                <div class="h-64 relative">
                    <canvas id="chartTrenKegiatan"></canvas>
                </div>
            </div>

            <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-gray-900">Status Ormawa</h3>
                    <p class="text-xs text-gray-500 mb-4">Distribusi keaktifan</p>
                </div>
                <div class="w-40 h-40 mx-auto relative flex items-center justify-center">
                    <canvas id="chartStatusOrmawa"></canvas>
                    <div class="absolute text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $total_ormawa }}</div>
                        <div class="text-[10px] text-gray-400 uppercase font-semibold">Total</div>
                    </div>
                </div>
                <div class="space-y-2 mt-4">
                    <div class="flex justify-between text-xs font-medium text-gray-600">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></span> Aktif</span>
                        <span class="font-bold text-gray-900">{{ $ormawa_aktif }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-medium text-gray-600">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-[#94A3B8]"></span> Tidak Aktif</span>
                        <span class="font-bold text-gray-900">{{ $ormawa_tidak_aktif }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-4">Daftar Ormawa</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-400 text-xs font-semibold uppercase bg-gray-50 border-b border-gray-100">
                            <th class="py-3 px-4">Ormawa</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Anggota</th>
                            <th class="py-3 px-4">Ketua</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($organisasis as $o)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-4 font-semibold text-gray-900">{{ $o->nama }}</td>
                            <td class="py-3.5 px-4 text-gray-500">{{ $o->jenis }}</td>
                            <td class="py-3.5 px-4 text-gray-500">{{ $o->jumlah_anggota ?? '0' }}</td>
                            <td class="py-3.5 px-4 text-gray-500">{{ $o->ketua ?? '—' }}</td>
                            <td class="py-3.5 px-4">
                                @include('components.status-badge', ['status' => $o->status])
                            </td>
                            
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.organisasi.edit', $o->id) }}" class="text-[#1A2B5C] font-semibold text-xs inline-flex items-center gap-1 hover:underline">
                                    👁 Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                                    </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Batang (Tren Kegiatan)
    new Chart(document.getElementById('chartTrenKegiatan'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            datasets: [{
                data: [8, 12, 15, 10, 18, 14],
                backgroundColor: '#1A2B5C',
                borderRadius: 6,
                barThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { 
                y: { grid: { display: false }, border: { display: false } }, 
                x: { grid: { display: false }, border: { display: false } } 
            }
        }
    });

    // Grafik Lingkaran (Status Ormawa)
    new Chart(document.getElementById('chartStatusOrmawa'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [{{ $ormawa_aktif }}, {{ $ormawa_tidak_aktif }}],
                backgroundColor: ['#10B981', '#94A3B8'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: { legend: { display: false } }
        }
    });
</script>
@endsection