@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Manajemen Pengguna')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Kelola akun pengguna sistem')

@section('topbar_actions')

<button data-slot="button" 
        class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive h-9 px-4 py-2 has-[>svg]:px-3 bg-[#F5A623] hover:bg-[#E0921B] text-white font-semibold">
    
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4">
        <path d="M5 12h14"></path>
        <path d="M12 5v14"></path>
    </svg>
    
    <span>Tambah Pengguna</span>
</button>
@endsection

    
@section('content')
<div class="p-4 sm:p-6 lg:p-8 space-y-4">
    <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-[#F7F8FC] text-[#6B7280]">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold">Nama / Role</th>
                    <th class="text-left px-5 py-3 font-semibold">Email</th>
                    <th class="text-left px-5 py-3 font-semibold">Status</th>
                    <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($pengguna as $p)

                    @if($p->mahasiswa)
                        @php
                            $u = $p->mahasiswa;
                        @endphp
                        <tr class="border-t border-[#E5E7EB]">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#1C1E2C]">{{ $u->nama }}</div>
                                @php
                                    // Mapping warna berdasarkan role (bg dan text)
                                    $roleColors = [
                                        'admin'     => 'bg-[#1A2B5C]/10 text-[#1A2B5C]',
                                        'pengurus'  => 'bg-[#00C9A7]/10 text-[#0F766E]',
                                        'pembina'   => 'bg-[#7C3AED]/10 text-[#6D28D9]',
                                        'bendahara' => 'bg-[#F5A623]/15 text-[#92400E]',
                                        'mahasiswa' => 'bg-[#E5E7EB] text-[#374151]' // Fallback / Tambahan jika ada mahasiswa
                                    ];

                                    // Ambil string role (jadikan lowercase agar pencocokan array aman)
                                    $userRole = strtolower($p->role);
                                    
                                    // Pilih warna berdasarkan role, jika tidak terdaftar pakai warna mahasiswa (default)
                                    $selectedColor = $roleColors[$userRole] ?? 'bg-[#E5E7EB] text-[#374151]';
                                @endphp

                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold mt-1 transition-colors {{ $selectedColor }}">
                                    {{ ucfirst($p->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-[#6B7280]">{{ $p->email }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $status = $p->anggotaOrganisasi->first()->status ?? 'nonaktif';
                                @endphp

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                    {{ $status == 'aktif' ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.pengguna.index', ['id' => $p->id]) }}" data-slot="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-[#1A2B5C] transition-all outline-none hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    <a href="{{ route('admin.pengguna.index', ['edit' => $p->id]) }}" data-slot="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-[#1A2B5C] transition-all outline-none hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pen h-4 w-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path></svg>
                                    </a>
                                    {{-- <button data-slot="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-[#92400E] transition-all outline-none hover:bg-amber-50 disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0" title="Arsip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive h-4 w-4"><rect width="20" height="5" x="2" y="3" rx="1"></rect><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"></path><path d="M10 12h4"></path></svg>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                    @else
                        @php
                            $u = $p->pembina;
                        @endphp
                        <tr class="border-t border-[#E5E7EB]">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#1C1E2C]">{{ $u->nama }}</div>
                                @php
                                    // Mapping warna berdasarkan role (bg dan text)
                                    $roleColors = [
                                        'admin'     => 'bg-[#1A2B5C]/10 text-[#1A2B5C]',
                                        'pengurus'  => 'bg-[#00C9A7]/10 text-[#0F766E]',
                                        'pembina'   => 'bg-[#7C3AED]/10 text-[#6D28D9]',
                                        'bendahara' => 'bg-[#F5A623]/15 text-[#92400E]',
                                        'mahasiswa' => 'bg-[#E5E7EB] text-[#374151]' // Fallback / Tambahan jika ada mahasiswa
                                    ];

                                    // Ambil string role (jadikan lowercase agar pencocokan array aman)
                                    $userRole = strtolower($p->role);
                                    
                                    // Pilih warna berdasarkan role, jika tidak terdaftar pakai warna mahasiswa (default)
                                    $selectedColor = $roleColors[$userRole] ?? 'bg-[#E5E7EB] text-[#374151]';
                                @endphp

                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold mt-1 transition-colors {{ $selectedColor }}">
                                    {{ ucfirst($p->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-[#6B7280]">{{ $p->email }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $status = $p->anggotaOrganisasi->first()->status ?? 'nonaktif';
                                @endphp

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                                    {{ $status == 'aktif' ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.pengguna.index', ['id' => $p->id]) }}"  data-slot="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-[#1A2B5C] transition-all outline-none hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0" title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye h-4 w-4"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    </a>
                                    <a href="{{ route('admin.pengguna.index', ['edit' => $p->id]) }}" data-slot="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-[#1A2B5C] transition-all outline-none hover:bg-gray-100 disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pen h-4 w-4"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"></path></svg>
                                    </a>
                                    {{-- <button data-slot="button" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-[#92400E] transition-all outline-none hover:bg-amber-50 disabled:pointer-events-none disabled:opacity-50 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0" title="Arsip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive h-4 w-4"><rect width="20" height="5" x="2" y="3" rx="1"></rect><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"></path><path d="M10 12h4"></path></svg>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                    @endif

                @empty
                    
                @endforelse
            </tbody>
        </table>
    </div>

    
    
</div>
@if (request('id'))
    <x-modal-detail-pengguna :data="$dPengguna">
    </x-modal-detail-pengguna>
@endif

@if (request('edit'))
    <x-modal-edit-pengguna>
    </x-modal-edit-pengguna>
@endif
@endsection