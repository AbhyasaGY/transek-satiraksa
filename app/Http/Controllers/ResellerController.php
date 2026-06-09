<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Product;
use App\Models\Contract; // Pastikan Model Contract sudah dibuat sesuai ERD
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    public function index()
    {
        // Ambil data kontrak milik reseller yang sedang login
        $contract = \App\Models\Contract::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();

        return view('reseller.dashboard', compact('contract'));
    }

    public function generateContract(Request $request)
    {
        $user = $request->user();
        $pdf = Pdf::loadView('reseller.contract_pdf', compact('user'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Kontrak_Kemitraan_' . str_replace(' ', '_', $user->name) . '.pdf');
    }

    // --- PEMBARUAN: Fungsi Upload Kontrak (Memuat Lapis 1) ---
    public function uploadContract(Request $request)
    {
        $request->validate([
            'contract_file' => 'required|mimes:pdf|max:2048'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('contract_file')) {
            $file = $request->file('contract_file');

            // Tangkap nama asli file fisik (misal: PT_Kain_Katun_Combed.DK01.pdf)
            $originalName = $file->getClientOriginalName();

            // LAPIS 1: Isolasi Penyimpanan & Obfuscation
            // Menghapus argumen 'public' agar tersimpan di storage/app/contracts/
            // Fungsi store() otomatis mengubah nama file fisik menjadi hash acak
            $hashedPath = $file->store('contracts');

            // Simpan ke tabel Contracts sesuai relasi ERD
            Contract::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'contract_number' => $originalName, // Rekam nama asli
                    'file_path' => $hashedPath,         // Rekam path hash
                    'status' => 'Pending',
                    'signed_date' => now()
                ]
            );

            // Tetap perbarui status di tabel User untuk sinkronisasi antarmuka
            $user->contract_status = 'Menunggu Validasi';
            $user->save();
        }

        return redirect()->back()->with('success', 'Dokumen kontrak berhasil diunggah secara aman! Mohon tunggu validasi dari Admin.');
    }

    // --- FUNGSI BARU: Fungsi Download Kontrak (Memuat Lapis 2 & Lapis 3) ---
    public function downloadContract($id)
    {
        $contract = Contract::findOrFail($id);

        // LAPIS 2: Kendali Akses Logis (Proteksi IDOR)
        // Blokir akses jika ID pengguna yang login tidak sama dengan pemilik kontrak
        if ($contract->user_id !== Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengunduh dokumen milik entitas lain.');
        }

        // Arahkan ke rute internal storage/app/private/ dengan nama file hash acak
        $filePath = storage_path('app/private/' . $contract->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Dokumen fisik tidak ditemukan di dalam peladen.');
        }

        // LAPIS 3: Pendistribusian via HTTP File Streaming
        // Mengembalikan nama asli file saat diunduh oleh peramban pengguna
        return response()->download($filePath, $contract->contract_number);
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