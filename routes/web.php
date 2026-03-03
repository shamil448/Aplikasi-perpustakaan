<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\MahasiswaDashboardController;
use App\Http\Controllers\DosenDashboardController;

// AUTH
Route::get('/register',[AuthController::class,'showRegister']);
Route::post('/register',[AuthController::class,'register']);

Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);

// STAFF
Route::middleware(['auth','role:staff'])->group(function(){
    Route::get('/staff/dashboard',[StaffDashboardController::class,'index']);
});

// MAHASISWA
Route::middleware(['auth','role:mahasiswa'])->group(function(){
    Route::get('/mahasiswa/dashboard',[MahasiswaDashboardController::class,'index']);
});

// DOSEN
Route::middleware(['auth','role:dosen'])->group(function(){
    Route::get('/dosen/dashboard',[DosenDashboardController::class,'index']);
});
