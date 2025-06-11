<?php
namespace App\Http\Controllers\Api;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TransactionApiResource;

class TransactionController extends Controller
{
    public function index()
    {
        // Get all transactions
        $transactions = Transaction::latest()->paginate(1000);

        // Return collection of transactions as a resource
        return new TransactionApiResource(true, 'List Data Transactions', $transactions);
    }

    public function show($order_id)
    {
        // Find transaction by ID
        $transaction = Transaction::find($order_id);

        // Check if transaction exists
        if ($transaction) {
            return new TransactionApiResource(true, 'Transaction Found', $transaction);
        } else {
            return new TransactionApiResource(false, 'Transaction Not Found', null);
        }
    }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'payment_method' => 'required|string|in:transfer,cod',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first(),
            'data' => null
        ], 422);
    }

    // Generate no_pemesanan unik
    do {
        $randomCode = 'NTR-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 9));
        $exists = Transaction::where('no_pemesanan', $randomCode)->exists();
    } while ($exists);

    // Buat transaksi awal
    $transaction = Transaction::create([
        'user_id' => $request->user_id,
        'payment_method' => $request->payment_method,
        'total_amount' => 0, // default, akan diupdate setelah booking
        'payment_status' => 'waiting',
        'status' => 'pending',
        'no_pemesanan' => $randomCode,
    ]);

    return new TransactionApiResource(true, 'Transaction created', $transaction);
}


    public function generateSnapToken($id)
{
    $transaction = Transaction::findOrFail($id);

    if ($transaction->payment_method === 'cod') {
        return new TransactionApiResource(true, 'COD method. No snap needed.', [
            'transaction' => $transaction,
            'snap_token' => null,
        ]);
    }

    if ($transaction->total_amount < 1) {
        return response()->json([
            'success' => false,
            'message' => 'Total amount must be at least 0.01 to generate snap token.',
            'data' => null,
        ], 400);
    }

    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => $transaction->no_pemesanan,
            'gross_amount' => $transaction->total_amount,
        ],
        'customer_details' => [
            'first_name' => 'User',
            'email' => 'user@example.com',
        ],
        'callbacks' => [
            'finish' => 'http://localhost:3000/pembayaran/status?order_id=' . $transaction->no_pemesanan,
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);
        $transaction->snap_token = $snapToken;
        $transaction->save();

        return new TransactionApiResource(true, 'Snap token generated', [
            'transaction' => $transaction,
            'snap_token' => $snapToken,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to generate snap token: ' . $e->getMessage(),
            'data' => null,
        ], 500);
    }
}




    public function getByOrderId($orderId)
{
    $transaction = Transaction::with(['user', 'bookings.court']) 
        ->where('no_pemesanan', $orderId)
        ->first();

    if (!$transaction) {
        return response()->json([
            'success' => false,
            'message' => 'Transaction not found',
            'data' => null,
        ], 404);
    }

    return new TransactionApiResource(true, 'Transaction found', $transaction);
}



    public function destroy($id)
    {
        $transaction = Transaction::where('id_transaction', $id)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }


}
