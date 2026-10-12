<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'item_type',
        'media_path',
        'youtube_url',
        'caption_hi',
        'caption_en',
        'sort_order',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
