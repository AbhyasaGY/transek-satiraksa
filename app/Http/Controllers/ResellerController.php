<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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

    // --- TAMBAHAN BARU: Upload Kontrak ---
    public function uploadContract(Request $request)
    {
        $request->validate([
            'contract_file' => 'required|mimes:pdf|max:2048'
        ]);

        // Beri tahu Intelephense bahwa ini adalah Model User sungguhan
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('contract_file')) {
            $path = $request->file('contract_file')->store('contracts', 'public');
            $user->contract_file = $path;
            $user->contract_status = 'Menunggu Validasi';
            $user->save();
        }

        return redirect()->back()->with('success', 'Dokumen kontrak berhasil diunggah! Mohon tunggu validasi dari Admin.');
    }

    public function belanja()
    {
        // --- KUNCI AKSES B2B ---
        if (Auth::user()->contract_status !== 'Disetujui') {
            return redirect()->route('reseller.dashboard')->with('error', 'Akses Ditolak: Anda harus mengunggah kontrak dan disetujui Admin sebelum dapat memesan stok grosir.');
        }

        $products = Product::where('stock', '>', 0)->get();
        return view('reseller.belanja', compact('products'));
    }
}