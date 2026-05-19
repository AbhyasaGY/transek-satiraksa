<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- GRUP ADMIN ---
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Selamat datang di Dasbor Admin. Hanya Admin yang bisa melihat ini.';
    });
});

// --- GRUP KASIR (Modul POS) ---
// Admin juga diizinkan mengakses halaman kasir jika diperlukan
Route::middleware(['auth', 'role:Kasir,Admin'])->group(function () {
    Route::get('/kasir/pos', function () {
        return 'Ini adalah halaman Point of Sales (POS) untuk Kasir.';
    });
});

// --- GRUP RESELLER (Modul Kontrak) ---
Route::middleware(['auth', 'role:Reseller'])->group(function () {
    Route::get('/reseller/kontrak', function () {
        return 'Ini adalah Dasbor Kemitraan untuk generate Kontrak PDF.';
    });
});

require __DIR__.'/auth.php';