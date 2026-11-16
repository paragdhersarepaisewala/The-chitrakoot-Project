<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'city',
        'state',
        'occupation',
        'seva_areas',
        'availability',
        'experience_notes',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'seva_areas' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
