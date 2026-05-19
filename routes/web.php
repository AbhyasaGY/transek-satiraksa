<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController; // <-- Tambahan untuk profil
use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- KEMBALIKAN ROUTE PROFILE BAWAAN BREEZE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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
Route::middleware(['auth', 'role:Kasir,Admin'])->group(function () {
    Route::get('/kasir/pos', [TransactionController::class, 'index'])->name('pos.index');
    Route::post('/kasir/pos/checkout', [TransactionController::class, 'store'])->name('pos.store');

    // Ini tambahan rute baru
    Route::get('/kasir/pos/sukses', function () {
        return view('pos.success');
    })->name('pos.success');
});

// --- GRUP RESELLER (Modul Kontrak) ---
Route::middleware(['auth', 'role:Reseller'])->group(function () {
    Route::get('/reseller/kontrak', function () {
        return 'Ini adalah Dasbor Kemitraan untuk generate Kontrak PDF.';
    });
});

// Rute API Webhook Midtrans (Tidak boleh dibungkus middleware auth!)
Route::post('/api/webhook/midtrans', [WebhookController::class, 'midtransHandler']);