<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoojaBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'pooja_service_id',
        'ritual_name',
        'user_id',
        'devotee_name',
        'phone',
        'email',
        'gotra',
        'nakshatra',
        'sankalp_purpose',
        'booking_date',
        'time_slot',
        'prasad_address',
        'amount',
        'quotation_amount',
        'quotation_notes',
        'quotation_token',
        'quotation_expires_at',
        'quoted_at',
        'payment_status',
        'payment_id',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'amount' => 'decimal:2',
        'quotation_amount' => 'decimal:2',
        'quotation_expires_at' => 'datetime',
        'quoted_at' => 'datetime',
    ];

    public function service()
    {
        return $this->belongsTo(PoojaService::class, 'pooja_service_id');
    }

    public function pooja()
    {
        return $this->belongsTo(PoojaService::class, 'pooja_service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDevoteePhoneAttribute(): ?string
    {
        return $this->phone;
    }

    public function getDevoteeEmailAttribute(): ?string
    {
        return $this->email;
    }

    public function getBookingStatusAttribute(): string
    {
        return $this->status ?? 'confirmed';
    }

    public function getScheduledDateAttribute()
    {
        return $this->booking_date;
    }

    public function getScheduledTimeSlotAttribute(): ?string
    {
        return $this->time_slot;
    }

    public function getAmountPaidAttribute(): float
    {
        return (float) ($this->amount ?? 0);
    }

    public function getPrasadDeliveryAttribute(): bool
    {
        return ! empty($this->prasad_address);
    }

    public function getEffectiveRitualTitle(): string
    {
        return $this->ritual_name ?: ($this->service?->title_hi ?: ($this->service?->title_en ?: 'विशेष पूजा एवं हवन संकल्प'));
    }

    public function isQuotationExpired(): bool
    {
        if (! $this->quotation_expires_at) {
            return false;
        }

        return $this->quotation_expires_at->isPast();
    }

    public function isQuotationActive(): bool
    {
        return $this->status === 'quoted'
            && ! empty($this->quotation_amount)
            && $this->quotation_amount > 0
            && ! empty($this->quotation_token)
            && ! $this->isQuotationExpired();
    }

    public function getQuotationPaymentUrl(): string
    {
        if (empty($this->quotation_token)) {
            return '';
        }

        return route('pooja.quotation.pay', ['token' => $this->quotation_token]);
    }
}
