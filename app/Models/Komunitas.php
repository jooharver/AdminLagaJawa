<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Komunitas extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'image',
        'image_logo',
        'image_banner',
        'phone',
        'deskripsi',
        'tanggal',
        'jadwal',
        'court',
    ];

    protected $casts = [
        'jadwal' => 'array',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/komunitas/image' . $image),
        );
    }

    protected function imageLogo(): Attribute
    {
        return Attribute::make(
            get: fn ($image_logo) => url('/storage/komunitas/image_logo' . $image_logo),
        );
    }

    protected function imageBanner(): Attribute
    {
        return Attribute::make(
            get: fn ($image_banner) => url('/storage/komunitas/image_banner' . $image_banner),
        );
    }

}
