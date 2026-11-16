<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageSeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'page_title',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_image',
        'no_index',
        'structured_data',
    ];

    protected $casts = [
        'no_index' => 'boolean',
    ];

    public static function getForPage(string $key): ?self
    {
        return static::where('page_key', $key)->first();
    }

    public static function clearCache(): void
    {
        Cache::forget('page_seo_all');
    }
}
