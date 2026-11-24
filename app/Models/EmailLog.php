<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'template_slug',
        'recipient_email',
        'recipient_name',
        'subject',
        'status',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_slug', 'slug');
    }

    public function getStatusBadge(): array
    {
        return match ($this->status) {
            'sent' => [
                'bg' => '#dcfce7',
                'color' => '#15803d',
                'border' => '#86efac',
                'label' => 'Sent (Live)',
                'icon' => '✓',
            ],
            'simulated' => [
                'bg' => '#fef3c7',
                'color' => '#b45309',
                'border' => '#fde68a',
                'label' => 'Simulated (Testing)',
                'icon' => '⚡',
            ],
            'stopped' => [
                'bg' => '#fee2e2',
                'color' => '#b91c1c',
                'border' => '#fca5a5',
                'label' => 'Stopped (Killswitch)',
                'icon' => '⏸',
            ],
            'failed' => [
                'bg' => '#ffe4e6',
                'color' => '#be123c',
                'border' => '#fecdd3',
                'label' => 'Failed',
                'icon' => '✕',
            ],
            default => [
                'bg' => '#f3f4f6',
                'color' => '#374151',
                'border' => '#e5e7eb',
                'label' => ucfirst($this->status),
                'icon' => '•',
            ],
        };
    }
}
