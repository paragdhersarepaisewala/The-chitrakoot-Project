<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoojaService extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_hi',
        'title_en',
        'slug',
        'service_type',
        'summary_hi',
        'description_hi',
        'suggested_donation_amount',
        'duration_minutes',
        'timings_display',
        'benefits_hi',
        'samagri_included',
        'priest_assigned',
        'featured_image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'suggested_donation_amount' => 'decimal:2',
        'duration_minutes' => 'integer',
        'benefits_hi' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'name',
        'price',
        'price_inr',
        'image_url',
        'benefits',
        'description',
        'tagline',
        'duration',
        'priest_assigned',
        'available_timings',
    ];

    public function bookings()
    {
        return $this->hasMany(PoojaBooking::class);
    }

    public function getNameAttribute(): string
    {
        return $this->title_hi ?: ($this->title_en ?? '');
    }

    public function getPriceAttribute(): float
    {
        return (float) ($this->suggested_donation_amount ?? 1100);
    }

    public function getPriceInrAttribute(): float
    {
        return (float) ($this->suggested_donation_amount ?? 1100);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->featured_image;
    }

    public function getBenefitsAttribute(): array
    {
        return is_array($this->benefits_hi) ? $this->benefits_hi : [];
    }

    public function getDescriptionAttribute(): string
    {
        return $this->description_hi ?: ($this->summary_hi ?? '');
    }

    public function getTaglineAttribute(): ?string
    {
        return $this->title_en ?: ($this->summary_hi ?? '');
    }

    public function getDurationAttribute(): string
    {
        return $this->duration_minutes ? "{$this->duration_minutes} Mins" : '45 Mins';
    }

    public function getPriestAssignedAttribute(): string
    {
        return 'Vedic Acharya';
    }

    public function getAvailableTimingsAttribute(): array
    {
        if (! empty($this->timings_display)) {
            $parts = preg_split('/[\r\n,]+/', $this->timings_display);

            return array_values(array_filter(array_map('trim', $parts)));
        }

        return ['प्रातः 07:00 AM', 'सायं 06:30 PM'];
    }

    public function getIncludesAttribute(): array
    {
        return [
            'वैदिक मंत्रोच्चार एवं संकल्प',
            'यज्ञ आहुति एवं अभिषेक',
            'पावन रक्षासूत्र एवं प्रसाद',
        ];
    }
}
