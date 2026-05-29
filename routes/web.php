<?php

use App\Http\Controllers\Auth\UserAuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PemesananPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [UserAuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::post('/register', [UserAuthenticatedSessionController::class, 'register'])->name('register.store');
});

Route::post('/logout', [UserAuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/kegiatan/{category}', [HomeController::class, 'beritaCategory'])->name('berita.category');
Route::get('/pengumuman', [HomeController::class, 'pengumuman'])->name('pengumuman');
Route::view('/guidebook', 'pages.guidebook')->name('guidebook');
Route::get('/pemesanan', [PemesananPageController::class, 'create'])
    ->middleware('auth')
    ->name('pemesanan.create');
Route::post('/pemesanan', [PemesananPageController::class, 'store'])
    ->middleware('auth')
    ->name('pemesanan.store');
Route::get('/pendaftaran', [HomeController::class, 'pendaftaran'])
    ->middleware('auth')
    ->name('pendaftaran');

Route::post('/pendaftaran', [HomeController::class, 'submitPendaftaran'])
    ->middleware('auth')
    ->name('pendaftaran.store');
