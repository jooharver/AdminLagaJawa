<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BookingApiResource;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::query();

        if ($request->has('booking_date')) {
            $query->where('booking_date', $request->booking_date);
        }

        $bookings = $query->orderBy('booking_date', 'desc')->get();

        return new BookingApiResource(true, 'List Data Booking', $bookings);
    }

    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return new BookingApiResource(true, 'Detail Data Booking', $booking);
    }

    public function store(Request $request)
    {
        $request->validate([
            'court_id' => 'required|integer|exists:courts,id_court',
            'booking_date' => 'required|date',
            'time_slots' => 'required|array',
            'time_slots.*' => 'date_format:H:i:s',
            'transaction_id' => 'required|exists:transactions,id_transaction',
            'notes' => 'nullable|string',
        ]);

        // Ambil semua booking untuk court & tanggal yang sama
        $existingBookings = Booking::where('court_id', $request->court_id)
            ->where('booking_date', $request->booking_date)
            ->get();

        foreach ($existingBookings as $existing) {
            $existingSlots = $existing->time_slots ?? [];

            // Cek apakah ada irisan slot yang sudah dibooking
            $conflict = array_intersect($request->time_slots, $existingSlots);

            if (count($conflict) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal booking. Slot berikut sudah dibooking: ' . implode(', ', $conflict),
                ], 409); // 409 = Conflict
            }
        }

        // Tidak ada konflik, lanjut simpan
        $booking = Booking::create([
            'court_id' => $request->court_id,
            'booking_date' => $request->booking_date,
            'time_slots' => $request->time_slots,
            'transaction_id' => $request->transaction_id,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking berhasil dibuat',
            'data' => $booking,
        ]);
    }
}
