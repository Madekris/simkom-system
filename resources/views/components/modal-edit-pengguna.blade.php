@props([
    'data',
    'organisasi',
    'ps'
])

@php
    // Ambil data nama dari relasi yang tersedia
    $namaLengkap = $data->mahasiswa->nama ?? $data->pembina->nama ?? '';
    
    // Ambil status dari anggota organisasi indeks pertama (jika ada)
    $statusOrganisasi = $data->anggotaOrganisasi[0]['status'] ?? $data->anggota_organisasi[0]['status'] ?? 'tidak aktif';
    
    // Ambil prodi & semester jika user adalah mahasiswa
    $prodiUser = $data->mahasiswa->programStudi->nama ?? $data->mahasiswa['program_studi']['nama'] ?? '';
    $semesterUser = $data->mahasiswa->semester ?? $data->mahasiswa['semester'] ?? '';
@endphp

<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <form method="POST" action="{{ route('admin.pengguna.update', $data->id) }}" class="w-full max-w-md bg-white rounded-2xl shadow-2xl">
        @csrf
        @method('PUT')
        
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#E5E7EB]">
            <h2 class="font-bold text-[#1C1E2C]">Edit Pengguna</h2>
            <a href="{{ route('admin.pengguna.index') }}" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#F7F8FC] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4 text-[#6B7280]">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </a>
        </div>

        <div class="px-6 py-5 space-y-4">
            <div>
                <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Nama Lengkap *</label>
                <input type="text" name="nama" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" placeholder="Nama lengkap" value="{{ $namaLengkap }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Role</label>
                    <select name="role" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option value="admin" {{ strtolower($data->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pembina" {{ strtolower($data->role) == 'pembina' ? 'selected' : '' }}>Pembina</option>
                        <option value="pengurus" {{ strtolower($data->role) == 'pengurus' ? 'selected' : '' }}>Pengurus</option>
                        <option value="bendahara" {{ strtolower($data->role) == 'bendahara' ? 'selected' : '' }}>Bendahara</option>
                        <option value="mahasiswa" {{ strtolower($data->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                    </select>
                </div>
                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Status</label>
                    <select name="status" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option value="aktif" {{ strtolower($statusOrganisasi) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak aktif" {{ strtolower($statusOrganisasi) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Organisasi</label>
                <select name="id_organisasi" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                    @php
                        $currentOrgId = $data->anggotaOrganisasi[0]['id_organisasi'] ?? $data->anggota_organisasi[0]['id_organisasi'] ?? null;
                    @endphp

                    <option value="" {{ is_null($currentOrgId) ? 'selected' : '' }}>-- Pilih Organisasi --</option>
                    
                    @foreach ($organisasi as $org)
                        <option value="{{ $org->id }}" {{ $currentOrgId == $org->id ? 'selected' : '' }}>
                            {{ $org->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Email *</label>
                <input type="email" name="email" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" placeholder="Email" value="{{ $data->email }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                @if ($data->mahasiswa)
                    <div>
                        <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Program Studi</label>
                        <select name="id_program_studi" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                            @php
                                $currentProdiId = $data->mahasiswa->id_program_studi ?? $data->mahasiswa['id_program_studi'] ?? null;
                            @endphp

                            <option value="" {{ is_null($currentProdiId) ? 'selected' : '' }}>-</option>
                            
                            @foreach ($ps as $p)
                                <option value="{{ $p->id }}" {{ $currentProdiId == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Semester</label>
                        <select name="semester" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                            <option value="" {{ $semesterUser == '' ? 'selected' : '' }}>-</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ $semesterUser == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#E5E7EB] flex justify-end gap-3">
            <a href="{{ route('admin.pengguna.index') }}" data-slot="button" class="inline-flex h-9 shrink-0 items-center justify-center rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all outline-none hover:bg-gray-50 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 has-[>svg]:px-3 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0">
                Batal
            </a>
            <button type="submit" data-slot="button" class="inline-flex h-9 shrink-0 items-center justify-center rounded-md bg-[#1A2B5C] px-4 py-2 text-sm font-medium text-white transition-all outline-none hover:bg-[#1A2B5C]/90 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 has-[>svg]:px-3 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>