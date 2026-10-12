<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = [
        'name_hi',
        'name_en',
        'slug',
        'tagline_hi',
        'tagline_en',
        'summary_hi',
        'summary_en',
        'description_hi',
        'description_en',
        'featured_image',
        'gallery_images',
        'map_url',
        'visitor_info',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];
}
