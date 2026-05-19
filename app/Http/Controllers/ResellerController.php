<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product; // <-- Tambahkan ini

class ResellerController extends Controller
{
    public function index()
    {
        return view('reseller.dashboard');
    }

    public function generateContract(Request $request)
    {
        $user = $request->user();
        $pdf = Pdf::loadView('reseller.contract_pdf', compact('user'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Kontrak_Kemitraan_' . str_replace(' ', '_', $user->name) . '.pdf');
    }

    // --- TAMBAHAN BARU: Fungsi Katalog Mitra ---
    public function belanja()
    {
        // Ambil produk yang stoknya masih ada
        $products = Product::where('stock', '>', 0)->get();
        return view('reseller.belanja', compact('products'));
    }
}
