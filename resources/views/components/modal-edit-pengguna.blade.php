<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl">
        
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
                <input type="text" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" placeholder="Nama lengkap" value="Andi Pratama">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Role</label>
                    <select class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option>Admin</option>
                        <option>Pembina</option>
                        <option selected>Pengurus</option>
                        <option>Bendahara</option>
                        <option>Mahasiswa</option>
                    </select>
                </div>
                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Status</label>
                    <select class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option selected>Aktif</option>
                        <option>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div>
                <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Email *</label>
                <input type="email" class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20" placeholder="email@simkom-bali.ac.id" value="andi@simkom-bali.ac.id">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Program Studi</label>
                    <select class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option>Teknik Informatika</option>
                        <option selected>Sistem Informasi</option>
                        <option>Manajemen Informatika</option>
                    </select>
                </div>
                <div>
                    <label data-slot="label" class="flex items-center gap-2 text-sm leading-none font-medium text-[#1C1E2C] select-none">Semester</label>
                    <select class="mt-1.5 w-full px-3 py-2 rounded-md border border-[#E5E7EB] bg-white text-sm outline-none transition-all focus:border-[#1A2B5C] focus:ring-2 focus:ring-[#1A2B5C]/20">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option selected>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-[#E5E7EB] flex justify-end gap-3">
            <a href="{{ route('admin.pengguna.index') }}" data-slot="button" class="inline-flex h-9 shrink-0 items-center justify-center rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all outline-none hover:bg-gray-50 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 has-[>svg]:px-3 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0">
                Batal
            </a>
            <button data-slot="button" class="inline-flex h-9 shrink-0 items-center justify-center rounded-md bg-[#1A2B5C] px-4 py-2 text-sm font-medium text-white transition-all outline-none hover:bg-[#1A2B5C]/90 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 has-[>svg]:px-3 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0">
                Simpan Perubahan
            </button>
        </div>

    </div>
</div>