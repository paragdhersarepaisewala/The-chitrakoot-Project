<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_hi',
        'title_en',
        'slug',
        'description',
        'cover_image',
        'type',
        'sort_order',
        'is_published',
    ];

    public function items()
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order', 'asc');
    }
}
