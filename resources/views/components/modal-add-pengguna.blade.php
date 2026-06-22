<div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4" 
     x-data="{ 
        role: 'Pembina', 
        id_organisasi: '-', 
        jabatan: '',
        mahasiswaList: [],
        jabatanTerisi: [],
        
        fetchDetailOrganisasi() {
            if (this.id_organisasi === '-') {
                this.mahasiswaList = [];
                this.jabatanTerisi = [];
                this.jabatan = '';
                return;
            }
            
            fetch(`/admin/organisasi/${this.id_organisasi}/detail`)
                .then(res => res.json())
                .then(data => {
                    this.mahasiswaList = data.mahasiswa;
                    this.jabatanTerisi = data.jabatan_terisi;
                })
                .catch(err => console.error('Gagal memuat data:', err));
        }
     }"
     x-effect="fetchDetailOrganisasi()">
    
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        
        <form action="{{ route('admin.pengguna.store') }}" method="POST" class="flex flex-col flex-1 m-0">
            @csrf

            <div class="flex items-center justify-between px-6 py-4 border-b border-[#E5E7EB] shrink-0">
                <h2 class="font-bold text-[#1C1E2C]">Tambah Pengguna Baru</h2>
                <a href="{{ route('admin.pengguna.index') }}" class="w-8 h-8 rounded-full hover:bg-[#F7F8FC] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 text-[#6B7280]">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </a>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50">Role *</label>
                    <select name="role" x-model="role" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option value="Pembina">Pembina</option>
                        <option value="Pengurus">Pengurus</option>
                    </select>
                </div>

                <div x-show="role == 'Pengurus'">
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50">Organisasi *</label>
                    <select name="id_organisasi" x-model="id_organisasi" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option value="-">Pilih organisasi</option>
                        @foreach ($organisasi as $org)
                            <option value="{{ $org->id }}">{{ $org->nama }}</option>
                        @endforeach
                    </select>
                </div>

<div x-show="role == 'Pembina'">
    <div class="mb-4">
        <label 
            data-slot="label" 
            class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
        >
            Nama Lengkap *
        </label>
        
        <input 
            type="text" 
            name="nama_lengkap"
            class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" 
            placeholder="Nama lengkap pengguna" 
            value=""
        />
    </div>

    <div class="mb-4">
        <label 
            data-slot="label" 
            class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
        >
            NIP *
        </label>
        
        <input 
            type="text" 
            name="nip"
            class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" 
            placeholder="Masukkan nomor induk pegawai" 
            value=""
        />
    </div>

    <div class="mb-4">
        <label 
            data-slot="label" 
            class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
        >
            Telepon *
        </label>
        
        <input 
            type="tel" 
            name="telepon"
            class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" 
            placeholder="Contoh: 08123456789" 
            value=""
        />
    </div>

    <div class="mb-4">
        <label 
            data-slot="label" 
            class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
        >
            Email *
        </label>
        
        <input 
            type="email" 
            name="email"
            autocomplete="off"
            class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" 
            placeholder="email@simkom-bali.ac.id" 
            value=""
        />
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label 
                data-slot="label" 
                class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
            >
                Password *
            </label>
            
            <input 
                type="password" 
                name="password"
                autocomplete="new-password"
                class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" 
                placeholder="Min. 8 karakter" 
                value=""
            />
        </div>

        <div>
            <label 
                data-slot="label" 
                class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
            >
                Konfirmasi Password *
            </label>
            
            <input 
                type="password" 
                name="password_confirmation"
                autocomplete="new-password"
                class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" 
                placeholder="Ulangi password" 
                value=""
            />
        </div>
    </div>
</div>

                
                <div x-show="role == 'Pengurus' && id_organisasi !== '-'">
                    <label class="flex items-center gap-2 text-sm leading-none font-medium select-none">Jabatan *</label>
                    
                    <template x-if="jabatanTerisi.filter(j => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'].includes(j)).length < 4">
                        <select name="jabatan" x-model="jabatan" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                            <option value="">Pilih jabatan</option>
                            <option value="Ketua" :disabled="jabatanTerisi.includes('Ketua')">Ketua <template x-if="jabatanTerisi.includes('Ketua')"><span> (Sudah Ada)</span></template></option>
                            <option value="Wakil Ketua" :disabled="jabatanTerisi.includes('Wakil Ketua')">Wakil Ketua <template x-if="jabatanTerisi.includes('Wakil Ketua')"><span> (Sudah Ada)</span></template></option>
                            <option value="Sekretaris" :disabled="jabatanTerisi.includes('Sekretaris')">Sekretaris <template x-if="jabatanTerisi.includes('Sekretaris')"><span> (Sudah Ada)</span></template></option>
                            <option value="Bendahara" :disabled="jabatanTerisi.includes('Bendahara')">Bendahara <template x-if="jabatanTerisi.includes('Bendahara')"><span> (Sudah Ada)</span></template></option>
                        </select>
                    </template>

                    <template x-if="jabatanTerisi.filter(j => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'].includes(j)).length >= 4">
                        <div class="mt-2 text-xs font-medium text-red-500 bg-red-50 border border-red-100 p-2.5 rounded-md">
                            Semua posisi jabatan inti di organisasi ini sudah terisi penuh.
                        </div>
                    </template>
                </div>

                <div x-show="role !== 'Admin' && id_organisasi !== '-' && (role !== 'Pengurus' || jabatan !== '') && !(role == 'Pengurus' && jabatanTerisi.filter(j => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'].includes(j)).length >= 4)">
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50">Mahasiswa *</label>
                    
                    <select name="id_user" 
                            :disabled="mahasiswaList.length === 0"
                            class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20 disabled:bg-gray-50 disabled:text-gray-400">
                        
                        <template x-if="mahasiswaList.length === 0">
                            <option value="">Belum ada mahasiswa yang menjadi anggota organisasi ini</option>
                        </template>

                        <template x-if="mahasiswaList.length > 0">
                            <option value="">Pilih mahasiswa</option>
                        </template>

                        <template x-for="item in mahasiswaList" :key="item.id">
                            <option :value="item.id" 
                                    x-text="item.mahasiswa ? `${item.mahasiswa.nama} (${item.mahasiswa.nim || '-'})` : 'Tanpa Nama'">
                            </option>
                        </template>
                    </select>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-[#E5E7EB] shrink-0 flex justify-end gap-3">
                <a href="{{ route('admin.pengguna.index') }}" data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] border bg-background text-foreground hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                    Batal
                </a>
                <button type="submit" data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] h-9 px-4 py-2 bg-[#F5A623] hover:bg-[#D88E15] text-[#1A2B5C]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4 mr-1">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg> 
                    Tambah Pengguna
                </button>
            </div>

        </form>
    </div>
</div>