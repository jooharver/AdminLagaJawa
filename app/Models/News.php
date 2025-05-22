<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class News extends Model
{
    protected $primaryKey = 'id_news';

    protected $fillable = [
        'judul',
        'sub_judul',
        'tempat',
        'tanggal',
        'image',
        'deskripsi',
        'kategori',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($image) => url('/storage/news/' . $image),
        );
    }

}
