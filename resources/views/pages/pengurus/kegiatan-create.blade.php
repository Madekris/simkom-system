@extends('layouts.app')

{{-- Isi Judul Topbar --}}
@section('topbar_title', 'Buat Kegiatan Baru')

{{-- Isi Subtitle Topbar (Opsional) --}}
@section('topbar_subtitle', 'Kelola seluruh kegiatan ormawa Anda')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto">
    
    <a href="{{ route('pengurus.kegiatan.index') }}" class="flex items-center gap-1.5 text-sm font-medium text-[#6B7280] hover:text-[#1A2B5C] mb-4 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left w-4 h-4">
            <path d="m12 19-7-7 7-7"></path>
            <path d="M19 12H5"></path>
        </svg> 
        Kembali
    </a>

    @php
        $data = $anggotaOrganisasi->first();
    @endphp

    <form action="{{ route('pengurus.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] p-6 space-y-5">
            
            <input type="hidden" name="id_organisasi" value="{{ $data->id_organisasi }}">
            <input type="hidden" name="id_periode" value="{{ $data->id_periode }}">

            <div>
                <label class="block text-sm font-semibold text-[#1C1E2C]">Judul Kegiatan <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    name="judul_kegiatan"
                    value="{{ old('judul_kegiatan') }}"
                    placeholder="Contoh: Workshop AI 2026" 
                    class="mt-1.5 flex h-9 w-full rounded-md border {{ $errors->has('judul_kegiatan') ? 'border-red-500 focus:border-red-500' : 'border-[#E5E7EB] focus:border-[#1A2B5C]' }} bg-[#F7F8FC] px-3 py-1 text-sm text-[#1C1E2C] placeholder-[#6B7280] outline-none transition-all focus:bg-white"
                >
                @error('judul_kegiatan')
                    <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#1C1E2C]">Deskripsi</label>
                <textarea 
                    name="deskripsi"
                    rows="4" 
                    placeholder="Jelaskan tujuan dan rangkaian kegiatan..." 
                    class="mt-1.5 flex w-full rounded-md border {{ $errors->has('deskripsi') ? 'border-red-500 focus:border-red-500' : 'border-[#E5E7EB] focus:border-[#1A2B5C]' }} bg-[#F7F8FC] px-3 py-2 text-sm text-[#1C1E2C] placeholder-[#6B7280] outline-none transition-all focus:bg-white resize-none"
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-[#1C1E2C]">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                    <input 
                        type="date" 
                        name="tanggal_kegiatan"
                        value="{{ old('tanggal_kegiatan') }}"
                        class="mt-1.5 block h-9 w-full rounded-md border {{ $errors->has('tanggal_kegiatan') ? 'border-red-500 focus:border-red-500' : 'border-[#E5E7EB] focus:border-[#1A2B5C]' }} bg-[#F7F8FC] px-3 py-1 text-sm text-[#1C1E2C] outline-none transition-all focus:bg-white"
                    >
                    @error('tanggal_kegiatan')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#1C1E2C]">Waktu Pelaksanaan <span class="text-red-500">*</span></label>
                    <input 
                        type="time" 
                        name="waktu_kegiatan"
                        value="{{ old('waktu_kegiatan') }}"
                        class="mt-1.5 block h-9 w-full rounded-md border {{ $errors->has('waktu_kegiatan') ? 'border-red-500 focus:border-red-500' : 'border-[#E5E7EB] focus:border-[#1A2B5C]' }} bg-[#F7F8FC] px-3 py-1 text-sm text-[#1C1E2C] outline-none transition-all focus:bg-white"
                    >
                    @error('waktu_kegiatan')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-[#1C1E2C]">Lokasi <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        name="lokasi"
                        value="{{ old('lokasi') }}"
                        placeholder="Aula SIMKOM Bali" 
                        class="mt-1.5 flex h-9 w-full rounded-md border {{ $errors->has('lokasi') ? 'border-red-500 focus:border-red-500' : 'border-[#E5E7EB] focus:border-[#1A2B5C]' }} bg-[#F7F8FC] px-3 py-1 text-sm text-[#1C1E2C] placeholder-[#6B7280] outline-none transition-all focus:bg-white"
                    >
                    @error('lokasi')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#1C1E2C]">Estimasi Peserta (Kuota) <span class="text-red-500">*</span></label>
                    <input 
                        type="number" 
                        name="kuota_peserta"
                        value="{{ old('kuota_peserta') }}"
                        placeholder="50" 
                        class="mt-1.5 flex h-9 w-full rounded-md border {{ $errors->has('kuota_peserta') ? 'border-red-500 focus:border-red-500' : 'border-[#E5E7EB] focus:border-[#1A2B5C]' }} bg-[#F7F8FC] px-3 py-1 text-sm text-[#1C1E2C] placeholder-[#6B7280] outline-none transition-all focus:bg-white"
                    >
                    @error('kuota_peserta')
                        <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#1C1E2C]">Upload Proposal (PDF)</label>
                
                <div class="mt-1.5 relative">
                    <label id="dropzone-label" class="block border-2 border-dashed {{ $errors->has('proposal') ? 'border-red-500 bg-red-50/30' : 'border-[#E5E7EB] hover:border-[#1A2B5C] hover:bg-[#F7F8FC]/50' }} rounded-lg p-8 text-center transition cursor-pointer group">
                        <input type="file" id="proposal-input" name="proposal" accept="application/pdf" class="hidden" onchange="previewPDF(this)">
                        
                        <div id="upload-placeholder" class="block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload w-8 h-8 mx-auto {{ $errors->has('proposal') ? 'text-red-400' : 'text-[#6B7280] group-hover:text-[#1A2B5C]' }} transition-colors">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" x2="12" y1="3" y2="15"></line>
                            </svg>
                            <div class="text-sm font-medium {{ $errors->has('proposal') ? 'text-red-500' : 'text-[#6B7280] group-hover:text-[#1A2B5C]' }} mt-2 transition-colors">Klik untuk pilih file proposal</div>
                            <div class="text-xs {{ $errors->has('proposal') ? 'text-red-400' : 'text-[#9CA3AF]' }} mt-1">PDF maks 10 MB</div>
                        </div>

                        <div id="pdf-preview" class="hidden items-center justify-between bg-[#F7F8FC] p-3 rounded-lg border border-[#E5E7EB] max-w-xl mx-auto">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="p-2 bg-red-100 text-red-600 rounded-md shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text w-6 h-6">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path>
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4"></path>
                                        <path d="M10 9H8"></path>
                                        <path d="M16 13H8"></path>
                                        <path d="M16 17H8"></path>
                                    </svg>
                                </div>
                                <div class="text-left min-w-0">
                                    <p id="pdf-name" class="text-sm font-semibold text-[#1C1E2C] truncate">nama-file.pdf</p>
                                    <p id="pdf-size" class="text-xs text-[#6B7280]">0 KB</p>
                                </div>
                            </div>
                            <button type="button" onclick="resetPDF(event)" class="p-1.5 hover:bg-gray-200 text-[#6B7280] hover:text-red-500 rounded-md transition-colors shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    <line x1="10" x2="10" y1="11" y2="17"></line>
                                    <line x1="14" x2="14" y1="11" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </label>
                </div>

                @error('proposal')
                    <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                <a href="{{ route('pengurus.kegiatan.index') }}" class="inline-flex items-center justify-center text-sm font-semibold h-9 px-4 rounded-md border border-[#E5E7EB] text-[#6B7280] hover:bg-[#F7F8FC] transition-colors">
                    Batal
                </a>
            
                <button type="submit" name="action" value="submit" class="inline-flex items-center justify-center text-sm font-semibold h-9 px-4 rounded-md bg-[#1A2B5C] text-white hover:bg-[#0F1B3D] transition-colors">
                    Submit untuk Persetujuan
                </button>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
function previewPDF(input) {
    const file = input.files[0];
    const placeholder = document.getElementById('upload-placeholder');
    const preview = document.getElementById('pdf-preview');
    const pdfName = document.getElementById('pdf-name');
    const pdfSize = document.getElementById('pdf-size');
    const dropzoneLabel = document.getElementById('dropzone-label');

    if (file && file.type === "application/pdf") {
        // Ambil nama file dan hitung ukuran file (KB/MB)
        pdfName.textContent = file.name;
        
        const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
        if (sizeInMB < 0.1) {
            pdfSize.textContent = (file.size / 1024).toFixed(1) + ' KB';
        } else {
            pdfSize.textContent = sizeInMB + ' MB';
        }

        // Tukar visibilitas tampilan placeholder dengan preview
        placeholder.classList.add('hidden');
        preview.classList.remove('hidden');
        preview.classList.add('flex');
        
        // Nonaktifkan pointer-events pada label luar agar klik tombol hapus tidak trigger file picker lagi
        dropzoneLabel.style.pointerEvents = 'none';
        // Aktifkan kembali pointer-events hanya pada box preview agar tombol hapus bisa diklik
        preview.style.pointerEvents = 'auto';
    }
}

function resetPDF(event) {
    // Mencegah efek bubbling klik agar tidak membuka file explorer lagi
    event.preventDefault();
    event.stopPropagation();

    const input = document.getElementById('proposal-input');
    const placeholder = document.getElementById('upload-placeholder');
    const preview = document.getElementById('pdf-preview');
    const dropzoneLabel = document.getElementById('dropzone-label');

    // Reset nilai input file menjadi kosong
    input.value = '';

    // Kembalikan visibilitas tampilan asal
    preview.classList.add('hidden');
    preview.classList.remove('flex');
    placeholder.classList.remove('hidden');
    
    // Kembalikan sifat pointer events label asal
    dropzoneLabel.style.pointerEvents = 'auto';
}
</script>
@endpush
@endsection