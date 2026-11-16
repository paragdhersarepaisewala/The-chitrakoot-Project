<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

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
        'site_tagline',
        'meta_description',
        'default_og_image',
        'ga4_measurement_id',
        'ga4_custom_script',
        'ga4_report_url',
        'google_site_verification',
        'instagram_url',
        'facebook_url',
        'youtube_url',
        'whatsapp_url',
        'donation_preset_amounts',
        'show_donation_totals',
        'is_80g_enabled',
        'is_pooja_packages_enabled',
        'razorpay_key_id',
        'razorpay_key_secret',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
        'is_smtp_configured',
        'privacy_policy_content',
    ];

    protected $casts = [
        'show_donation_totals' => 'boolean',
        'is_80g_enabled' => 'boolean',
        'is_pooja_packages_enabled' => 'boolean',
        'is_smtp_configured' => 'boolean',
    ];

    protected $hidden = [
        'razorpay_key_secret',
        'smtp_password',
    ];

    public function getRazorpayKeySecretAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return $value;
        }
    }

    public function setRazorpayKeySecretAttribute(?string $value): void
    {
        $this->attributes['razorpay_key_secret'] = ! empty($value) ? Crypt::encryptString($value) : null;
    }

    public function getSmtpPasswordAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return $value;
        }
    }

    public function setSmtpPasswordAttribute(?string $value): void
    {
        $this->attributes['smtp_password'] = ! empty($value) ? Crypt::encryptString($value) : null;
    }

    public static function clearCache(): void
    {
        // Invalidate any cached organization settings
    }

    public static function getSettings(): self
    {
        return static::firstOrCreate([], [
            'org_name' => 'Shri Kamadgiri Peetham Sewa Trust',
            'receipt_header_title' => 'Shri Kamadgiri Peetham Sewa Trust — Donation Receipt',
        ]);
    }

    public function isSmtpConfigured(): bool
    {
        return (bool) ($this->is_smtp_configured && ! empty($this->smtp_host) && ! empty($this->smtp_username));
    }
}
