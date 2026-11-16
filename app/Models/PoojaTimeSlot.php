<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoojaTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_name',
        'start_time',
        'end_time',
        'service_type',
        'max_capacity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_capacity' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'formatted_time',
        'formatted_range',
    ];

    public function getFormattedTimeAttribute(): string
    {
        try {
            return Carbon::createFromFormat('H:i', $this->start_time)->format('h:i A');
        } catch (\Throwable) {
            return $this->start_time;
        }
    }

    public function getFormattedRangeAttribute(): string
    {
        try {
            $start = Carbon::createFromFormat('H:i', $this->start_time)->format('h:i A');
            $end = Carbon::createFromFormat('H:i', $this->end_time)->format('h:i A');

            return "{$start} - {$end}";
        } catch (\Throwable) {
            return "{$this->start_time} - {$this->end_time}";
        }
    }

    /**
     * Check if this slot for the given date is at least 24 hours in advance.
     * Devotees can only book >= 24 hours before slot start time.
     */
    public function isSlotAvailableForDate(string|Carbon $date): bool
    {
        if (! $this->is_active) {
            return false;
        }

        try {
            $dateStr = $date instanceof Carbon ? $date->format('Y-m-d') : Carbon::parse($date)->format('Y-m-d');
            $slotDateTime = Carbon::parse("{$dateStr} {$this->start_time}:00");

            // Must be at least 24 hours in the future from current instant
            return $slotDateTime->diffInSeconds(now(), false) <= -86400;
        } catch (\Throwable) {
            return false;
        }
    }
}
