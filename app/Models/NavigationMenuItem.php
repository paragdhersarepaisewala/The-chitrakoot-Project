<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

class NavigationMenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'label_en',
        'label_hi',
        'url',
        'route_name',
        'target',
        'sort_order',
        'is_active',
        'location',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'parent_id' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavigationMenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavigationMenuItem::class, 'parent_id')->orderBy('sort_order', 'asc');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get resolved link URL
     */
    public function getResolvedUrlAttribute(): string
    {
        if (! empty($this->url) && (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://'))) {
            return $this->url;
        }

        if (! empty($this->url) && str_starts_with($this->url, '/')) {
            return url($this->url);
        }

        if (! empty($this->route_name) && Route::has($this->route_name)) {
            return route($this->route_name);
        }

        return ! empty($this->url) ? url($this->url) : '#';
    }

    /**
     * Get active menu tree for specified location.
     */
    public static function getTree(string $location = 'header')
    {
        return static::where('location', $location)
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order', 'asc');
            }])
            ->orderBy('sort_order', 'asc')
            ->get();
    }
}
