<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Hitung total pendapatan (Hanya dari transaksi yang sukses)
        $totalRevenue = Transaction::where('status', 'Success')->sum('total_amount');

        // 2. Hitung jumlah total transaksi
        $totalTransactions = Transaction::count();

        // 3. AMBIL SEMUA TRANSAKSI + EAGER LOADING RELASI (User, Payment, Detail Item)
        // Ini memastikan data pembeli dan metode pembayaran langsung terbaca
        // Ambil semua transaksi + Eager Loading + batasi 10 per halaman
        $allTransactions = Transaction::with(['user', 'payment', 'transaction_details.product'])
                                        ->latest()
                                        ->paginate(10); // <-- UBAH DI SINI

        // 4. Cek produk yang stoknya menipis
        $lowStockProducts = Product::where('stock', '<', 10)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTransactions',
            'allTransactions', // Variabel diganti menjadi allTransactions
            'lowStockProducts'
        ));
    }

    // (Posisikan di bawah fungsi index milik Admin)

    // 1. Tampilkan Halaman Validasi
    public function validasiKontrak()
    {
        // Ambil reseller yang sudah upload kontrak (statusnya bukan 'Belum Upload')
        $resellers = \App\Models\User::where('role', 'Reseller')
                        ->where('contract_status', '!=', 'Belum Upload')
                        ->latest()
                        ->get();

        return view('admin.contracts', compact('resellers'));
    }

    // 2. Proses Persetujuan/Penolakan
    public function prosesValidasi(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->contract_status = $request->status; // Isinya 'Disetujui' atau 'Ditolak'
        $user->save();

        return redirect()->back()->with('success', 'Status kontrak Mitra ' . $user->name . ' berhasil diubah menjadi ' . $request->status);
    }
}