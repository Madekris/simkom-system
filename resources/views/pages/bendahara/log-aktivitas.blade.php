@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Log Aktivitas Saya')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Riwayat input transaksi & laporan keuangan Anda')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-[#F7F8FC] text-[#6B7280] border-b border-[#E5E7EB]">
                    <tr>
                        <th class="w-[200px] text-left px-5 py-3 font-semibold tracking-wide">Waktu</th>
                        <th class="w-[120px] text-left px-5 py-3 font-semibold tracking-wide">Aksi</th>
                        <th class="text-left px-5 py-3 font-semibold tracking-wide">Target</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E5E7EB]">
                    <tr class="hover:bg-[#F9FAFB] transition-colors">
                        <td class="px-5 py-4 text-[#6B7280] whitespace-nowrap">30 Mei 2026, 15:23</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                Tambah
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[#1C1E2C] font-medium">
                            Pemasukan: <span class="font-normal text-[#6B7280]">Iuran anggota Mei (Rp 2.400.000)</span>
                        </td>
                    </tr>

                    <tr class="hover:bg-[#F9FAFB] transition-colors">
                        <td class="px-5 py-4 text-[#6B7280] whitespace-nowrap">28 Mei 2026, 10:18</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                Tambah
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[#1C1E2C] font-medium">
                            Pengeluaran: <span class="font-normal text-[#6B7280]">Konsumsi rapat (Rp 350.000)</span>
                        </td>
                    </tr>

                    <tr class="hover:bg-[#F9FAFB] transition-colors">
                        <td class="px-5 py-4 text-[#6B7280] whitespace-nowrap">26 Mei 2026, 13:45</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#EFF6FF] text-[#1E40AF]">
                                Edit
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[#1C1E2C] font-medium">
                            Transaksi: <span class="font-normal text-[#6B7280]">Sewa tempat workshop</span>
                        </td>
                    </tr>

                    <tr class="hover:bg-[#F9FAFB] transition-colors">
                        <td class="px-5 py-4 text-[#6B7280] whitespace-nowrap">20 Mei 2026, 16:30</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                Tambah
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[#1C1E2C] font-medium">
                            Pemasukan: <span class="font-normal text-[#6B7280]">Sponsor Workshop AI</span>
                        </td>
                    </tr>

                    <tr class="hover:bg-[#F9FAFB] transition-colors">
                        <td class="px-5 py-4 text-[#6B7280] whitespace-nowrap">1 Mei 2026, 09:00</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#374151]">
                                Unduh
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[#1C1E2C] font-medium">
                            Laporan: <span class="font-normal text-[#6B7280]">PDF April 2026</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection