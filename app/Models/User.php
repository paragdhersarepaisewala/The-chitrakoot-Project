<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'approval_status',
        'is_active',
        'email_otp',
        'email_otp_expires_at',
        'declaration_accepted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'declaration_accepted_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function profile()
    {
        return $this->hasOne(SupporterProfile::class);
    }

    public function membership()
    {
        return $this->hasOne(Membership::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function hasRole(string ...$roles): bool
    {
        if ($this->role === 'super_admin') {
            return true;
        }
        return in_array($this->role, $roles, true);
    }
}
