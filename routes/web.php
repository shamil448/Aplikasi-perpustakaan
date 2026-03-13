<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\SirkulasiController;


// =======================
// AUTH
// =======================

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);


// =======================
// STAFF
// =======================

Route::middleware(['auth', 'role:staff'])->group(function () {

    // dashboard staff
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index']);

    // =======================
    // BIBLIOGRAFI (BUKU)
    // =======================

    // daftar buku
    Route::get('/books', [BookController::class, 'index']);

    // halaman tambah buku
    Route::get('/books/create', [BookController::class, 'create']);

    // simpan buku
    Route::post('/books', [BookController::class, 'store']);

    // halaman edit buku
    Route::get('/books/{id}/edit', [BookController::class, 'edit']);

    // update buku
    Route::put('/books/{id}', [BookController::class, 'update']);

    // hapus buku
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    // hapus banyak buku
    Route::post('/books/delete-selected', [BookController::class, 'deleteSelected']);

    // halaman eksemplar
    Route::get('/books/{id}/eksemplar', [BookController::class, 'eksemplar']);

    // simpan eksemplar
    Route::post('/books/{id}/eksemplar', [BookController::class, 'storeEksemplar']);
});


// =======================
// MAHASISWA
// =======================

Route::middleware(['auth', 'role:mahasiswa'])->group(function () {

    // dashboard mahasiswa
    Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index']);

    // halaman sirkulasi
    Route::get('/mahasiswa/sirkulasi', function () {
        return view('mahasiswa.sirkulasi');
    });

    // proses peminjaman buku
    Route::post('/mahasiswa/pinjam', [SirkulasiController::class, 'pinjam']);

    Route::get('/mahasiswa/pinjaman', [SirkulasiController::class, 'pinjamanSaatIni']);
});


// =======================
// DOSEN
// =======================

Route::middleware(['auth', 'role:dosen'])->group(function () {

    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index']);
});
