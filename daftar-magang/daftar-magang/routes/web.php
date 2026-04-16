<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MagangController;

// Jalur Utama (Landing Page)
Route::get('/', [MagangController::class, 'landing'])->name('magang.landing');

Route::prefix('magang')->name('magang.')->group(function () {
    
    // --- FITUR USER (Pelamar) ---
    Route::get('/daftar', [MagangController::class, 'form'])->name('form');
    Route::post('/daftar', [MagangController::class, 'submit'])->name('submit'); 
    Route::get('/status', [MagangController::class, 'status'])->name('status');
    
    // Cek Status Lamaran
    Route::get('/cek-status', [MagangController::class, 'cekStatusForm'])->name('cek-status');
    Route::post('/cek-status', [MagangController::class, 'cekStatusResult'])->name('cek-status.result');

    // --- FITUR ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/login', [MagangController::class, 'loginForm'])->name('login');
        Route::post('/login', [MagangController::class, 'loginSubmit'])->name('login.submit');
        Route::post('/logout', [MagangController::class, 'logout'])->name('logout'); 
        
        Route::get('/dashboard', [MagangController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/detail/{id}', [MagangController::class, 'adminDetail'])->name('detail');
        Route::post('/update-status/{id}', [MagangController::class, 'updateStatus'])->name('update');
        // Route untuk ekspor data ke Excel
        Route::get('/export', [MagangController::class, 'exportExcel'])->name('export');
    });
});