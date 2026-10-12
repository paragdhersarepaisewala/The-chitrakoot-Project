<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupporterProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'father_name',
        'date_of_birth',
        'gender',
        'occupation',
        'address',
        'city',
        'district',
        'state',
        'pincode',
        'profile_photo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
