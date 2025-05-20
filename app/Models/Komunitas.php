<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Komunitas extends Model
{
    protected $primaryKey = 'id_komunitas';

    protected $fillable = [
        'name',
        'image',
        'image_logo',
        'phone',
        'deskripsi',
        'booking_date',
        'time_slots',
        'court',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/community/' . $image),
        );
    }
}
