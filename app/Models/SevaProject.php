<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SevaProject extends Model
{
    protected $fillable = [
        'title_hi',
        'title_en',
        'slug',
        'category',
        'summary_hi',
        'summary_en',
        'description_hi',
        'description_en',
        'featured_image',
        'gallery_images',
        'cause_id',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }
}
