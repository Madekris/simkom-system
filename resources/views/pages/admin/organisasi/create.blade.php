<div id="modal-create-ormawa" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100 transform transition-all my-8 max-h-[90vh] flex flex-col relative text-left">
        
        <button type="button" onclick="closeCreateModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <h3 class="text-xl font-bold text-gray-900 mb-6 text-left">Tambah Ormawa Baru</h3>

        <form action="{{ route('admin.organisasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 overflow-y-auto pr-1 flex-1">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Logo Ormawa</label>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center bg-gray-50 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="logo" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Format: PNG, JPG, SVG • Maks. 2 MB</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Ormawa *</label>
                <input type="text" name="nama" required placeholder="cth. HIMA Teknik Informatika" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition placeholder-gray-300 text-gray-900">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis *</label>
                    <select name="id_jenis_organisasi" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition bg-white text-gray-700">
                        <option value="">Pilih Jenis</option>
                        @foreach($jenis_organisasis as $jo)
                            <option value="{{ $jo->id }}">{{ $jo->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ketua Umum *</label>
                    <select name="mahasiswa_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition bg-white text-gray-700">
                        <option value="">Nama ketua</option>
                        @foreach($mahasiswas as $mhs)
                            <option value="{{ $mhs->id_user }}">{{ $mhs->nama }} ({{ $mhs->nim }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pembina *</label>
                <input type="text" name="pembina" placeholder="Nama dosen pembina" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition placeholder-gray-300 text-gray-900">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat tentang ormawa..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition placeholder-gray-300 text-gray-900"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                <textarea name="visi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F5A623]/20 focus:border-[#F5A623] transition-colors" placeholder="Tuliskan visi organisasi..."></textarea>
            </div>

            {{-- Input Misi --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                <textarea name="misi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#F5A623]/20 focus:border-[#F5A623] transition-colors" placeholder="Tuliskan misi organisasi..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Dokumen AD/ART *</label>
                <input type="file" name="ad_art" required class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition">
                <p class="text-[11px] text-gray-400 mt-1">Format: PDF, DOC, DOCX • Maks. 5 MB</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition focus:outline-none">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-medium shadow-sm transition focus:outline-none">
                    + Tambah Ormawa
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        const modal = document.getElementById('modal-create-ormawa');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeCreateModal() {
        const modal = document.getElementById('modal-create-ormawa');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
    }

    // Menutup modal jika area luar kotak diklik
    document.getElementById('modal-create-ormawa').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
</script>