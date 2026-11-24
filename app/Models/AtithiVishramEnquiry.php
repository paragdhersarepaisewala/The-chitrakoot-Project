<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AtithiVishramEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_number',
        'user_id',
        'devotee_name',
        'phone',
        'email',
        'city',
        'state',
        'check_in_date',
        'check_out_date',
        'number_of_devotees',
        'number_of_rooms',
        'room_preference',
        'purpose_of_stay',
        'id_proof_type',
        'special_requests',
        'status',
        'is_read',
        'admin_notes',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'number_of_devotees' => 'integer',
        'number_of_rooms' => 'integer',
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateEnquiryNumber(): string
    {
        do {
            $number = 'AVE-'.date('Ymd').'-'.strtoupper(Str::random(4));
        } while (static::where('enquiry_number', $number)->exists());

        return $number;
    }

    public function getPurposeLabel(): string
    {
        return match ($this->purpose_of_stay) {
            'darshan_parikrama' => 'कामतानाथ दर्शन एवं परिक्रमा (Darshan & Parikrama)',
            'sadhana_meditation' => 'साधना एवं मौन जप (Sadhana & Meditation)',
            'seva_volunteer' => 'ट्रस्ट आश्रम सेवा (Ashram Seva / Volunteering)',
            'family_pilgrimage' => 'सपरिवार तीर्थ यात्रा (Family Pilgrimage)',
            'senior_citizen_yatra' => 'वरिष्ठ नागरिक यात्रा (Senior Citizen Yatra)',
            default => ucfirst(str_replace('_', ' ', $this->purpose_of_stay ?? 'सामान्य तीर्थ दर्शन')),
        };
    }

    public function getRoomPreferenceLabel(): string
    {
        return match ($this->room_preference) {
            'standard' => 'साधारण कक्ष (Standard Non-AC Room)',
            'ac_room' => 'वातानुकूलित कक्ष (AC Room)',
            'family_suite' => 'पारिवारिक कक्ष (Family Suite)',
            'dormitory' => 'सामूहिक विश्राम गृह (Devotee Dormitory)',
            default => ucfirst(str_replace('_', ' ', $this->room_preference ?? 'Standard Room')),
        };
    }

    public function getStatusBadge(): array
    {
        return match ($this->status) {
            'new' => ['label' => 'New / नया', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'in_review' => ['label' => 'In Review / विचाराधीन', 'class' => 'bg-blue-100 text-blue-800 border-blue-300'],
            'confirmed' => ['label' => 'Confirmed / स्वीकृत', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'cancelled' => ['label' => 'Cancelled / निरस्त', 'class' => 'bg-rose-100 text-rose-800 border-rose-300'],
            'completed' => ['label' => 'Completed / संपन्न', 'class' => 'bg-slate-100 text-slate-800 border-slate-300'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }
}
