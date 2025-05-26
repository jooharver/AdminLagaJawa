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

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? url('/storage/' . $this->image) : null;
    }

}
