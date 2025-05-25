<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Court;
use App\Models\Transaction;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'transaction_id',
        'court_id',
        'booking_date',
        'time_slots',
        'duration',
        'amount',
        'notes'
    ];

    protected $casts = [
        'time_slots' => 'array',
        'booking_date' => 'date',
    ];


    protected static function boot()
    {
        parent::boot();

        static::saving(function ($booking) {
            // Cek bentrok booking (exclude diri sendiri saat update)
            $existingBookings = Booking::where('booking_date', $booking->booking_date)
                ->where('court_id', $booking->court_id)
                ->where('id_booking', '<>', $booking->id_booking ?? 0)
                ->get();

            foreach ($existingBookings as $existing) {
                $overlap = array_intersect($booking->time_slots, $existing->time_slots);

                if (!empty($overlap)) {
                    throw new \Exception("Waktu " . implode(', ', $overlap) . " sudah dibooking pada tanggal dan court yang sama.");
                }
            }

            // Hitung duration & amount
            $duration = count($booking->time_slots);

            $court = \App\Models\Court::find($booking->court_id);
            if (!$court) {
                throw new \Exception("Court dengan ID {$booking->court_id} tidak ditemukan.");
            }

            $booking->duration = $duration;
            $booking->amount = $duration * $court->price_per_hour;
        });

        static::saved(function ($booking) {
            $booking->updateTransactionTotalAmount();
        });

        static::deleted(function ($booking) {
            $booking->updateTransactionTotalAmount();

            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'from' => json_encode($booking->getAttributes()),
                'to' => null,
            ]);
        });

        static::created(function ($booking) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'from' => null,
                'to' => json_encode($booking->getAttributes()),
            ]);
        });
    }


    public function updateTransactionTotalAmount()
    {
        if ($this->transaction_id) {
            $total = self::where('transaction_id', $this->transaction_id)->sum('amount');

            $transaction = $this->transaction;
            if ($transaction) {
                $transaction->total_amount = $total;
                $transaction->save();
            }
        }
    }

    // RELASI
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id_transaction');
    }

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id', 'id_court');
    }
}
