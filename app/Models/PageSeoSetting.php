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

    /**
     * Get the full URL for the Open Graph / social share featured image.
     */
    public function getOgImageUrlAttribute(): ?string
    {
        if (empty($this->og_image)) {
            return null;
        }

        if (str_starts_with($this->og_image, 'http://') || str_starts_with($this->og_image, 'https://')) {
            return $this->og_image;
        }

        return asset(ltrim($this->og_image, '/'));
    }

    /**
     * Alias for featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        return $this->og_image_url;
    }

    /**
     * Check if a custom featured image is set for this page.
     */
    public function hasFeaturedImage(): bool
    {
        return ! empty($this->og_image);
    }

    public static function getForPage(string $key): ?self
    {
        return static::where('page_key', $key)->first();
    }

    public static function clearCache(): void
    {
        Cache::forget('page_seo_all');
    }
}
