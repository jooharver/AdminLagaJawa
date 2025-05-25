<?php
namespace App\Http\Controllers\Api;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionApiResource;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function index()
    {
        // Get all transactions
        $transactions = Transaction::latest()->paginate(5);

        // Return collection of transactions as a resource
        return new TransactionApiResource(true, 'List Data Transactions', $transactions);
    }

    public function show($id)
    {
        // Find transaction by ID
        $transaction = Transaction::find($id);

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
            'payment_method' => 'required|string',
            // Tambahkan validasi lain jika perlu
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


        // Buat transaksi baru, total_amount default 0, payment_status default 'waiting', status default 'pending'
        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'payment_method' => $request->payment_method,
            'total_amount' => 0,
            'payment_status' => 'waiting',
            'status' => 'pending',
            'no_pemesanan' => $randomCode,
        ]);

        return new TransactionApiResource(true, 'Transaction created successfully', $transaction);
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
