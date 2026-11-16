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
        'target_amount',
        'summary_hi',
        'summary_en',
        'description_hi',
        'description_en',
        'featured_image',
        'gallery_images',
        'cause_id',
        'sort_order',
        'is_published',
        'is_featured',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'gallery_images' => 'array',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'cause_id', 'cause_id');
    }

    public function getTitleAttribute(): string
    {
        if ($this->slug === 'sanskrit-gurukul') {
            return 'ऋषिकुल संस्कृत गुरुकुल एवं विद्यालय';
        }

        return $this->title_hi ?: ($this->title_en ?? '');
    }

    public function getDescriptionAttribute(): string
    {
        return $this->description_hi ?: ($this->description_en ?? ($this->summary_hi ?? ''));
    }

    public function getTargetAmountAttribute($value): float
    {
        if (! empty($value) && (float) $value > 0) {
            return (float) $value;
        }

        return (float) ($this->cause?->target_amount ?? 500000);
    }

    public function getRaisedAmountAttribute(): float
    {
        if ($this->cause_id) {
            return (float) Donation::where('cause_id', $this->cause_id)->where('status', 'verified')->sum('amount');
        }

        return 0.0;
    }

    public function getDonorsCountAttribute(): int
    {
        if ($this->cause_id) {
            return (int) Donation::where('cause_id', $this->cause_id)->where('status', 'verified')->count();
        }

        return 0;
    }

    public function getProgressPercentAttribute(): int
    {
        $target = $this->target_amount;
        if ($target <= 0) {
            return 0;
        }

        return min(100, (int) round(($this->raised_amount / $target) * 100));
    }
}
