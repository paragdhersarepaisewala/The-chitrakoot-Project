<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadershipProfile extends Model
{
    /**
     * Supported social media platforms for leadership & patron profiles.
     *
     * @var array<string, array{label: string, placeholder: string}>
     */
    public const SOCIAL_PLATFORMS = [
        'youtube' => [
            'label' => 'YouTube',
            'placeholder' => 'https://www.youtube.com/@channel',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'placeholder' => 'https://www.facebook.com/page',
        ],
        'instagram' => [
            'label' => 'Instagram',
            'placeholder' => 'https://www.instagram.com/handle',
        ],
        'x' => [
            'label' => 'X (Twitter)',
            'placeholder' => 'https://x.com/handle',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp Channel / Contact',
            'placeholder' => 'https://whatsapp.com/channel/...',
        ],
        'telegram' => [
            'label' => 'Telegram',
            'placeholder' => 'https://t.me/channel',
        ],
        'website' => [
            'label' => 'Official Website / Blog',
            'placeholder' => 'https://example.com',
        ],
    ];

    protected $fillable = [
        'name_hi',
        'name_en',
        'title_hi',
        'title_en',
        'role_category',
        'photo_path',
        'bio_hi',
        'bio_en',
        'social_links',
        'youtube_playlist_title',
        'youtube_playlist_url',
        'is_youtube_playlist_enabled',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_youtube_playlist_enabled' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Return only enabled social links that have a non-empty URL.
     *
     * @return array<string, array{label: string, url: string}>
     */
    public function getEnabledSocialLinks(): array
    {
        $stored = is_array($this->social_links) ? $this->social_links : [];
        $enabled = [];

        foreach (self::SOCIAL_PLATFORMS as $key => $meta) {
            $entry = $stored[$key] ?? null;
            if (! is_array($entry)) {
                continue;
            }
            $isEnabled = (bool) ($entry['enabled'] ?? false);
            $url = trim((string) ($entry['url'] ?? ''));

            if ($isEnabled && $url !== '') {
                $enabled[$key] = [
                    'label' => $meta['label'],
                    'url' => $url,
                ];
            }
        }

        return $enabled;
    }

    /**
     * Extract a valid YouTube playlist ID from a URL or raw playlist ID.
     */
    public function getYoutubePlaylistId(): ?string
    {
        $raw = trim((string) $this->youtube_playlist_url);
        if ($raw === '') {
            return null;
        }

        if (preg_match('/[?&]list=([a-zA-Z0-9_-]+)/', $raw, $matches)) {
            return $matches[1];
        }

        if (preg_match('/^(PL|UU|OL|RD)[a-zA-Z0-9_-]{8,}$/', $raw)) {
            return $raw;
        }

        return null;
    }
}
