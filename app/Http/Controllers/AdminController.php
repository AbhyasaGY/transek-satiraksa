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

        // 3. Ambil 5 transaksi terakhir untuk tabel riwayat
        $recentTransactions = Transaction::latest()->take(5)->get();

        // 4. Cek produk yang stoknya menipis (misal kurang dari 10)
        $lowStockProducts = Product::where('stock', '<', 10)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTransactions',
            'recentTransactions',
            'lowStockProducts'
        ));
    }
}
