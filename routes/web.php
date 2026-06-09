<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController; // <-- Tambahan untuk profil
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// --- SMART REDIRECTOR ---
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    $role = $request->user()->role;

    if ($role === 'Admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'Kasir') {
        return redirect()->route('pos.index');
    } elseif ($role === 'Reseller') {
        return redirect()->route('reseller.dashboard');
    } elseif ($role === 'Pelanggan' || empty($role)) {
        return redirect()->route('pelanggan.dashboard'); // <-- DIUBAH KE SINI
    } else {
        return abort(403, 'Akses ditolak: Peran akun tidak valid.');
    }
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

// --- GRUP ROUTE ADMIN ---
Route::middleware(['auth', 'role:Admin'])->group(function () {
    // Rute dasbor admin yang sudah ada sebelumnya
    Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');

    // TAMBAHKAN BARIS INI: Rute otomatis untuk CRUD Produk
    Route::resource('admin/products', ProductController::class);

    Route::get('/admin/kontrak', [\App\Http\Controllers\AdminController::class, 'validasiKontrak'])->name('admin.contracts');
    Route::post('/admin/kontrak/{id}/proses', [\App\Http\Controllers\AdminController::class, 'prosesValidasi'])->name('admin.contracts.process');
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

// --- GRUP RESELLER (Modul Kontrak & B2B) ---
Route::middleware(['auth', 'role:Reseller'])->group(function () {
    Route::get('/reseller/dashboard', [ResellerController::class, 'index'])->name('reseller.dashboard');

    // 1. Rute untuk mencetak PDF kosong (Diubah namanya agar tidak bentrok)
    Route::get('/reseller/kontrak/generate', [ResellerController::class, 'generateContract'])->name('reseller.contract.generate');

    // 2. Rute untuk mengunggah dokumen fisik (Disesuaikan dengan action di form View)
    Route::post('/reseller/kontrak/upload', [ResellerController::class, 'uploadContract'])->name('reseller.uploadContract');

    // 3. Rute untuk mengunduh dokumen fisik (Lapis 2 & 3)
    // WAJIB berada di dalam Middleware agar fungsi Auth::id() di Controller bisa membaca siapa yang login
    Route::get('/reseller/kontrak/{id}/download', [ResellerController::class, 'downloadContract'])->name('reseller.contract.downloadFile');

    // Rute Katalog Mitra
    Route::get('/reseller/katalog', [ResellerController::class, 'belanja'])->name('reseller.belanja');
});
// --- GRUP ROUTE BARU: PELANGGAN ---
Route::middleware(['auth', 'role:Pelanggan'])->group(function () {
    Route::get('/pelanggan/dashboard', [PelangganController::class, 'dashboard'])->name('pelanggan.dashboard');

    // Tambahan rute untuk menu belanja
    Route::get('/pelanggan/belanja', [PelangganController::class, 'belanja'])->name('pelanggan.belanja');
});

// --- GRUP KERANJANG BELANJA (Di dalam middleware auth) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // TAMBAHKAN 2 ROUTE BARU INI:
    Route::get('/payment/success', [CartController::class, 'success'])->name('payment.success');
    Route::get('/purchase-history', [CartController::class, 'history'])->name('purchase.history');
});

// Rute API Webhook Midtrans (Tidak boleh dibungkus middleware auth!)
Route::post('/api/webhook/midtrans', [WebhookController::class, 'midtransHandler']);

// ==========================================
// RUTE MAKELAR AI KAMERA (Bypass CORS)
// ==========================================
Route::post('/api/scan-kamera', function (Request $request) {
    // Ambil gambar base64 yang dikirim dari browser Kasir
    $base64Image = $request->input('image');

    // Laravel yang akan menembak ke server Roboflow (Bebas dari hukum CORS browser)
    $response = Http::withBody($base64Image, 'application/x-www-form-urlencoded')
        ->post('https://serverless.roboflow.com/deteksi-mata-uang-rupiah-nerog/2?api_key=yTwDcOCBmI8RvoJ8rfu2');

    // Kembalikan jawaban Roboflow ke browser Kasir
    return $response->json();
})->name('api.scan-kamera');