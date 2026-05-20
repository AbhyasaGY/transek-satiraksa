<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // 1. Menampilkan isi keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // 2. Menambah produk ke keranjang (Berdasarkan jumlah/qty)
    public function add(Request $request, $id)
    {
        // Validasi input: wajib diisi, angka, dan minimal 1
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($id);

        // Cek apakah jumlah pesanan melebihi stok
        if ($request->quantity > $product->stock) {
            return redirect()->back()->with('error', 'Gagal: Kuantitas melebihi stok yang tersedia!');
        }

        $cart = session()->get('cart', []);
        $user = Auth::user();

        // Cek Role: Jika Reseller, otomatis potong harga 20%
        $price = $user->role === 'Reseller' ? $product->price * 0.8 : $product->price;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $request->quantity; // Tambahkan sesuai qty yang dipilih
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $request->quantity, // Simpan qty awal
            ];
        }

        session()->put('cart', $cart);

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Berhasil menambahkan ' . $request->quantity . ' ' . $product->name . ' ke keranjang!');
    }

    // 3. Menghapus produk dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }

    // 4. Proses Checkout ke Midtrans
    public function checkout()
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->back()->with('error', 'Keranjang Anda kosong!');
        }

        $user = Auth::user();
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();
        try {
            // Buat Transaksi Utama
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'invoice_number' => 'INV-' . time() . '-' . rand(100, 999),
                'total_amount' => $totalAmount,
                'status' => 'Pending',
            ]);

            // Catat Detail Transaksi & Kurangi Stok
            foreach ($cart as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'], // <-- TAMBAHKAN BARIS INI
                ]);

                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }

            // Buat Catatan Pembayaran Awal
            // Buat Catatan Pembayaran Awal
            Payment::create([
                'transaction_id' => $transaction->id,
                'payment_status' => 'Unpaid',
                'payment_method' => 'Digital',
                'amount_paid' => $totalAmount, // <-- TAMBAHKAN BARIS INI
            ]);

            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->invoice_number,
                    'gross_amount' => $totalAmount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                ],
            ];

            // 1. DAPATKAN SNAP TOKEN (Bukan Redirect URL)
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            DB::commit();
            session()->forget('cart'); // Kosongkan keranjang setelah checkout

            // 2. TAMPILKAN HALAMAN PEMBAYARAN POP-UP
            return view('cart.payment', compact('snapToken', 'transaction'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }


    }

    // 5. Menampilkan Halaman Sukses Pembayaran
    public function success()
    {
        return view('cart.success');
    }

    // 6. Menampilkan Riwayat Pembelian Pengguna
    public function history()
    {
        // Ambil transaksi milik user yang sedang login beserta detail produk dan status bayarnya
        $transactions = Transaction::with(['transaction_details.product', 'payment'])
                                    ->where('user_id', Auth::id())
                                    ->latest()
                                    ->get();

        return view('cart.history', compact('transactions'));
    }
}