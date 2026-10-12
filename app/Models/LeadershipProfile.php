<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadershipProfile extends Model
{
    protected $fillable = [
        'name_hi',
        'name_en',
        'title_hi',
        'title_en',
        'role_category',
        'photo_path',
        'bio_hi',
        'bio_en',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
