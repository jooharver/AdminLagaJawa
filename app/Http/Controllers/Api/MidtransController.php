<?php

namespace App\Http\Controllers\Api;

use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MidtransApiResource;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Set konfigurasi Midtrans setiap request masuk
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.sanitized', true);
        Config::$is3ds = config('midtrans.3ds', true);
    }

    /**
     * Contoh endpoint GET untuk cek semua transaksi (bisa dihapus kalau tidak perlu)
     */
    public function index()
    {
        $transactions = Transaction::latest()->get();

        return new MidtransApiResource(true, 'List Data Transaksi', $transactions);
    }

    /**
     * Webhook endpoint yang akan menerima notifikasi dari Midtrans
     */
    public function store(Request $request)
    {
        try {
            // Terima notification dari Midtrans
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status; // status transaksi Midtrans
            $orderId = $notif->order_id;                     // ini harus berisi no_pemesanan
            $fraudStatus = $notif->fraud_status ?? null;     // fraud status, opsional

            // Cari transaksi berdasarkan no_pemesanan (orderId)
            $transaction = Transaction::where('no_pemesanan', $orderId)->first();

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Update payment_status dan status berdasarkan transaction_status dan fraud_status Midtrans
            if ($transactionStatus === 'capture') {
                // Kalau ada fraud check
                if ($fraudStatus === 'challenge') {
                    $transaction->payment_status = 'waiting';  // status tunggu verifikasi fraud
                    $transaction->status = 'pending';
                } else if ($fraudStatus === 'accept') {
                    $transaction->payment_status = 'paid';
                    $transaction->status = 'confirmed';
                    $transaction->paid_at = now();
                }
            } elseif ($transactionStatus === 'settlement') {
                $transaction->payment_status = 'paid';
                $transaction->status = 'confirmed';
                $transaction->paid_at = now();
            } elseif ($transactionStatus === 'pending') {
                $transaction->payment_status = 'waiting';
                $transaction->status = 'pending';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $transaction->payment_status = 'failed';
                $transaction->status = 'cancelled';
            }

            $transaction->save();

            return new MidtransApiResource(true, 'Transaction status updated', $transaction);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error handling Midtrans notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
