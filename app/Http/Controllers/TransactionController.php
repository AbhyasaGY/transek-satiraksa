<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    // 1. Menampilkan Halaman Kasir (POS)
    public function index()
    {
        // Ambil produk yang stoknya masih ada
        $products = Product::where('stock', '>', 0)->get();
        return view('pos.index', compact('products'));
    }

    // 2. Memproses Checkout & Midtrans
    public function store(Request $request)
    {
        // Validasi input dari celah manipulasi (Secure by Design)
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:Uang Tunai,Digital',
            'amount_paid' => 'nullable|numeric' // Hanya diisi jika tunai
        ]);

        // Mulai transaksi Database (ACID Compliance)
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($request->product_id);

            // Cek ulang stok (mencegah Race Condition jika 2 kasir checkout bersamaan)
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Gagal! Stok tidak mencukupi.');
            }

            $subtotal = $product->price * $request->quantity;
            $invoice = 'INV-' . time() . '-' . rand(100, 999);

            // A. Simpan Log Transaksi
            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'user_id' => Auth::id(),
                'total_amount' => $subtotal,
                'status' => 'Pending',
            ]);

            // B. Simpan Detail Belanjaan
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);

            // C. Potong Stok Sementara
            $product->decrement('stock', $request->quantity);

            // D. Logika Pembayaran
            if ($request->payment_method == 'Uang Tunai') {
                $change = $request->amount_paid - $subtotal;
                if ($change < 0) {
                    throw new \Exception("Uang tunai kurang dari total belanja!");
                }

                Payment::create([
                    'transaction_id' => $transaction->id,
                    'payment_method' => 'Uang Tunai',
                    'amount_paid' => $request->amount_paid,
                    'change_amount' => $change,
                    'payment_status' => 'Paid'
                ]);
                $transaction->update(['status' => 'Success']);

                DB::commit(); // Simpan permanen ke database
                return back()->with('success', 'Transaksi Tunai Berhasil! Kembalian: Rp ' . number_format($change, 0, ',', '.'));

            } else {
                // LOGIKA DIGITAL / MIDTRANS
                Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
                Config::$is3ds = env('MIDTRANS_IS_3DS', true);

                $params = [
                    'transaction_details' => [
                        'order_id' => $invoice,
                        'gross_amount' => $subtotal,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ]
                ];

                // Minta token Snap ke server Midtrans
                $snapToken = Snap::getSnapToken($params);
                $transaction->update(['snap_token' => $snapToken]);

                Payment::create([
                    'transaction_id' => $transaction->id,
                    'payment_method' => 'Dana', // Default sementara, diupdate saat webhook
                    'amount_paid' => $subtotal,
                    'payment_status' => 'Unpaid'
                ]);

                DB::commit(); // Simpan permanen ke database

                // Arahkan ke halaman pop-up Midtrans
                return view('pos.checkout', compact('snapToken', 'transaction'));
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua kueri jika ada yang error
            return back()->with('error', 'Transaksi gagal: ' . $e->getMessage());
        }
    }
}
