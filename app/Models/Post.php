<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_hi',
        'title_en',
        'slug',
        'category',
        'excerpt',
        'content_hi',
        'content_en',
        'featured_image',
        'author_name',
        'likes_count',
        'views_count',
        'status',
    ];

    public function comments()
    {
        return $this->hasMany(PostComment::class)->where('is_approved', true)->latest();
    }
}
