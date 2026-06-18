<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Registrasi;
use App\Http\Controllers\Mahasiswa\Dashboard as MahasiswaDashboard;
use App\Http\Controllers\Mahasiswa\KegiatanSaya as MahasiswaKegiatanSaya;
use App\Http\Controllers\Pengurus\VerifikasiController;
use App\Http\Controllers\Pengurus\OrganisasiController; 
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KeuanganOrmawa as AdminKeuanganOrmawa;
use App\Http\Controllers\Bendahara\InputKeuangan as BendaharaInputKeuangan;
use App\Http\Controllers\Bendahara\Dashboard as BendaharaDashboard;
use App\Http\Controllers\Bendahara\InfoOrmawa;
use App\Http\Controllers\Bendahara\LogAktivitas as BendaharaLogAktivitas;
use App\Http\Controllers\Mahasiswa\DaftarKegiatan as MahasiswaDaftarKegiatan;
use App\Http\Controllers\Pembina\KeuanganBinaan as PembinaKeuanganBinaan;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Pembina\RiwayatKegiatan;
use App\Http\Controllers\Pengurus\Dashboard as PengurusDashboard;
use App\Http\Controllers\Pengurus\Kegiatan as PengurusKegiatan;
use App\Http\Controllers\Pengurus\Keuangan as PengurusKeuangan;
use Illuminate\Support\Facades\Route;


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

    // 1. AREA MAHASISWA (Hanya bisa diakses oleh role: mahasiswa)
    Route::prefix('mahasiswa')
     ->name('mahasiswa.')
     ->middleware(['role:mahasiswa']) // <-- Proteksi Role
     ->group(function () {
        Route::get('/daftar-kegiatan', [MahasiswaDaftarKegiatan::class, 'index'])->name('daftar-kegiatan.index');
        Route::get('/daftar-kegiatan/{id}', [MahasiswaDaftarKegiatan::class, 'show'])->name('daftar-kegiatan.show');
        Route::post('/daftar-kegiatan/{id}', [MahasiswaDaftarKegiatan::class, 'daftar'])->name('daftar-kegiatan.daftar');

         Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
         Route::get('/organisasi', [MahasiswaDashboard::class, 'organisasi'])->name('organisasi.index');
         
         // KUNCI FIX: Rute DAFTAR HARUS DI ATAS rute DETAIL!
         Route::get('/organisasi/{id}/daftar', [MahasiswaDashboard::class, 'pendaftaran'])->name('organisasi.daftar');
         Route::post('/organisasi/daftar', [MahasiswaDashboard::class, 'store'])->name('organisasi.store');
         
         // Rute DETAIL
         Route::get('/organisasi/{id}', [MahasiswaDashboard::class, 'show'])->name('organisasi.show'); 

         // ── TAMBAHAN RUTE BARU: KEGIATAN SAYA ──
         // URL: /mahasiswa/kegiatan-saya  |  Nama Route: mahasiswa.kegiatan-saya
         Route::get('/kegiatan-saya', [MahasiswaKegiatanSaya::class, 'index'])->name('kegiatan-saya');
         // TAMBAHAN RUTE UNTUK DETAIL API (ALPINJS AJAX)
         Route::get('/kegiatan-saya/{id}', [MahasiswaKegiatanSaya::class, 'show'])->name('kegiatan-saya.show');
     });

    // 2. AREA PENGURUS (Hanya bisa diakses oleh role: pengurus)
    Route::prefix('pengurus')
         ->name('pengurus.')
         ->middleware(['role:pengurus']) // <-- Proteksi Role
         ->group(function () {
            Route::get('/keuangan/export/{id}', [PengurusKeuangan::class, 'export'])->name('keuangan.export');
            
            Route::get('/dashboard', [PengurusDashboard::class, 'index'])->name('dashboard.index');

            Route::get('/keuangan', [PengurusKeuangan::class, 'index'])->name('keuangan.index');
             
            // Kegiatan
            Route::get('/kegiatan', [PengurusKegiatan::class, 'index'])->name('kegiatan.index');
            Route::get('/kegiatan/create', [PengurusKegiatan::class, 'create'])->name('kegiatan.create');
            Route::post('/kegiatan/store', [PengurusKegiatan::class, 'store'])->name('kegiatan.store');
            Route::put('/kegiatan/{id}', [PengurusKegiatan::class, 'update'])->name('kegiatan.update');
            //Route::get('/kegiatan/{id}', [PengurusKegiatan::class, 'show'])->name('kegiatan.show'); // <-- Tambahkan baris ini
        
             // Verifikasi & Anggota
             Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
             Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verifikasi'])->name('verifikasi'); 
             
             // Manajemen Anggota Internal
             Route::put('/anggota/{id}', [VerifikasiController::class, 'updateAnggota'])->name('anggota.update');
             Route::post('/anggota/{id}/arsip', [VerifikasiController::class, 'arsip'])->name('anggota.arsip');
             Route::post('/anggota/{id}/restore', [VerifikasiController::class, 'restore'])->name('anggota.restore');
        
    });

    Route::middleware('role:bendahara')->prefix('bendahara')->name('bendahara.')->group(function () {

        // Dashboard keuangan
        Route::get('/dashboard', [BendaharaDashboard::class, 'index'])->name('dashboard.index');

        // Info ormawa
        Route::get('/info-ormawa', [InfoOrmawa::class, 'index'])->name('info-ormawa.index');

        // Input keuangan
        Route::get('/input-keuangan', [BendaharaInputKeuangan::class, 'create'])->name('input-keuangan.create');
        Route::post('/input-keuangan', [BendaharaInputKeuangan::class, 'store'])->name('input-keuangan.store');
        Route::get('/input-keuangan/export', [BendaharaInputKeuangan::class, 'exportExcel'])->name('input-keuangan.export');

        // Log aktivitas
        Route::get('/log-aktivitas', [BendaharaLogAktivitas::class, 'index'])->name('log-aktivitas.index');
    });

    Route::get('/organisasi/edit', [OrganisasiController::class, 'edit'])->name('organisasi.edit');
    Route::put('/organisasi/update', [OrganisasiController::class, 'update'])->name('organisasi.update');

    Route::prefix('pembina')
        ->name('pembina.')
        ->middleware(['role:pembina'])
        ->group(function () {
            
        // SINKRON: Sekarang mengarah ke fungsi dashboard()
        Route::get('/dashboard', [RiwayatKegiatan::class, 'dashboard'])->name('dashboard'); 
        
        // Rute Resource untuk Riwayat (Otomatis mengarah ke fungsi index())
        Route::get('/keuangan-binaan', [PembinaKeuanganBinaan::class, 'index'])->name('keuangan-binaan.index');
        Route::resource('riwayat-kegiatan', RiwayatKegiatan::class)->parameters([
            'riwayat-kegiatan' => 'id'
        ]);
    });

    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['role:admin']) // <-- Proteksi Role
        ->group(function () {
        // Dashboard Utama Admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Fitur Keuangan ormawa & export
        Route::get('/keuangan-ormawa', [AdminKeuanganOrmawa::class, 'index'])->name('keuangan-ormawa.index');
        Route::get('/keuangan-ormawa/export', [AdminKeuanganOrmawa::class, 'exportExcel'])->name('keuangan-ormawa.export');

        // Fitur Tambah Organisasi Baru
        Route::get('/organisasi/create', [AdminController::class, 'create'])->name('organisasi.create');
        Route::post('/organisasi/store', [AdminController::class, 'store'])->name('organisasi.store');

        // Manajemen Organisasi oleh Admin
        Route::get('/organisasi', [AdminController::class, 'index'])->name('organisasi.index');
        Route::get('/organisasi/{id}/edit', [AdminController::class, 'edit'])->name('organisasi.edit');
        Route::put('/organisasi/{id}', [AdminController::class, 'update'])->name('organisasi.update');
        Route::post('/organisasi/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('organisasi.toggle');
        
        // Fitur Kearsipan Organisasi via Admin
        Route::get('/organisasi/arsipkan/{id}', [AdminController::class, 'arsipkan'])->name('organisasi.arsipkan');
        Route::get('/organisasi/pulihkan/{id}', [AdminController::class, 'pulihkan'])->name('organisasi.pulihkan');
        
        Route::get('/organisasi/{id}/anggota', [AdminController::class, 'getAnggota'])->name('organisasi.anggota');
    });

}); // Penutup Middleware Auth Utama