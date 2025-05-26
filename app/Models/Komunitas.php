<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Komunitas extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'user_id',
        'image',
        'image_logo',
        'image_banner',
        'phone',
        'deskripsi',
    ];

    protected $casts = [
        'jadwal' => 'array',
    ];

// Tetap menyimpan path asli
public function getImageLogoUrlAttribute(): ?string
{
    return $this->image_logo ? url('/storage/' . $this->image_logo) : null;
}

public function getImageBannerUrlAttribute(): ?string
{
    return $this->image_banner ? url('/storage/' . $this->image_banner) : null;
}

public function getImageUrlAttribute(): ?string
{
    return $this->image ? url('/storage/' . $this->image) : null;
}


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($komunitas) {
            if ($komunitas->image && Storage::exists($komunitas->image)) {
                Storage::delete($komunitas->image);
            }

            if ($komunitas->image_logo && Storage::exists($komunitas->image_logo)) {
                Storage::delete($komunitas->image_logo);
            }

            if ($komunitas->image_banner && Storage::exists($komunitas->image_banner)) {
                Storage::delete($komunitas->image_banner);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
