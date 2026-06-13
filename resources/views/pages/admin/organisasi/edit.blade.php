<div id="modal-edit-ormawa" class="fixed inset-0 z-50 hidden items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4 overflow-y-auto animate-fade-in">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-gray-100 transform transition-all my-8 max-h-[90vh] flex flex-col">
        
        <div class="flex justify-between items-center pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Ubah Data Organisasi</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="form-edit-ormawa" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4 overflow-y-auto pr-1 flex-1 text-left">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nama Ormawa *</label>
                <input type="text" name="nama" id="edit-nama" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Jenis Organisasi *</label>
                    <select name="id_jenis_organisasi" id="edit-id-jenis-organisasi" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($jenis_organisasis as $jo)
                            <option value="{{ $jo->id }}">{{ $jo->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status *</label>
                    <select name="status" id="edit-status" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="diarsipkan">Diarsipkan</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ketua Umum *</label>
                    <select name="mahasiswa_id" id="edit-mahasiswa-id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition bg-white">
                        <option value="">-- Pilih Anggota Ormawa --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pembina</label>
                    <input type="text" id="edit-pembina" value="Prof. Rina Wati" disabled class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-sm text-gray-400 cursor-not-allowed outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="edit-deskripsi" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition placeholder-gray-400" placeholder="Deskripsi mengenai visi taktis ormawa..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Visi</label>
                <textarea name="visi" id="edit-visi" rows="2" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition placeholder-gray-400" placeholder="Menjadi organisasi yang..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Misi</label>
                <textarea name="misi" id="edit-misi" rows="3" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition placeholder-gray-400" placeholder="1. Mengembangkan kompetensi..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Dokumen AD/ART (PDF, Docx - Max 5MB)</label>
                <input type="file" name="ad_art" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                <p class="text-[11px] text-gray-400 mt-1">*Biarkan kosong jika tidak ingin mengubah dokumen legalitas lama</p>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition focus:outline-none">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-[#1e293b] hover:bg-slate-800 text-white rounded-xl text-sm font-semibold shadow-sm transition focus:outline-none">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Utama: Membuka modal dan memetakan data lama ke dalam form input
    function openEditModal(organisasi) {
        const modal = document.getElementById('modal-edit-ormawa');
        const form = document.getElementById('form-edit-ormawa');
        const selectKetua = document.getElementById('edit-mahasiswa-id');
        
        if (modal && form) {
            // 1. Set URL target form action secara dinamis sesuai ID organisasi
            form.action = `/admin/organisasi/${organisasi.id}`;

            // 2. Isi nilai tiap input elemen berdasarkan object organisasi
            document.getElementById('edit-nama').value = organisasi.nama || '';
            document.getElementById('edit-id-jenis-organisasi').value = organisasi.id_jenis_organisasi || '';
            document.getElementById('edit-status').value = organisasi.status || 'aktif';
            document.getElementById('edit-deskripsi').value = organisasi.deskripsi || '';
            document.getElementById('edit-visi').value = organisasi.visi || '';
            document.getElementById('edit-misi').value = organisasi.misi || '';

            // 3. Tampilkan status memuat data sementara pada dropdown Ketua Umum
            selectKetua.innerHTML = '<option value="">-- Memuat Anggota... --</option>';

            // 4. AJAX Fetch: Diarahkan ke URL /anggota sesuai dengan route web.php
            fetch(`/admin/organisasi/${organisasi.id}/anggota`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Gagal merespon dari server');
                    }
                    return response.json();
                })
                .then(res => {
                    selectKetua.innerHTML = '<option value="">-- Pilih Anggota Ormawa --</option>';
                    
                    // VALIDASI EXTRA: Mendeteksi apakah response berbentuk Array langsung atau bersarang di .data
                    const daftarAnggota = Array.isArray(res) ? res : (res.data || []);
                    
                    if (daftarAnggota.length === 0) {
                        selectKetua.innerHTML = '<option value="">(Tidak ada anggota terdaftar di ormawa ini)</option>';
                    } else {
                        // Looping data mahasiswa hasil filter dari backend
                        daftarAnggota.forEach(anggota => {
                            const option = document.createElement('option');
                            option.value = anggota.id;
                            
                            // Menampilkan nama beserta NIM agar informatif saat dipilih
                            const nimString = anggota.nim ? ` (${anggota.nim})` : '';
                            option.text = (anggota.nama || 'Tanpa Nama') + nimString;
                            
                            // Ambil ID Ketua saat ini dari object relasi
                            let targetId = null;
                            if (organisasi.ketua) {
                                // Mendukung jika data ketua dilempar langsung atau lewat properti id_mahasiswa
                                targetId = organisasi.ketua.id_mahasiswa || organisasi.ketua.id;
                            } else if (organisasi.id_mahasiswa) {
                                targetId = organisasi.id_mahasiswa;
                            }

                            // Otomatis tandai 'selected' jika id cocok dengan ketua saat ini
                            if (targetId && anggota.id == targetId) {
                                option.selected = true;
                            }
                            selectKetua.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Gagal memuat data anggota:', error);
                    selectKetua.innerHTML = '<option value="">Gagal memuat daftar anggota</option>';
                });

            // 5. Munculkan modal pop-up
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Kunci scroll halaman utama
        }
    }

    // Fungsi: Menutup modal pop-up
    function closeEditModal() {
        const modal = document.getElementById('modal-edit-ormawa');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto'; // Aktifkan kembali scroll halaman utama
        }
    }

    // Penutup Otomatis: Jika pengguna mengeklik area buram di luar kotak modal
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('modal-edit-ormawa');
        if (event.target === modal) {
            closeEditModal();
        }
    });
</script>