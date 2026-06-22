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
use App\Http\Controllers\Mahasiswa\LogAktivitas as MahasiswaLogAktivitas;
use App\Http\Controllers\Pengurus\LogAktivitas as PengurusLogAktivitas;
use App\Http\Controllers\Pembina\LogAktivitas as PembinaLogAktivitas;
use App\Http\Controllers\Admin\LogAktivitas as AdminLogAktivitas;
use App\Http\Controllers\Pembina\KeuanganBinaan as PembinaKeuanganBinaan;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Pembina\RiwayatKegiatan;
use App\Http\Controllers\Pengurus\Dashboard as PengurusDashboard;
use App\Http\Controllers\Pengurus\Kegiatan as PengurusKegiatan;
use App\Http\Controllers\Pengurus\Keuangan as PengurusKeuangan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DokumenKegiatan;
use App\Http\Controllers\Admin\Pengguna as AdminPengguna;
use App\Http\Controllers\Admin\SemuaKegiatan as AdminSemuaKegiatan;
use App\Http\Controllers\Mahasiswa\DaftarKegiatan as MahasiswaDaftarKegiatan;
use App\Http\Controllers\Pembina\OrmawaBinaan as PembinaOrmawaBinaan;
use App\Http\Controllers\Pembina\PersetujuanKegiatan as PembinaPersetujuanKegiatan;
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
     ->middleware(['role:mahasiswa,pengurus,bendahara']) // <-- Proteksi Role
     ->group(function () {
        // ==========================================
        // AREA MAHASISWA (Prefix: mahasiswa, Middleware: role:mahasiswa,pengurus,bendahara)
        // ==========================================

        // Dashboard Utama
        Route::get('/dashboard', [MahasiswaDashboard::class, 'index'])->name('dashboard');

        // Fitur Daftar Kegiatan (Eksplorasi & Pendaftaran)
        Route::get('/daftar-kegiatan', [MahasiswaDaftarKegiatan::class, 'index'])->name('daftar-kegiatan.index');
        Route::get('/daftar-kegiatan/{id}', [MahasiswaDaftarKegiatan::class, 'show'])->name('daftar-kegiatan.show');
        Route::post('/daftar-kegiatan/{id}', [MahasiswaDaftarKegiatan::class, 'daftar'])->name('daftar-kegiatan.daftar');
        Route::post('/kegiatan/daftar', [PendaftaranPeserta::class, 'daftar'])->name('kegiatan.daftar');

        // Fitur Kegiatan Saya (Riwayat & Progress)
        Route::get('/kegiatan-saya', [MahasiswaKegiatanSaya::class, 'index'])->name('kegiatan-saya');
        Route::get('/kegiatan-saya/{id}', [MahasiswaKegiatanSaya::class, 'show'])->name('kegiatan-saya.show');

        // Fitur Organisasi
        Route::get('/organisasi', [MahasiswaDashboard::class, 'organisasi'])->name('organisasi.index');

        // ⚠️ KUNCI FIX: Rute Parameter Spesifik HARUS DI ATAS Rute Wildcard {id}!
        Route::get('/organisasi/{id}/daftar', [MahasiswaDashboard::class, 'pendaftaran'])->name('organisasi.daftar');
        Route::post('/organisasi/daftar', [MahasiswaDashboard::class, 'store'])->name('organisasi.store');

        // Rute Detail Organisasi (Wildcard di paling bawah kelompoknya)
        Route::get('/organisasi/{id}', [MahasiswaDashboard::class, 'show'])->name('organisasi.show'); 

        // Log Aktivitas Internal Mahasiswa
        Route::get('/log-aktivitas', [MahasiswaLogAktivitas::class, 'index'])->name('log-aktivitas.index');
        Route::get('/log-aktivitas/export-pdf', [MahasiswaLogAktivitas::class, 'exportPdf'])->name('log-aktivitas.export-pdf');
     });

    // 2. AREA PENGURUS (Hanya bisa diakses oleh role: pengurus)
    Route::get('/keuangan/export/{id}/{format}', [PengurusKeuangan::class, 'export'])->name('keuangan.export');
    Route::prefix('pengurus')
         ->name('pengurus.')
         ->middleware(['role:pengurus'])
         ->group(function () {
            // ==========================================
            // AREA PENGURUS (Prefix: pengurus, Middleware: role:pengurus)
            // ==========================================

            // Dashboard Utama
            Route::get('/dashboard', [PengurusDashboard::class, 'index'])->name('dashboard.index');

            // Keuangan & Export (Menggunakan format 2 parameter: {id}/{format})
            Route::get('/keuangan', [PengurusKeuangan::class, 'index'])->name('keuangan.index');

            // Dokumen Utama
            Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
            Route::get('/dokumen/create', [DokumenController::class, 'create'])->name('dokumen.create');
            Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
            Route::get('/dokumen/download/{id}', [DokumenController::class, 'download'])->name('dokumen.download');

            // Manajemen Kegiatan (CRUD)
            Route::get('/kegiatan', [PengurusKegiatan::class, 'index'])->name('kegiatan.index');
            Route::get('/kegiatan/create', [PengurusKegiatan::class, 'create'])->name('kegiatan.create');
            Route::post('/kegiatan/store', [PengurusKegiatan::class, 'store'])->name('kegiatan.store');
            Route::put('/kegiatan/{id}', [PengurusKegiatan::class, 'update'])->name('kegiatan.update');
            // Route::get('/kegiatan/{id}', [PengurusKegiatan::class, 'show'])->name('kegiatan.show');

            // Verifikasi Pendaftaran Anggota Baru
            Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
            Route::post('/verifikasi/{id}', [VerifikasiController::class, 'verifikasi'])->name('verifikasi'); 

            // Manajemen Anggota Internal (Active, Archive, & Restore)
            Route::put('/anggota/{id}', [VerifikasiController::class, 'updateAnggota'])->name('anggota.update');
            Route::post('/anggota/{id}/arsip', [VerifikasiController::class, 'arsip'])->name('anggota.arsip');
            Route::post('/anggota/{id}/restore', [VerifikasiController::class, 'restore'])->name('anggota.restore');

            // Log Aktivitas Internal Pengurus
            Route::get('/log-aktivitas', [PengurusLogAktivitas::class, 'index'])->name('log-aktivitas.index');
            Route::get('/log-aktivitas/export-pdf', [PengurusLogAktivitas::class, 'exportPdf'])->name('log-aktivitas.export-pdf');
    });

    // 3. AREA BENDAHARA
    Route::middleware('role:bendahara')->prefix('bendahara')->name('bendahara.')->group(function () {
     // ==========================================
        // AREA BENDAHARA (Prefix: bendahara, Middleware: role:bendahara)
        // ==========================================

        // Dashboard Keuangan
        Route::get('/dashboard', [BendaharaDashboard::class, 'index'])->name('dashboard.index');

        // Informasi Ormawa
        Route::get('/info-ormawa', [InfoOrmawa::class, 'index'])->name('info-ormawa.index');

        // Transaksi & Input Keuangan
        Route::get('/input-keuangan', [BendaharaInputKeuangan::class, 'create'])->name('input-keuangan.create');
        Route::post('/input-keuangan', [BendaharaInputKeuangan::class, 'store'])->name('input-keuangan.store');

        // Export Data Keuangan (Pilih salah satu sesuai metode Controller kamu)
        Route::get('/input-keuangan/export', [BendaharaInputKeuangan::class, 'exportExcel'])->name('input-keuangan.export');
        // Route::get('/input-keuangan/export/{format}', [BendaharaInputKeuangan::class, 'export'])->name('input-keuangan.export-format');

        // Monitoring Laporan Global
        Route::get('/laporan', [PengurusKeuangan::class, 'index'])->name('laporan.index');

        // Log Aktivitas Internal Bendahara
        Route::get('/log-aktivitas', [BendaharaLogAktivitas::class, 'index'])->name('log-aktivitas.index');
        Route::get('/log-aktivitas/export-pdf', [BendaharaLogAktivitas::class, 'exportPdf'])->name('log-aktivitas.export-pdf');
    });

    // Rute Edit Utama Profil Ormawa (Oleh Pengurus/Ketua Umum)
    Route::get('/organisasi/edit', [OrganisasiController::class, 'edit'])->name('organisasi.edit');
    Route::put('/organisasi/update', [OrganisasiController::class, 'update'])->name('organisasi.update');

    // 4. AREA PEMBINA
    Route::prefix('pembina')
        ->name('pembina.')
        ->middleware(['role:pembina'])
        ->group(function () {
           // ==========================================
            // AREA PEMBINA (Prefix: pembina, Middleware: role:pembina)
            // ==========================================

            // Dashboard Utama
            Route::get('/dashboard', [RiwayatKegiatan::class, 'dashboard'])->name('dashboard'); 

            // Ormawa Binaan & Status
            Route::get('/ormawa-binaan', [PembinaOrmawaBinaan::class, 'index'])->name('ormawa-binaan.index');
            Route::post('/setStatus/{id}', [PembinaOrmawaBinaan::class, 'setStatus'])->name('setStatus.setStatus');

            // Persetujuan Kegiatan (Proposal)
            Route::get('/pesetujuan-kegiatan', [PembinaPersetujuanKegiatan::class, 'index'])->name('persetujuan-kegiatan.index');
            Route::patch('/pesetujuan/setStatus/{id}', [PembinaPersetujuanKegiatan::class, 'setStatus'])->name('setStatus.setStatus');

            // Keuangan Binaan
            Route::get('/keuangan-binaan', [PembinaKeuanganBinaan::class, 'index'])->name('keuangan-binaan.index');

            // Riwayat Kegiatan (Resource CRUD)
            Route::resource('riwayat-kegiatan', RiwayatKegiatan::class)->parameters([
                'riwayat-kegiatan' => 'id'
            ]);

            // Log Aktivitas Internal Pembina
            Route::get('/log-aktivitas', [PembinaLogAktivitas::class, 'index'])->name('log-aktivitas.index');
            Route::get('/log-aktivitas/export-pdf', [PembinaLogAktivitas::class, 'exportPdf'])->name('log-aktivitas.export-pdf');
    });

    // 5. AREA ADMIN GLOBAL (Super Admin)
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['role:admin,pengurus,pembina']) // Gunakan koma, bukan pipe (|)
        ->group(function () {
            // ==========================================
            // AREA ADMIN (Prefix: admin, Middleware: role:admin)
            // ==========================================

            // Dashboard Utama & Persetujuan
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            Route::post('/persetujuan/{id}', [AdminController::class, 'persetujuan'])->name('persetujuan.persetujuan');

            // Fitur Keuangan Ormawa & Export
            Route::get('/keuangan-ormawa', [AdminKeuanganOrmawa::class, 'index'])->name('keuangan-ormawa.index');
            Route::get('/keuangan-ormawa/export', [AdminKeuanganOrmawa::class, 'exportExcel'])->name('keuangan-ormawa.export');

            // Manajemen & Kelola Organisasi (CRUD + Kearsipan)
            Route::get('/organisasi', [AdminController::class, 'index'])->name('organisasi.index');
            Route::get('/organisasi/create', [AdminController::class, 'create'])->name('organisasi.create');
            Route::post('/organisasi/store', [AdminController::class, 'store'])->name('organisasi.store');
            Route::get('/organisasi/{id}/edit', [AdminController::class, 'edit'])->name('organisasi.edit');
            Route::put('/organisasi/{id}', [AdminController::class, 'update'])->name('organisasi.update');
            Route::post('/organisasi/{id}/toggle', [AdminController::class, 'toggleStatus'])->name('organisasi.toggle');
            Route::post('/organisasi/arsipkan/{id}', [AdminController::class, 'arsipkan'])->name('organisasi.arsipkan');
            Route::get('/organisasi/pulihkan/{id}', [AdminController::class, 'pulihkan'])->name('organisasi.pulihkan');

            // Utilitas Anggota, Dokumen, & Semua Kegiatan Ormawa
            Route::get('/organisasi/{id}/anggota', [AdminController::class, 'getAnggota'])->name('organisasi.anggota');
            Route::get('/organisasi/{id}/dokumen', [AdminController::class, 'getDokumen'])->name('organisasi.dokumen');
            Route::get('/semua-kegiatan', [AdminSemuaKegiatan::class, 'index'])->name('semua-kegiatan.index');

            // Manajemen Pengguna (User)
            Route::get('/pengguna', [AdminPengguna::class, 'index'])->name('pengguna.index');
            Route::post('/pengguna', [AdminPengguna::class, 'store'])->name('pengguna.store');
            Route::put('/pengguna/{id}', [AdminPengguna::class, 'update'])->name('pengguna.update');
            Route::get('/organisasi/{id}/detail', [AdminPengguna::class, 'getOrganisasiDetail'])->name('admin.organisasi.detail');

            // Log Aktivitas Sistem
            Route::get('/log-aktivitas', [AdminLogAktivitas::class, 'index'])->name('log-aktivitas.index');
            Route::get('/log-aktivitas/export-pdf', [AdminLogAktivitas::class, 'exportPdf'])->name('log-aktivitas.export-pdf');
    });

}); // Penutup Middleware Auth Utama
