<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Registrasi;
use App\Http\Controllers\Mahasiswa\Dashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/login', [Login::class, 'create'])->name('login');
Route::post('/login', [Login::class, 'store']);
Route::post('/logout', [Login::class, 'destroy'])->name('logout');

Route::get('/register', [Registrasi::class,'create'])->name('register');
Route::post('/register', [Registrasi::class,'store']);
Route::middleware('guest')->group(function () {
});



Route::middleware(['auth'])->group(function () {

    // Mahasiswa
    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard');
    });
});

