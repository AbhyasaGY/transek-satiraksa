<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function midtransHandler(Request $request)
    {
        // 1. Validasi Keamanan (Secure by Design: Mencegah Fake Webhook dari Hacker)
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            // Catat log jika ada percobaan peretasan
            Log::warning('Percobaan Webhook Palsu terdeteksi pada Order ID: ' . $request->order_id);
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        // 2. Cari transaksi di database berdasarkan Order ID dari Midtrans
        $transaction = Transaction::where('invoice_number', $request->order_id)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // 3. Tangkap status dari Midtrans
        $status = $request->transaction_status;

        // Jika Sukses dibayar
        if ($status == 'capture' || $status == 'settlement') {
            $transaction->update(['status' => 'Success']);

            if ($transaction->payment) {
                $transaction->payment->update(['payment_status' => 'Paid']);
            }
        }
        // Jika Gagal, Dibatalkan, atau Kadaluarsa
        elseif ($status == 'cancel' || $status == 'deny' || $status == 'expire') {
            // Hanya jalankan jika status sebelumnya belum Failed
            if ($transaction->status !== 'Failed') {
                $transaction->update(['status' => 'Failed']);

                if ($transaction->payment) {
                    $transaction->payment->update(['payment_status' => 'Failed']);
                }

                // INI SOLUSI MASALAH ANDA: RESTORE STOK (KEMBALIKAN BARANG)
                foreach ($transaction->transaction_details as $detail) {
                    $detail->product->increment('stock', $detail->quantity);
                }
            }
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
