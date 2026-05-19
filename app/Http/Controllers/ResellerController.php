<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ResellerController extends Controller
{
    // Menampilkan halaman dasbor reseller
    public function index()
    {
        return view('reseller.dashboard');
    }

    // Fungsi untuk generate dan download PDF
    // KITA TAMBAHKAN (Request $request) DI SINI
    public function generateContract(Request $request)
    {
        // Gunakan $request->user() agar ekstensi Intelephense di VS Code tidak bingung
        $user = $request->user();

        // Load tampilan 'reseller.contract_pdf' dan kirim data user ke dalamnya
        $pdf = Pdf::loadView('reseller.contract_pdf', compact('user'));

        // Atur ukuran kertas ke A4
        $pdf->setPaper('a4', 'portrait');

        // Unduh filenya dengan nama yang otomatis menyesuaikan nama user
        return $pdf->download('Kontrak_Kemitraan_' . str_replace(' ', '_', $user->name) . '.pdf');
    }
}