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
    
    $roleSelected = strtolower($data->role ?? '');
    // Ambil prodi & semester jika user adalah mahasiswa
    $prodiUser = $data->mahasiswa->programStudi->nama ?? $data->mahasiswa['program_studi']['nama'] ?? '';
    $semesterUser = $data->mahasiswa->semester ?? $data->mahasiswa['semester'] ?? '';
@endphp

<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-data="{ role: '{{ $roleSelected }}' }">
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
                @error('nama')
                    <span class="text-xs text-red-500 mt-1 block font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Role</label>
                    
                    <select name="role" x-model="role" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        @if ($data->mahasiswa)
                            <option value="pengurus">Pengurus</option>
                            <option value="bendahara">Bendahara</option>
                            <option value="mahasiswa">Mahasiswa</option>
                        @else
                            <option value="admin">Admin</option>
                            <option value="pembina">Pembina</option>
                        @endif
                    </select>

                    @error('role')
                        <span class="text-xs text-red-500 mt-1 block font-medium">
                            {{ $message }}
                        </span>
                @enderror
                </div>

                <div>

                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Status</label>
                    <select  name="status" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option value="aktif" {{ strtolower($statusOrganisasi) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ strtolower($statusOrganisasi) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>

                    @error('status')
                        <span class="text-xs text-red-500 mt-1 block font-medium">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            @if ($data->mahasiswa)
            <div>
                @php
                   $currentOrg = $data->anggotaOrganisasi[0] ?? null;
               @endphp

               <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Organisasi</label>
               <input type="hidden" name="id_organisasi" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" placeholder="Nama lengkap" value="{{ $currentOrg->id_organisasi }}">
               <input 
                   type="text" 
                   name="organisasi" 
                   readonly
                   class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-gray-50 text-gray-500 text-sm outline-none cursor-not-allowed" 
                   placeholder="Nama Organisasi" 
                   value="{{ $currentOrg->organisasi->nama }}"
               >
           </div>
           @else
                @php
                    // Ambil semua ID organisasi yang saat ini sudah diikuti oleh user
                    $selectedOrgIds = $data->anggotaOrganisasi->pluck('id_organisasi')->toArray() ?? [];
                    
                    // Siapkan data organisasi untuk dimasukkan ke JavaScript Alpine.js
                    $orgList = $organisasi->map(function($org) {
                        return ['id' => $org->id, 'nama' => $org->nama];
                    })->toArray();
                @endphp

                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none mb-1.5">
                        Pilih Organisasi
                    </label>

                    <div x-data="{ 
                        allOrgs: {{ json_encode($orgList) }},
                        selectedIds: {{ json_encode($selectedOrgIds) }}.map(Number),
                        
                        // Fungsi untuk menambah organisasi ke daftar pilihan
                        addOrg(id) {
                            if (id && !this.selectedIds.includes(Number(id))) {
                                this.selectedIds.push(Number(id));
                            }
                        },
                        // Fungsi untuk menghapus organisasi dari daftar pilihan
                        removeOrg(id) {
                            this.selectedIds = this.selectedIds.filter(i => i !== id);
                        }
                    }">
                        
                        <select 
                            x-on:change="addOrg($event.target.value); $event.target.value = ''"
                            class="w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20"
                        >
                            <option value="">Pilih Organisasi untuk Ditambahkan</option>
                            @foreach($organisasi as $org)
                                <option value="{{ $org->id }}" x-show="!selectedIds.includes({{ $org->id }})">
                                    {{ $org->nama }}
                                </option>
                            @endforeach
                        </select>

                        <div class="mt-4">
                            <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none mb-2">
                                Organisasi yang dibina:
                            </label>
                            
                            <div x-show="selectedIds.length === 0" class="text-sm text-gray-400 italic px-3 py-2 bg-gray-50 rounded-md border border-dashed border-gray-200">
                                Belum ada organisasi yang dipilih.
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <template x-for="id in selectedIds" :key="id">
                                    <div class="flex items-center gap-1.5 bg-[#1A2B5C]/5 text-[#1A2B5C] text-sm font-medium py-2 px-3 rounded-full border border-[#1A2B5C]/20 animate-fade-in">
                                        
                                        <span x-text="allOrgs.find(o => o.id === id)?.nama"></span>
                                        
                                        <button 
                                            type="button" 
                                            x-on:click="removeOrg(id)" 
                                            class="w-4 h-4 rounded-full flex items-center justify-center hover:bg-[#1A2B5C]/20 text-[#1A2B5C] font-bold text-xs transition-colors"
                                            title="Hapus"
                                        >
                                            &times;
                                        </button>

                                        <input type="hidden" name="id_organisasi[]" :value="id">
                                        
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
            @endif


            {{-- <div>
                
                <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Organisasi</label>
                <select name="id_organisasi" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                   

                    <option value="" {{ is_null($currentOrgId) ? 'selected' : '' }}>-- Pilih Organisasi --</option>
                    
                    @foreach ($organisasi as $org)
                        <option value="{{ $org->id }}" {{ $currentOrgId == $org->id ? 'selected' : '' }}>
                            {{ $org->nama }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

       
            <div x-show="role == 'pengurus'">
                <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Jabatan</label>
                <select name="jabatan" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                    <option value="Ketua" {{ $data->anggotaOrganisasi[0]['jabatan'] == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                    <option value="Wakil Ketua" {{ $data->anggotaOrganisasi[0]['jabatan'] == 'Wakil Ketua' ? 'selected' : '' }}>Wakil Ketua</option>
                    <option value="Sekretaris" {{ $data->anggotaOrganisasi[0]['jabatan'] == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                </select>
                @error('jabatan')
                    <span class="text-xs text-red-500 mt-1 block font-medium">
                        {{ $message }}
                    </span>
                @enderror
            </div>

                
         


            <div>
                <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Email *</label>
                <input type="email" name="email" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" placeholder="Email" value="{{ $data->email }}">
                @error('email')
                    <span class="text-xs text-red-500 mt-1 block font-medium">
                        {{ $message }}
                    </span>
                @enderror
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
                        @error('id_program_studi')
                            <span class="text-xs text-red-500 mt-1 block font-medium">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div>
                        <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Semester</label>
                        <select name="semester" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                            <option value="" {{ $semesterUser == '' ? 'selected' : '' }}>-</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ $semesterUser == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <span class="text-xs text-red-500 mt-1 block font-medium">
                                {{ $message }}
                            </span>
                        @enderror
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