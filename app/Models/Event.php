<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title_hi',
        'title_en',
        'slug',
        'event_date',
        'event_end_date',
        'location_hi',
        'location_en',
        'summary_hi',
        'summary_en',
        'description_hi',
        'description_en',
        'featured_image',
        'registration_url',
        'is_published',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_end_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->startOfDay())
                     ->where('is_published', true)
                     ->orderBy('event_date', 'asc');
    }
}
