<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
   public function midtransHandler(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            Log::warning('Percobaan Webhook Palsu terdeteksi pada Order ID: ' . $request->order_id);
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transaction = Transaction::where('invoice_number', $request->order_id)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // === LOGIKA BARU: DETEKSI METODE PEMBAYARAN ASLI MIDTRANS ===
        $paymentType = $request->payment_type;
        $realMethod = ucfirst(str_replace('_', ' ', $paymentType)); // Fallback default (cth: Bank transfer)

        if ($paymentType === 'bank_transfer') {
            if (isset($request->va_numbers[0]['bank'])) {
                $realMethod = 'VA ' . strtoupper($request->va_numbers[0]['bank']); // Cth: VA BCA, VA BNI
            } elseif (isset($request->permata_va_number)) {
                $realMethod = 'VA Permata';
            }
        } elseif ($paymentType === 'qris') {
            $realMethod = 'QRIS';
        } elseif ($paymentType === 'gopay') {
            $realMethod = 'GoPay';
        } elseif ($paymentType === 'shopeepay') {
            $realMethod = 'ShopeePay';
        }
        // ============================================================

        $status = $request->transaction_status;

        if ($status == 'capture' || $status == 'settlement') {
            $transaction->update(['status' => 'Success']);

            if ($transaction->payment) {
                $transaction->payment->update([
                    'payment_status' => 'Paid',
                    'payment_method' => $realMethod // <-- Simpan metode asli secara dinamis
                ]);
            }
        }
        elseif ($status == 'cancel' || $status == 'deny' || $status == 'expire') {
            if ($transaction->status !== 'Failed') {
                $transaction->update(['status' => 'Failed']);

                if ($transaction->payment) {
                    $transaction->payment->update([
                        'payment_status' => 'Failed',
                        'payment_method' => $realMethod // <-- Tetap update meskipun gagal
                    ]);
                }

                foreach ($transaction->transaction_details as $detail) {
                    $detail->product->increment('stock', $detail->quantity);
                }
            }
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
