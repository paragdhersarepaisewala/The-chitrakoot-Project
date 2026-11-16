<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    protected $primaryKey = 'setting_key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'setting_key',
        'setting_value',
    ];

    /**
     * @var array<string, mixed>|null
     */
    protected static ?array $cache = null;

    /**
     * Get a setting by key with fallback default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::$cache === null) {
            static::$cache = static::pluck('setting_value', 'setting_key')->toArray();
        }

        return static::$cache[$key] ?? $default;
    }

    /**
     * Set/persist a setting by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['setting_key' => $key],
            ['setting_value' => is_array($value) ? json_encode($value) : $value]
        );

        if (static::$cache !== null) {
            static::$cache[$key] = is_array($value) ? json_encode($value) : $value;
        }
    }

    /**
     * Retrieve all settings as key-value pairs.
     *
     * @return array<string, mixed>
     */
    public static function getAll(): array
    {
        if (static::$cache === null) {
            static::$cache = static::pluck('setting_value', 'setting_key')->toArray();
        }

        return static::$cache;
    }

    /**
     * Clear cached memory store.
     */
    public static function clearCache(): void
    {
        static::$cache = null;
    }
}
