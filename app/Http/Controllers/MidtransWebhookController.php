<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Buat notifikasi dari payload Midtrans
        $notification = new Notification();

        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status;

        // Log untuk debugging
        Log::info("Webhook received", [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
            'fraud_status' => $fraudStatus,
        ]);

        // Temukan transaksi berdasarkan order_id (no_pemesanan)
        $transaction = Transaction::where('no_pemesanan', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status berdasarkan status dari Midtrans
        if ($transactionStatus == 'settlement') {
            $transaction->payment_status = 'paid';
            $transaction->status = 'confirmed';
        } elseif ($transactionStatus == 'pending') {
            $transaction->payment_status = 'waiting';
            $transaction->status = 'pending';
        } elseif (in_array($transactionStatus, ['expire', 'cancel'])) {
            $transaction->payment_status = 'failed';
            $transaction->status = 'cancelled';
        }

        $transaction->save();

        return response()->json(['message' => 'Notification handled'], 200);
    }
}
