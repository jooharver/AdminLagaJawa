<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use HasFactory;

    // Secara default Laravel pakai "id" sebagai primary key,
    // maka kita override dengan nama yang kamu pakai:
    protected $primaryKey = 'id_court';

    // Kalau kamu tidak pakai auto increment integer (tidak perlu diubah di sini)
    public $incrementing = true;

    protected $fillable = [
        'name',
        'type',
        'price_per_hour',
    ];

    // Relasi: satu lapangan bisa punya banyak booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'court_id', 'id_court');
    }
}
