<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController; // <-- PASTIKAN BARIS INI ADA DI ATAS

Route::get('/', function () {
    return view('welcome');
});

// Route bawaan Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

// ==========================================
// CUSTOM ROUTES: ROLE-BASED ACCESS CONTROL
// ==========================================

// --- GRUP ADMIN ---
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Selamat datang di Dasbor Admin. Hanya Admin yang bisa melihat ini.';
    });
});

// --- GRUP KASIR (Modul POS) ---
// (Ini yang baru)
Route::middleware(['auth', 'role:Kasir,Admin'])->group(function () {
    Route::get('/kasir/pos', [TransactionController::class, 'index'])->name('pos.index');
    Route::post('/kasir/pos/checkout', [TransactionController::class, 'store'])->name('pos.store');
});

// --- GRUP RESELLER (Modul Kontrak) ---
Route::middleware(['auth', 'role:Reseller'])->group(function () {
    Route::get('/reseller/kontrak', function () {
        return 'Ini adalah Dasbor Kemitraan untuk generate Kontrak PDF.';
    });
});
