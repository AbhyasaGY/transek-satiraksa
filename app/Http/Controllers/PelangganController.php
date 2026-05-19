<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Memanggil model Product

class PelangganController extends Controller
{
    // Menampilkan halaman dasbor utama pelanggan
    public function dashboard()
    {
        return view('pelanggan.dashboard');
    }

    // Menampilkan halaman katalog belanja
    public function belanja()
    {
        // Ambil semua produk yang stoknya masih tersedia (lebih dari 0)
        $products = Product::where('stock', '>', 0)->get();

        return view('pelanggan.belanja', compact('products'));
    }
}
