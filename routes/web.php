<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Registrasi;
use App\Http\Controllers\Mahasiswa\Dashboard as MahasiswaDashboard;
use App\Http\Controllers\Pengurus\VerifikasiController;
use App\Http\Controllers\Pengurus\OrganisasiController; 
use App\Http\Controllers\Admin\AdminController;
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
// AREA MAHASISWA & PENGURUS (MIDDLEWARE: AUTH)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // 1. AREA MAHASISWA
    Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
        Route::get('/organisasi', [MahasiswaDashboard::class, 'organisasi'])->name('organisasi.index');
        
        // KUNCI FIX: Rute DAFTAR HARUS DI ATAS rute DETAIL!
        Route::get('/organisasi/{id}/daftar', [MahasiswaDashboard::class, 'pendaftaran'])->name('organisasi.daftar');
        Route::post('/organisasi/daftar', [MahasiswaDashboard::class, 'store'])->name('organisasi.store');
        
        // Rute DETAIL taruh di paling bawah agar tidak "memakan" rute lain
        Route::get('/organisasi/{id}', [MahasiswaDashboard::class, 'show'])->name('organisasi.show'); 
    });

    // 2. AREA PENGURUS
    Route::prefix('pengurus')->name('pengurus.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');
        
        // Verifikasi & Anggota
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verifikasi'])->name('verifikasi'); 
        
        // Manajemen Anggota Internal
        Route::put('/anggota/{id}', [VerifikasiController::class, 'updateAnggota'])->name('anggota.update');
        Route::post('/anggota/{id}/arsip', [VerifikasiController::class, 'arsip'])->name('anggota.arsip');
        Route::post('/anggota/{id}/restore', [VerifikasiController::class, 'restore'])->name('anggota.restore');

        // Manajemen Profil Organisasi Internal Pengurus
        Route::get('/organisasi/edit', [OrganisasiController::class, 'edit'])->name('organisasi.edit');
        Route::put('/organisasi/update', [OrganisasiController::class, 'update'])->name('organisasi.update');
    });

}); // Penutup Middleware Auth Utama


// ==========================================
// 3. AREA ADMIN (MIDDLEWARE: AUTH & ROLE ADMIN)
// ==========================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Utama Admin
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
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
    
    // FIX SINKRONISASI: Mengubah URL dari '/organisasi/{id}/get-anggota' menjadi '/organisasi/{id}/anggota' 
    // agar tepat sasaran dengan fungsi fetch() JavaScript di file edit.blade.php
    // FIX SINKRONISASI: Menghapus '/admin' di awal path karena sudah otomatis terisi oleh prefix grup admin di atas
    Route::get('/organisasi/{id}/anggota', [AdminController::class, 'getAnggota'])->name('organisasi.anggota');
});