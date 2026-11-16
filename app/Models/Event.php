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
        'is_archived',
        'archived_at',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'event_end_date' => 'datetime',
        'is_published' => 'boolean',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
    ];

    /**
     * Get effective end date for expiration calculation
     */
    public function getEffectiveEndDateAttribute()
    {
        return $this->event_end_date ?? $this->event_date;
    }

    /**
     * Check if event has already passed
     */
    public function isExpired(): bool
    {
        $endDate = $this->effective_end_date;

        return $endDate ? $endDate->isPast() : false;
    }

    /**
     * Mark event as archived
     */
    public function archive(): bool
    {
        return $this->update([
            'is_archived' => true,
            'archived_at' => now(),
        ]);
    }

    /**
     * Unarchive / Restore event
     */
    public function unarchive(): bool
    {
        return $this->update([
            'is_archived' => false,
            'archived_at' => null,
        ]);
    }

    /**
     * Scan and automatically expire past events into archive
     */
    public static function autoArchiveExpired(): int
    {
        $now = now();
        $expiredQuery = static::where('is_archived', false)
            ->where(function ($q) use ($now) {
                $q->where(function ($sub) use ($now) {
                    $sub->whereNotNull('event_end_date')
                        ->where('event_end_date', '<', $now);
                })->orWhere(function ($sub) use ($now) {
                    $sub->whereNull('event_end_date')
                        ->where('event_date', '<', $now->copy()->startOfDay());
                });
            });

        $count = (clone $expiredQuery)->count();
        if ($count > 0) {
            $expiredQuery->update([
                'is_archived' => true,
                'archived_at' => $now,
            ]);
        }

        return $count;
    }

    /**
     * Scope for upcoming non-expired events
     */
    public function scopeUpcoming($query)
    {
        $now = now();

        return $query->where('is_published', true)
            ->where('is_archived', false)
            ->where(function ($q) use ($now) {
                $q->where(function ($sub) use ($now) {
                    $sub->whereNotNull('event_end_date')
                        ->where('event_end_date', '>=', $now);
                })->orWhere(function ($sub) use ($now) {
                    $sub->whereNull('event_end_date')
                        ->where('event_date', '>=', $now->copy()->startOfDay());
                });
            })
            ->orderBy('event_date', 'asc');
    }

    /**
     * Scope for past or explicitly archived events
     */
    public function scopeArchived($query)
    {
        $now = now();

        return $query->where('is_published', true)
            ->where(function ($q) use ($now) {
                $q->where('is_archived', true)
                    ->orWhere(function ($sub) use ($now) {
                        $sub->whereNotNull('event_end_date')
                            ->where('event_end_date', '<', $now);
                    })
                    ->orWhere(function ($sub) use ($now) {
                        $sub->whereNull('event_end_date')
                            ->where('event_date', '<', $now->copy()->startOfDay());
                    });
            })
            ->orderBy('event_date', 'desc');
    }
}
