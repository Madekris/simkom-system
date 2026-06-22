@extends('layouts.app')

@section('topbar_title', 'Manajemen Anggota')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'BEM · (' . $listAnggota->count() . ') anggota')

@section('content')
<div class="p-4 md:p-5 space-y-4 select-none">
    
    {{-- Notifikasi Sukses / Alert --}}
    @if(session('success'))
        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-medium app-notification">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl p-2.5 border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2 flex-1 min-w-[260px]">
            <div class="relative w-full max-w-xs">
                <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" id="tableSearch" placeholder="Cari NIM atau nama..." class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/10 focus:border-amber-500 transition-all">
            </div>
            <select class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600 focus:outline-none cursor-pointer">
                <option>Semua</option>
            </select>
            <select class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600 focus:outline-none cursor-pointer">
                <option>Semua</option>
            </select>
        </div>
        <button class="flex items-center gap-1.5 px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-50 transition-colors">
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export
        </button>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative">
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500"></div>
        
        <div class="p-3 sm:p-4 flex items-center justify-between border-b border-slate-100 pl-4">
            <div>
                <h2 class="text-xs font-bold text-slate-900 tracking-tight">Verifikasi Pendaftaran Anggota</h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Pendaftar baru menunggu persetujuan Anda — pengurus tidak menambah anggota manual.</p>
            </div>
            <span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full whitespace-nowrap">
                {{ $pendaftarBaru->count() }} pendaftar
            </span>
        </div>
        
        <div class="bg-white pl-4">
            @if($pendaftarBaru->isEmpty())
                <div class="py-4 pr-4 flex flex-col items-center justify-center text-center">
                    <svg class="w-6 h-6 text-slate-300 stroke-[1.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <h3 class="text-[11px] font-bold text-slate-600 mt-1">Semua Beres!</h3>
                    <p class="text-[10px] text-slate-400">Tidak ada pendaftaran baru yang berstatus pending.</p>
                </div>
            @else
                <div class="pr-4 py-2 space-y-2">
                    @foreach($pendaftarBaru as $pendaftar)
                    <div class="p-2.5 border border-amber-100 bg-amber-50/10 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-[11px]">
                                {{ strtoupper(substr($pendaftar->user->mahasiswa->nama ?? 'B', 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-xs leading-none">{{ $pendaftar->user->mahasiswa->nama ?? 'Nama Tidak Ditemukan' }}</h4>
                                <p class="text-[10px] text-slate-400 mt-1">
                                    {{ $pendaftar->user->mahasiswa->nim ?? '-' }} · Angkatan {{ $pendaftar->user->mahasiswa->semester ?? '2024' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-center">
                            {{-- Form Tolak --}}
                            <form action="{{ route('pengurus.verifikasi', $pendaftar->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="ditolak">
                                <button type="submit" class="px-2.5 py-1 border border-red-200 text-red-500 rounded-lg text-[10px] font-semibold hover:bg-red-50 transition-colors">Tolak</button>
                            </form>
                            
                            {{-- Form Setujui (Value diubah dari disetujui -> aktif) --}}
                            <form action="{{ route('pengurus.verifikasi', $pendaftar->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="aktif">
                                <button type="submit" class="px-2.5 py-1 bg-emerald-500 text-white rounded-lg text-[10px] font-semibold hover:bg-emerald-600 transition-colors shadow-sm">Setujui</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-xs text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/60 text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <th class="px-4 py-2.5 w-[15%]">NIM</th>
                        <th class="px-4 py-2.5 w-[30%]">Nama</th>
                        <th class="px-4 py-2.5 w-[15%]">Jabatan</th>
                        <th class="px-4 py-2.5 w-[15%]">Angkatan</th>
                        <th class="px-4 py-2.5 w-[15%]">Status</th>
                        <th class="px-4 py-2.5 w-[10%] text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($listAnggota as $anggota)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-4 py-2 text-slate-400 font-mono text-[11px]">{{ $anggota->user->mahasiswa->nim ?? '-' }}</td>
                        <td class="px-4 py-2 font-bold text-slate-800 text-[11px]">{{ $anggota->user->mahasiswa->nama ?? 'Tanpa Nama' }}</td>
                        <td class="px-4 py-2">
                            @if(strtolower($anggota->jabatan) === 'ketua')
                                <span class="bg-amber-50 text-amber-700 text-[10px] px-2 py-0.5 rounded-md font-medium border border-amber-100">Ketua</span>
                            @else
                                <span class="text-slate-500 text-[11px]">{{ $anggota->jabatan ?? 'Anggota' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-slate-400 text-[11px]">2022</td>
                        <td class="px-4 py-2">
                            @if(strtolower($anggota->status) === 'aktif')
                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[10px] px-2 py-0.5 rounded-full font-medium border border-emerald-100">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-[10px] px-2 py-0.5 rounded-full font-medium border border-amber-100">
                                    <span class="w-1 h-1 rounded-full bg-amber-500"></span>{{ ucfirst($anggota->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-center gap-2.5">
                                <button class="text-slate-400 hover:text-indigo-600 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form action="{{ route('pengurus.anggota.arsip', $anggota->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-amber-700/70 hover:text-amber-900 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <button type="button" id="archiveToggle" class="w-full p-2.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors focus:outline-none">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-700">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div class="text-left">
                    <h3 class="text-xs font-bold text-slate-800">Anggota Diarsipkan</h3>
                    <p class="text-[10px] text-slate-400" id="archiveCounter">{{ isset($anggotaDiarsipkan) ? $anggotaDiarsipkan->count() : 0 }} item diarsipkan</p>
                </div>
            </div>
            <svg id="archiveChevron" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div id="archiveContent" class="hidden border-t border-slate-100 bg-slate-50/20 overflow-x-auto">
            @if(!isset($anggotaDiarsipkan) || $anggotaDiarsipkan->isEmpty())
                <div class="p-4 text-center text-[10px] text-slate-400">0 item diarsipkan</div>
            @else
                <table class="w-full border-collapse text-left text-xs text-slate-600">
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($anggotaDiarsipkan as $arsip)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-4 py-2 text-slate-400 font-mono text-[11px] w-[20%]">{{ $arsip->user->mahasiswa->nim ?? '-' }}</td>
                            <td class="px-4 py-2 font-bold text-slate-800 text-[11px] w-[40%]">{{ $arsip->user->mahasiswa->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-slate-500 text-[11px] w-[20%]">{{ $arsip->jabatan ?? 'Anggota' }}</td>
                            <td class="px-4 py-2 text-center w-[20%]">
                                <form action="{{ route('pengurus.anggota.restore', $arsip->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-indigo-500 hover:text-indigo-700 text-[10px] font-semibold">Pulihkan</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>

<script>
    // Pencarian realtime di tabel utama
    document.getElementById('tableSearch').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            if(!row.closest('#archiveContent')) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            }
        });
    });

    // Kontrol Buka Tutup Drawer Pengarsipan
    document.getElementById('archiveToggle').addEventListener('click', function() {
        const content = document.getElementById('archiveContent');
        const chevron = document.getElementById('archiveChevron');
        
        content.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    });
</script>
@endsection