<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'subject',
        'content_html',
        'placeholders_hint',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    public function getCategoryLabel(): string
    {
        return match ($this->category) {
            'donations' => 'Donations & Receipts',
            'auth' => 'Accounts & Security',
            'services' => 'Pooja & Temple Services',
            'inquiries' => 'Inquiries & Contact',
            'volunteers' => 'Seva Dal & Volunteers',
            'newsletter' => 'Newsletter & Broadcast',
            default => 'General System',
        };
    }

    public function getCategoryColor(): string
    {
        return match ($this->category) {
            'donations' => '#8f1719', // Crimson
            'auth' => '#0284c7', // Sky Blue
            'services' => '#b45309', // Amber / Gold
            'inquiries' => '#0d9488', // Teal
            'volunteers' => '#16a34a', // Emerald Green
            'newsletter' => '#7c3aed', // Purple
            default => '#593a28',
        };
    }
}
