<?php

// 1. NAMESPACE: Tetap menunjuk ke folder tempat file ini berada
namespace App\Http\Controllers\Admin;

// 2. PERBAIKAN UTAMA: Arahkan import Base Controller ke folder induknya (Controllers)
// Hapus 'Admin' dari path ini agar tidak memicu class not found
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// 3. IMPORT MODEL & LAINNYA: Tetap dipertahankan
use App\Models\DokumenKegiatan as DokumenModel;
use App\Models\Kegiatan;
use App\Models\PendaftaranPesertaKegiatan;

class DokumenKegiatan extends Controller
{
    public function index()
    {
        // Menggunakan alias DokumenModel agar tidak memanggil Controller-nya sendiri
        $documents = DokumenModel::orderBy('created_at', 'desc')->get();
        
        return view('pages.pengurus.daftar-dokumen', compact('documents'));
    }

    public function download($id)
{
    // 1. Cari data dokumen di database
    $dokumen = DokumenModel::find($id);

    if (!$dokumen) {
        return redirect()->back()->with('error', 'Data dokumen tidak ditemukan di database.');
    }

    // 2. AMBIL NAMA FILE FISIK YANG ASLI DARI PATH URL
    // Karena di database kolom 'nama_file' berisi judul teks, kita harus mengambil nama file asli dari akhir string 'path_url'
    $namaFileFisik = basename($dokumen->path_url); 

    // 3. JALUR PATH STORAGE (Disamakan dengan folder saat upload)
    // upload: 'public/dokumen_kegiatan' -> dibaca lewat Storage default sebagai berikut:
    $pathFile = 'public/dokumen_kegiatan/' . $namaFileFisik;

    // 4. Periksa apakah file fisiknya benar-benar ada
    if (!\Storage::exists($pathFile)) {
        return redirect()->back()->with('error', 'Gagal mendownload! Berkas fisik tidak ditemukan di folder storage/app/' . $pathFile);
    }

    // 5. Download dengan nama unduhan menggunakan Judul Dokumen kustom dari user + ekstensi aslinya
    $ekstensi = pathinfo($namaFileFisik, PATHINFO_EXTENSION);
    $namaUnduhan = str_replace(' ', '_', $dokumen->nama_file) . '.' . $ekstensi;

    return \Storage::download($pathFile, $namaUnduhan);
}

    public function create()
    {
        // Mengambil data kegiatan untuk pilihan dropdown di form
        $kegiatans = Kegiatan::all(); 
        
        // Mengarah ke resources/views/pages/pengurus/upload-dokumen.blade.php
        return view('pages.pengurus.upload-dokumen', compact('kegiatans'));
    }


    /**
     * Mengunggah dokumen (Proposal / LPJ Kegiatan)
     */
    public function upload(Request $request)
{
    $request->validate([
        'id_kegiatan'   => 'required', 
        'nama_dokumen'  => 'required|string|max:255',
        // Ditambahkan opsi 'dokumentasi' sesuai pilihan dropdown di UI baru
        'jenis_dokumen' => 'required|in:proposal,laporan_kegiatan,lpj_keuangan,notulen,dokumentasi', 
        // Ditambahkan mimes gambar: png, jpg, jpeg serta kapasitas naik ke 10MB (10240 KB)
        'berkas'        => 'required|file|mimes:pdf,docx,xlsx,png,jpg,jpeg|max:10240', 
    ]);

    if ($request->hasFile('berkas')) {
        $file = $request->file('berkas');
        $namaAsli = $file->getClientOriginalName();
        
        $namaFileBaru = time() . '_' . str_replace(' ', '_', $namaAsli);
        $path = $file->storeAs('public/dokumen_kegiatan', $namaFileBaru);
        $pathUrl = \Storage::url($path);

        DokumenModel::create([
            'id_kegiatan'   => $request->id_kegiatan,
            'jenis_dokumen' => $request->jenis_dokumen,
            'nama_file'     => $request->nama_dokumen, // Mengambil string nama kustom inputan pengguna
            'path_url'      => $pathUrl,
        ]);

        // Dialihkan kembali ke rute indeks dokumen utama dengan pesan sukses alert
        return redirect()->route('pengurus.dokumen')->with('success', 'Dokumen berhasil diunggah.');
    }

    return redirect()->back()->with('error', 'Berkas gagal diunggah, periksa kembali inputan Anda.');
}

    /**
     * Menghapus Dokumen dari database dan sistem penyimpanan lokal
     */
    public function destroy($id)
    {
        $dokumen = DokumenModel::findOrFail($id);
        $relativeStoragePath = str_replace('/storage/', 'public/', $dokumen->path_url);
        
        if (Storage::exists($relativeStoragePath)) {
            Storage::delete($relativeStoragePath);
        }

        $dokumen->delete();

        return redirect()->back()->with('success', 'Dokumen sukses dihapus dari sistem.');
    }
}