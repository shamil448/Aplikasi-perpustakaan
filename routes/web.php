<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\DosenDashboardController;
use App\Http\Controllers\SirkulasiController;
use App\Http\Controllers\MemberController;


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

// =======================
// STAFF
// =======================

// =======================
// STAFF
// =======================

// =======================
// STAFF
// =======================

Route::middleware(['auth', 'role:staff'])->group(function () {

    // dashboard staff
    Route::get('/staff/dashboard', [StaffDashboardController::class, 'index']);

    // laporan keuangan
    Route::get('/staff/laporan-keuangan', [SirkulasiController::class, 'laporanKeuangan']);

    // cetak PDF
    Route::get('/staff/laporan-keuangan/pdf', [SirkulasiController::class, 'cetakPdf']);

    // export Excel
    Route::get('/staff/laporan-keuangan/excel', [SirkulasiController::class, 'exportExcel']);

    // buku
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/create', [BookController::class, 'create']);
    Route::post('/books', [BookController::class, 'store']);

    Route::get('/books/{id}/edit', [BookController::class, 'edit']);
    Route::put('/books/{id}', [BookController::class, 'update']);

    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    Route::post('/books/delete-selected', [BookController::class, 'deleteSelected']);

    Route::get('/books/{id}/eksemplar', [BookController::class, 'eksemplar']);
    Route::post('/books/{id}/eksemplar', [BookController::class, 'storeEksemplar']);

    Route::get('/eksemplar', [BookController::class, 'daftarEksemplar']);
    Route::get('/eksemplar-keluar', [BookController::class, 'eksemplarKeluar']);
    Route::get('/eksemplar-denda', [BookController::class, 'eksemplarDenda']);

    // anggota
    Route::get('/anggota', [MemberController::class, 'index']);

    Route::get('/anggota/{id}', [MemberController::class, 'show']);

    Route::post(
        '/anggota/{id}/reset-password',
        [MemberController::class, 'resetPassword']
    );
});

// =======================
// MAHASISWA
// =======================

Route::middleware(['auth', 'role:mahasiswa,dosen'])->group(function () {

    // dashboard mahasiswa
    Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index']);

    // halaman sirkulasi
    Route::get('/mahasiswa/sirkulasi', function () {
        return view('mahasiswa.sirkulasi');
    });

    // proses peminjaman buku
    Route::post('/mahasiswa/pinjam', [SirkulasiController::class, 'pinjam']);

    // pinjaman saat ini
    Route::get('/mahasiswa/pinjaman', [SirkulasiController::class, 'pinjamanSaatIni']);

    // =====================================
    // PENGEMBALIAN BUKU (BARU)
    // =====================================

    // halaman pengembalian
    Route::get(
        '/mahasiswa/pengembalian',
        [SirkulasiController::class, 'pengembalian']
    );

    // cari data pinjaman berdasarkan kode eksemplar
    Route::post(
        '/mahasiswa/pengembalian',
        [SirkulasiController::class, 'cariPengembalian']
    );

    // konfirmasi pengembalian
    Route::post(
        '/mahasiswa/pengembalian/{id}',
        [SirkulasiController::class, 'konfirmasiPengembalian']
    );

    // =====================================
    // PERPANJANG
    // =====================================

    Route::post(
        '/mahasiswa/perpanjang/{id}',
        [SirkulasiController::class, 'perpanjang']
    );

    // =====================================
    // DENDA
    // =====================================

    Route::post(
        '/mahasiswa/denda/{id}',
        [SirkulasiController::class, 'denda']
    );

    // halaman bayar
    Route::get(
        '/mahasiswa/bayar/{id}',
        [SirkulasiController::class, 'halamanBayar']
    )->name('bayar');

    // halaman denda
    Route::get(
        '/mahasiswa/denda',
        [SirkulasiController::class, 'halamanDenda']
    );

    // aktivasi denda
    Route::post(
        '/mahasiswa/aktivasi-denda/{id}',
        [SirkulasiController::class, 'aktivasiDenda']
    );

    // sejarah peminjaman
    Route::get(
        '/mahasiswa/sejarah',
        [SirkulasiController::class, 'sejarah']
    );
});

// =======================
// DOSEN
// =======================

Route::middleware(['auth', 'role:dosen'])->group(function () {

    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index']);
});


// =======================
// CALLBACK MIDTRANS
// =======================

Route::post('/payment/callback', [SirkulasiController::class, 'callback']);


// =======================
// PROFILE ANGGOTA
// =======================

Route::post('/anggota/{id}/profile', [MemberController::class, 'saveProfile']);


// =======================
// DAFTAR BUKU SISWA / UMUM
// =======================

Route::get('/daftar-buku', [BookController::class, 'daftarBuku']);
