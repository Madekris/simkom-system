<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Registrasi;
use App\Http\Controllers\Mahasiswa\Dashboard as MahasiswaDashboard;
use App\Http\Controllers\Mahasiswa\KegiatanSaya as MahasiswaKegiatanSaya;
use App\Http\Controllers\Mahasiswa\PendaftaranPeserta; 
use App\Http\Controllers\Pengurus\VerifikasiController;
use App\Http\Controllers\Pengurus\OrganisasiController; 
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KeuanganOrmawa as AdminKeuanganOrmawa;
use App\Http\Controllers\Bendahara\InputKeuangan as BendaharaInputKeuangan;
use App\Http\Controllers\Bendahara\Dashboard as BendaharaDashboard;
use App\Http\Controllers\Bendahara\InfoOrmawa;
use App\Http\Controllers\Bendahara\LogAktivitas as BendaharaLogAktivitas;
use App\Http\Controllers\Pembina\KeuanganBinaan as PembinaKeuanganBinaan;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Pembina\RiwayatKegiatan;
use App\Http\Controllers\Pengurus\Dashboard as PengurusDashboard;
use App\Http\Controllers\Pengurus\Kegiatan as PengurusKegiatan;
use App\Http\Controllers\Pengurus\Keuangan as PengurusKeuangan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DokumenKegiatan;
use App\Http\Controllers\Pengurus\DokumenController;    


// ==========================================
// LANDING & AUTHENTICATION (PUBLIC)
// ==========================================
Route::get('/', function () { return view('welcome'); });

Route::get('/login', [Login::class, 'create'])->name('login');
Route::post('/login', [Login::class, 'store']);
Route::post('/logout', [Login::class, 'destroy'])->name('logout');
Route::get('/register', [Registrasi::class, 'create'])->name('register');
Route::post('/register', [Registrasi::class, 'store']);


// ==========================================
// AREA USER TER-AUTENTIKASI (MIDDLEWARE: AUTH)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Route untuk mendownload berkas file fisik dokumen
    Route::get('/dokumen/download/{id}', [DokumenKegiatan::class, 'download'])->name('dokumen.download');
    Route::delete('/dokumen/delete/{id}', [DokumenKegiatan::class, 'destroy'])->name('DokumenKegiatan.destroy');

    // 1. AREA MAHASISWA (Hanya bisa diakses oleh role: mahasiswa)
    Route::prefix('mahasiswa')
     ->name('mahasiswa.')
     ->middleware(['role:mahasiswa']) 
     ->group(function () {
         Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
         Route::get('/organisasi', [MahasiswaDashboard::class, 'organisasi'])->name('organisasi.index');
         
         // KUNCI FIX: Rute DAFTAR HARUS DI ATAS rute DETAIL!
         Route::get('/organisasi/{id}/daftar', [MahasiswaDashboard::class, 'pendaftaran'])->name('organisasi.daftar');
         Route::post('/organisasi/daftar', [MahasiswaDashboard::class, 'store'])->name('organisasi.store');
         
         // Rute DETAIL
         Route::get('/organisasi/{id}', [MahasiswaDashboard::class, 'show'])->name('organisasi.show'); 

         // RUTE BARU: KEGIATAN SAYA 
         Route::get('/kegiatan-saya', [MahasiswaKegiatanSaya::class, 'index'])->name('kegiatan-saya');
         Route::get('/kegiatan-saya/{id}', [MahasiswaKegiatanSaya::class, 'show'])->name('kegiatan-saya.show');

         Route::post('/kegiatan/daftar', [PendaftaranPeserta::class, 'daftar'])->name('kegiatan.daftar');
     });

    // 2. AREA PENGURUS (Hanya bisa diakses oleh role: pengurus)
    Route::prefix('pengurus')
         ->name('pengurus.')
         ->middleware(['role:pengurus']) 
         ->group(function () {
            Route::get('/keuangan/export/{id}', [PengurusKeuangan::class, 'export'])->name('keuangan.export');
            Route::get('/dashboard', [PengurusDashboard::class, 'index'])->name('dashboard.index');
            Route::get('/keuangan', [PengurusKeuangan::class, 'index'])->name('keuangan.index');

            // Dokumen Utama
            Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
            Route::get('/dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
            Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
            Route::get('/dokumen/download/{id}', [DokumenController::class, 'download'])->name('dokumen.download');
                 
            // Kegiatan
            Route::get('/kegiatan', [PengurusKegiatan::class, 'index'])->name('kegiatan.index');
            Route::get('/kegiatan/create', [PengurusKegiatan::class, 'create'])->name('kegiatan.create');
            Route::post('/kegiatan/store', [PengurusKegiatan::class, 'store'])->name('kegiatan.store');
        
             // Verifikasi & Anggota
             Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
             Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verifikasi'])->name('verifikasi'); 
             
             // Manajemen Anggota Internal
             Route::put('/anggota/{id}', [VerifikasiController::class, 'updateAnggota'])->name('anggota.update');
             Route::post('/anggota/{id}/arsip', [VerifikasiController::class, 'arsip'])->name('anggota.arsip');
             Route::post('/anggota/{id}/restore', [VerifikasiController::class, 'restore'])->name('anggota.restore');
    });

    // 3. AREA BENDAHARA
    Route::middleware('role:bendahara')->prefix('bendahara')->name('bendahara.')->group(function () {
        Route::get('/dashboard', [BendaharaDashboard::class, 'index'])->name('dashboard.index');
        Route::get('/info-ormawa', [InfoOrmawa::class, 'index'])->name('info-ormawa.index');

        // Input keuangan
        Route::get('/input-keuangan', [BendaharaInputKeuangan::class, 'create'])->name('input-keuangan.create');
        Route::post('/input-keuangan', [BendaharaInputKeuangan::class, 'store'])->name('input-keuangan.store');
        Route::get('/input-keuangan/export', [BendaharaInputKeuangan::class, 'exportExcel'])->name('input-keuangan.export');

        Route::get('/log-aktivitas', [BendaharaLogAktivitas::class, 'index'])->name('log-aktivitas.index');
    });

    // Rute Edit Utama Profil Ormawa (Oleh Pengurus/Ketua Umum)
    Route::get('/organisasi/edit', [OrganisasiController::class, 'edit'])->name('organisasi.edit');
    Route::put('/organisasi/update', [OrganisasiController::class, 'update'])->name('organisasi.update');

    // 4. AREA PEMBINA
    Route::prefix('pembina')
        ->name('pembina.')
        ->middleware(['role:pembina'])
        ->group(function () {
            Route::get('/dashboard', [RiwayatKegiatan::class, 'dashboard'])->name('dashboard'); 
            Route::get('/keuangan-binaan', [PembinaKeuanganBinaan::class, 'index'])->name('keuangan-binaan.index');
            Route::resource('riwayat-kegiatan', RiwayatKegiatan::class)->parameters([
                'riwayat-kegiatan' => 'id'
            ]);
    });

    // 5. AREA ADMIN GLOBAL (Super Admin)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['role:admin,pengurus,pembina']) // Gunakan koma, bukan pipe (|)
    ->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            // Persetujuan Proposal/Kegiatan
            Route::post('/persetujuan/{id}', [AdminController::class, 'persetujuan'])->name('persetujuan.persetujuan');

            // Keuangan Global
            Route::get('/keuangan-ormawa', [AdminKeuanganOrmawa::class, 'index'])->name('keuangan-ormawa.index');
            Route::get('/keuangan-ormawa/export', [AdminKeuanganOrmawa::class, 'exportExcel'])->name('keuangan-ormawa.export');

            // Manajemen Kelola Organisasi (CRUD)
            Route::get('/organisasi', [AdminController::class, 'index'])->name('organisasi.index');
            Route::get('/organisasi/create', [AdminController::class, 'create'])->name('organisasi.create');
            Route::post('/organisasi/store', [AdminController::class, 'store'])->name('organisasi.store');
            Route::get('/organisasi/{id}/edit', [AdminController::class, 'edit'])->name('organisasi.edit');
            Route::put('/organisasi/{id}', [AdminController::class, 'update'])->name('organisasi.update');
            Route::post('/organisasi/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('organisasi.toggle');
            
            // FIX KEARSIPAN: Diubah ke POST agar sinkron dengan AJAX Fetch API Anda
            Route::post('/organisasi/arsipkan/{id}', [AdminController::class, 'arsipkan'])->name('organisasi.arsipkan');
            Route::get('/organisasi/pulihkan/{id}', [AdminController::class, 'pulihkan'])->name('organisasi.pulihkan');
            
            // Utilitas Anggota & Dokumen Ormawa tertentu
            Route::get('/organisasi/{id}/anggota', [AdminController::class, 'getAnggota'])->name('organisasi.anggota');
            
            // FIX: Menyesuaikan endpoint URL akhir dari "/dokumen" menjadi "/dokumen-list" agar klop dengan JavaScript Blade Anda
            // SESUDAH DIUBAH:
            Route::get('/organisasi/{id}/dokumen', [AdminController::class, 'getDokumen'])->name('organisasi.dokumen');
            
    });

});