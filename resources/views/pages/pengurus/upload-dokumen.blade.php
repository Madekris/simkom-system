@extends('layouts.app')

@section('content')
<div class="fixed inset-0 min-h-screen w-full flex items-center justify-center p-4 z-50" style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(2px);">
    
    <div class="w-full bg-white shadow-xl" style="max-width: 480px; border-radius: 16px; overflow: hidden; font-family: 'Inter', system-ui, sans-serif;">
        
        <div class="px-6 pt-5 pb-4 flex justify-between items-start">
            <div>
                <h3 class="text-lg font-bold text-[#1A2B5C]" style="letter-spacing: -0.01em;">Upload Dokumen Kegiatan</h3>
            </div>
            <a href="{{ route('pengurus.dokumen.create') }}" class="text-[#9CA3AF] hover:text-gray-600 transition-colors pt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
        </div>

        <div class="h-[1px] w-full bg-[#E5E7EB]"></div>

        <form action="{{ route('pengurus.dokumen.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id_kegiatan" value="{{ $kegiatan->id ?? 1 }}">

            <div class="px-6 py-4 space-y-4">
                
                <div>
                    <label class="block text-xs font-semibold text-[#1A2B5C] mb-1.5">Nama Dokumen *</label>
                    <input type="text" name="nama_dokumen" placeholder="cth. Proposal_Hackathon_2026.pdf" 
                        class="w-full px-3 py-2 text-sm rounded-md border border-[#E5E7EB] bg-white text-gray-800 outline-none placeholder-gray-400 focus:border-[#1A2B5C] focus:ring-1 focus:ring-[#1A2B5C]" 
                        required>
                    @error('nama_dokumen')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1A2B5C] mb-1.5">Kategori</label>
                    <div class="relative">
                        <select name="jenis_dokumen" class="w-full px-3 py-2 text-sm rounded-md border border-[#E5E7EB] bg-white text-gray-800 outline-none appearance-none focus:border-[#1A2B5C] focus:ring-1 focus:ring-[#1A2B5C]" required>
                            <option value="proposal" selected>Proposal</option>
                            <option value="laporan_kegiatan">LPJ</option>
                            <option value="lpj_keuangan">Laporan Keuangan</option>
                            <option value="notulen">Notulen</option>
                            <option value="dokumentasi">Dokumentasi Foto</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#1A2B5C] mb-1.5">File</label>
                    
                    <div class="relative w-full flex flex-col items-center justify-center text-center transition-all border border-dashed border-gray-300 bg-gray-50 rounded-lg py-6 px-4 cursor-pointer hover:bg-gray-100" id="drop-zone">
                        
                        <input type="file" name="berkas" id="fileInput" 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                            accept=".pdf,.doc,.docx,.xlsx,.png,.jpg,.jpeg" required>
                        
                        <div class="flex flex-col items-center justify-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-2 text-gray-400">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" x2="12" y1="3" y2="15"></line>
                            </svg>

                            <p class="text-xs font-medium text-[#6B7280] mb-0.5">Klik atau seret file ke sini untuk memilih</p>
                            <p class="text-[10px] text-[#9CA3AF]">PDF / DOCX / XLSX / Gambar maks 10 MB</p>
                        </div>
                    </div>

                    <div id="file-chosen" class="text-xs text-emerald-600 mt-2 text-center font-medium bg-emerald-50 py-2 rounded-md border border-emerald-200" style="display:none;"></div>
                    
                    @error('berkas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-white flex justify-end items-center gap-3" style="border-top: 1px solid #E5E7EB;">
                <a href="{{ route('pengurus.dokumen.create') }}" 
                    class="inline-flex items-center justify-center rounded-md text-xs font-medium border border-[#E5E7EB] bg-white text-gray-700 hover:bg-gray-50 transition-colors"
                    style="height: 32px; padding: 0 16px;">
                    Batal
                </a>
                
                <button type="submit" 
                    class="inline-flex items-center justify-center gap-1.5 rounded-md text-xs font-semibold text-[#1A2B5C] transition-colors"
                    style="background-color: #F5A623; height: 32px; padding: 0 16px; border: none;">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" x2="12" y1="3" y2="15"></line>
                    </svg>
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const fileChosen = document.getElementById('file-chosen');
    const dropZone = document.getElementById('drop-zone');

    // 1. Aksi saat memilih file via jendela file browser klik normal
    fileInput.addEventListener('change', function() {
        showPreview(this.files);
    });

    // 2. Efek visual animasi penyeretan berkas (Drag & Drop)
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.add('border-amber-500', 'bg-amber-50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-amber-500', 'bg-amber-50');
        }, false);
    });

    // 3. Menangkap data transfer berkas saat file dilepas di atas box
    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files; // transfer list file ke form input Laravel
        showPreview(files);
    });

    // Fungsi pembantu menampilkan penanda nama file terpilih di UI
    function showPreview(files) {
        if (files.length > 0) {
            const file = files[0];
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            fileChosen.innerHTML = `📄 Berkas siap: <strong>${file.name}</strong> (${fileSizeMB} MB)`;
            fileChosen.style.display = "block";
        } else {
            fileChosen.style.display = "none";
        }
    }
</script>
@endsection