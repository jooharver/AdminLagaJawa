<?php
namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionApiResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'requester_id'  => 'required|integer|exists:users,id',
            'court_id'      => 'required|integer|exists:courts,id_court',
            'booking_date'  => 'required|date',
            'time_slots'    => 'required|array|min:1',
            'payment_method'=> 'required|string',
            'notes'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return new TransactionApiResource(false, 'Validasi gagal', $validator->errors());
        }

        // Mulai transaction DB
        DB::beginTransaction();

        try {
            // Hitung durasi (jumlah slot)
            $duration = count($request->time_slots);

            // Hitung total pembayaran
            $totalAmount = $duration * 100000;

            // Buat data payment dulu
            $payment = Payment::create([
                'payment_method' => $request->payment_method,
                'payment_status' => 'waiting',
                'amount' => $totalAmount,
            ]);

            // Cek bentrok booking
            $exists = Booking::where('court_id', $request->court_id)
                ->where('booking_date', $request->booking_date)
                ->whereJsonContains('time_slots', $request->time_slots)
                ->exists();

            if ($exists) {
                DB::rollBack();
                return new TransactionApiResource(false, 'Slot waktu sudah dipesan, silakan pilih slot lain.', null);
            }

            // Simpan booking
            $booking = Booking::create([
                'requester_id' => $request->requester_id,
                'court_id' => $request->court_id,
                'payment_id' => $payment->id_payment,
                'booking_date' => $request->booking_date,
                'time_slots' => $request->time_slots,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return new TransactionApiResource(true, 'Booking dan pembayaran berhasil.', [
                'payment' => $payment,
                'booking' => $booking,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return new TransactionApiResource(false, 'Terjadi kesalahan: ' . $e->getMessage(), null);
        }
    }
}
