<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationSetting extends Model
{
    protected $fillable = [
        'org_name',
        'address',
        'phone',
        'email',
        'reg_number',
        'pan_number',
        'gst_number',
        'logo_path',
        'favicon_path',
        'authorized_signature_path',
        'receipt_header_title',
        'receipt_legal_note',
        'show_donation_totals',
        'razorpay_key_id',
        'razorpay_key_secret',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
    ];

    protected $casts = [
        'show_donation_totals' => 'boolean',
    ];

    public static function getSettings(): self
    {
        return static::firstOrCreate([], [
            'org_name' => 'Shri Kamadgiri Peetham Sewa Trust',
            'receipt_header_title' => 'Shri Kamadgiri Peetham Sewa Trust — Donation Receipt',
        ]);
    }
}
