<?php

// namespace App\Http\Controllers\Api;

// //import model Payment
// use App\Models\Payment;

// use Illuminate\Http\Request;

// //import resource PaymentApiResource
// use App\Http\Controllers\Controller;

// //import Http request
// use App\Http\Resources\PaymentApiResource;

// //import facade Validator
// use Illuminate\Support\Facades\Validator;

// //import facade Storage
// use Illuminate\Support\Facades\Storage;

// class PaymentController extends Controller
// {
//     /**
//      * index
//      *
//      * @return void
//      */
//     public function index()
//     {
//         //get all payments
//         $payments = Payment::latest()->paginate(5);

//         //return collection of payments as a resource
//         return new PaymentApiResource(true, 'List Data Payments', $payments);
//     }

//     /**
//      * store
//      *
//      * @param  mixed $request
//      * @return void
//      */
//     public function store(Request $request)
//     {
//         // Validasi input
//         $validator = Validator::make($request->all(), [
//             'payment_method' => 'required|string',
//             'time_slots' => 'required|array|min:1',
//             'time_slots.*' => ['required', 'string', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
//         ]);

//         if ($validator->fails()) {
//             // Jika validasi gagal, kembalikan error dengan status 422
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Validasi gagal',
//                 'errors' => $validator->errors(),
//             ], 422);
//         }

//         // Hitung durasi dari jumlah time_slots (anggap 1 slot = 1 jam)
//         $duration = count($request->time_slots);

//         // Hitung total amount (misal 100.000 per jam)
//         $amount = $duration * 100000;

//         // Simpan data payment baru ke database
//         $payment = Payment::create([
//             'payment_status' => 'waiting',
//             'payment_method' => $request->payment_method,
//             'amount' => $amount,
//         ]);

//         // Kembalikan response success dengan resource payment
//         return new PaymentApiResource(true, 'Data Payment Berhasil Ditambahkan!', $payment);
//     }



//     /**
//      * show
//      *
//      * @param  mixed $id
//      * @return void
//      */
//     public function show($id)
//     {
//         //find payment by ID
//         $payment = Payment::find($id);

//         //return single payment as a resource
//         return new PaymentApiResource(true, 'Detail Data Payment!', $payment);
//     }

//     /**
//      * update
//      *
//      * @param  mixed $request
//      * @param  mixed $id
//      * @return void
//      */
//     public function update(Request $request, $id)
//     {
//         // Validate hanya payment_method yang wajib dan nilai harus valid
//         $validator = Validator::make($request->all(), [
//             'payment_method' => 'required|in:transfer,qris,cod',
//             // 'amount' dan 'payment_status' diabaikan / tidak wajib
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

//         $payment = Payment::find($id);
//         if (!$payment) {
//             return response()->json(['message' => 'Payment not found'], 404);
//         }

//         // Update hanya payment_method
//         $payment->update([
//             'payment_method' => $request->payment_method,
//             // Kalau mau update amount/status juga, bisa ditambahkan di sini,
//             // tapi sesuai skenario sekarang, kita hanya update payment_method
//         ]);

//         return new PaymentApiResource(true, 'Data Payment Berhasil Diubah!', $payment);
//     }

//     /**
//      * destroy
//      *
//      * @param  mixed $id
//      * @return void
//      */
//     public function destroy($id)
//     {

//         //find payment by ID
//         $payment = Payment::findOrFail($id);

//         //delete payment
//         $payment->delete();

//         //return response
//         return new PaymentApiResource(true, 'Data Payment Berhasil Dihapus!', null);
//     }
// }
