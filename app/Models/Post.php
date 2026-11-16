<?php

namespace App\Models;

use App\Services\ShortcodeParser;
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
        'featured_image_caption',
        'featured_image_alt',
        'video_url',
        'tags',
        'focus_keyword',
        'seo_score',
        'is_hero',
        'author_name',
        'likes_count',
        'views_count',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_hero' => 'boolean',
        'seo_score' => 'integer',
        'published_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(PostComment::class)->where('is_approved', true)->latest();
    }

    public function getParsedContentAttribute(): string
    {
        return ShortcodeParser::parse($this->content_hi ?? '');
    }
}
