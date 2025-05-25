<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'id_transaction';

    protected $fillable = [
        'user_id',
        'no_pemesanan',
        'payment_method',
        'total_amount',
        'payment_status',
        'paid_at',
        'status',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($transaction) {
            // Hapus semua booking terkait sebelum hapus transaction
            $transaction->bookings()->each(function ($booking) {
                $booking->delete();
            });
        });
    }


    public function bookings()
    {
        return $this->hasMany(Booking::class, 'transaction_id', 'id_transaction');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
