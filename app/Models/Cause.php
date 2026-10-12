<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cause extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'target_amount',
        'minimum_amount',
        'image_path',
        'start_date',
        'end_date',
        'status',
        'allow_donations_after_target',
        'is_archived',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'datetime',
        'allow_donations_after_target' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function raisedAmount(): float
    {
        return (float) $this->donations()->where('status', 'verified')->sum('amount');
    }
}
