<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_hi',
        'name_en',
        'slug',
        'description',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category', 'slug');
    }

    public function getPostCountAttribute(): int
    {
        return Post::where('category', $this->slug)->count();
    }
}
