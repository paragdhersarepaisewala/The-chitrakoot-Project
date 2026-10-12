<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'membership_id',
        'status',
        'consecutive_qualifying_months',
        'last_qualifying_month',
        'became_member_at',
    ];

    protected $casts = [
        'became_member_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
