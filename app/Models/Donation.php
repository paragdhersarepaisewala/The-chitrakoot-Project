<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'cause_id',
        'donation_type',
        'amount',
        'currency',
        'payment_gateway',
        'gateway_order_id',
        'gateway_payment_id',
        'gateway_signature',
        'status',
        'receipt_number',
        'qualifying_year_month',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cause()
    {
        return $this->belongsTo(Cause::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}
