<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Tampilkan Daftar Produk
    public function index()
    {
        // Ambil data produk, urutkan dari yang terbaru, batasi 10 per halaman
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // 2. Tampilkan Form Tambah Produk
    public function create()
    {
        return view('admin.products.create');
    }

    // 3. Simpan Produk Baru ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    // 4. Tampilkan Form Edit Produk
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // 5. Simpan Perubahan Produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Pengecualian SKU unique agar tidak error saat update produk yang sama
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Data produk berhasil diperbarui!');
    }

    // 6. Hapus Produk
    public function destroy(Product $product)
    {
        // Opsi aman: Pastikan produk tidak dihapus jika sudah ada di tabel transaksi
        // Namun untuk MVP, kita biarkan bisa dihapus langsung.
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}