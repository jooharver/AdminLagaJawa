<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_payment';

    protected $fillable = [
        'payment_status',
        'payment_method',
        'paid_at',
        'amount',
    ];

    protected static function booted()
    {


        static::updating(function ($payment) {
            if ($payment->isDirty('payment_status')) {
                $payment->paid_at = $payment->payment_status === 'paid' ? now() : null;
            }
        });
    }

public function booking()
{
    return $this->hasOne(Booking::class, 'payment_id', 'id_payment');
}

}
