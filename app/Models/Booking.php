<?php

namespace App\Models;

use App\Models\User;
use App\Models\Court;
use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'requester_id',
        'court_id',
        'payment_id',
        'no_pemesanan',
        'booking_date',
        'time_slots',
        'start_time',
        'end_time',
        'duration',
        'approval_status',
        'notes'
    ];

    protected $casts = [
        'time_slots' => 'array',
        'booking_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
    ];


    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($booking) {
        //     // Set requester_id jika belum ada
        //     if ($booking->requester_id == null) {
        //         $booking->requester_id = Auth::id();
        //     }

        //     // Urutkan time_slots dan hitung start_time, end_time, duration
        //     $slots = collect($booking->time_slots)->sort()->values();
        //     $booking->time_slots = $slots->toArray();
        //     $booking->start_time = $slots->first();

        //     // Hitung end_time = last slot + 1 jam
        //     $lastSlot = Carbon::createFromFormat('H:i:s', $slots->last());
        //     $booking->end_time = $lastSlot->addHour()->format('H:i:s');

        //     $booking->duration = $slots->count();

        //     // Buat no_pemesanan unik
        //     $booking->no_pemesanan = 'NTR-11' . now()->format('YmdHis') . mt_rand(1000, 9999);
        // });

        static::creating(function ($booking) {
            // 1. Set requester_id jika belum ada
            if ($booking->requester_id === null) {
                $booking->requester_id = Auth::id();
            }

            $duration = 0;

            // 2. Jika ada time_slots (array), urutkan dan hitung waktu & durasi dari situ
            if (is_array($booking->time_slots) && count($booking->time_slots) > 0) {
                $slots = collect($booking->time_slots)->sort()->values();
                $booking->time_slots = $slots->toArray();

                $booking->start_time = $slots->first();

                $lastSlot = Carbon::createFromFormat('H:i:s', $slots->last());
                $booking->end_time = $lastSlot->copy()->addHour()->format('H:i:s');

                $duration = $slots->count();
            } else {
                // 3. Jika tidak ada time_slots, hitung durasi dari start_time & end_time
                $start = Carbon::createFromFormat('H:i:s', $booking->start_time);
                $end = Carbon::createFromFormat('H:i:s', $booking->end_time);
                $duration = $end->diffInHours($start);
            }

            $booking->duration = $duration;

            // 4. Generate no_pemesanan unik
            $booking->no_pemesanan = 'NTR-11' . now()->format('YmdHis') . mt_rand(1000, 9999);

            // 5. Buat payment otomatis
            $payment = Payment::create([
                'amount' => $duration * 100000,
            ]);

            $booking->payment_id = $payment->id_payment;
        });

        static::created(function ($booking) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'create',
                'from' => null,
                'to' => json_encode($booking->getAttributes()),
            ]);
        });

        static::deleting(function ($booking) {
            if ($booking->payment) {
                $booking->payment->delete();
            }
        });

        static::deleted(function ($booking) {
            AdminActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'delete',
                'from' => json_encode($booking->getAttributes()),
                'to' => null,
            ]);
        });

        static::updating(function ($booking) {
            if (is_array($booking->time_slots)) {
                $slots = collect($booking->time_slots)->sort()->values();
                $booking->time_slots = $slots->toArray();
                $booking->start_time = $slots->first();

                $lastSlot = Carbon::createFromFormat('H:i:s', $slots->last());
                $booking->end_time = $lastSlot->addHour()->format('H:i:s');

                $booking->duration = $slots->count();
            }
        });
    }

    // RELASI
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id', 'id');
    }

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id', 'id_court');
    }

    public function payment()
    {
        return $this->belongsTo(\App\Models\Payment::class, 'payment_id', 'id_payment');
    }
}
