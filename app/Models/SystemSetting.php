<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SystemSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
        'description',
        'is_public',
        'is_encrypted',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_public' => 'boolean',
        'is_encrypted' => 'boolean',
    ];

    /**
     * Cache key prefix.
     */
    protected const CACHE_PREFIX = 'system_settings_';

    /**
     * Cache TTL in seconds.
     */
    protected const CACHE_TTL = 3600; // 1 hour

    /**
     * Get the user who last updated this setting.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the typed value.
     */
    public function getTypedValueAttribute()
    {
        $value = $this->is_encrypted && $this->value
            ? Crypt::decryptString($this->value)
            : $this->value;

        return match ($this->type) {
            'integer' => (int) $value,
            'float' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Set the value with proper type handling.
     */
    public function setValueAttribute($value): void
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        } elseif (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } else {
            $value = (string) $value;
        }

        if ($this->is_encrypted) {
            $value = Crypt::encryptString($value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Get a setting value by group and key.
     */
    public static function getValue(string $group, string $key, $default = null)
    {
        $cacheKey = self::CACHE_PREFIX.$group.'_'.$key;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($group, $key, $default) {
            $setting = static::where('group', $group)->where('key', $key)->first();

            return $setting ? $setting->typed_value : $default;
        });
    }

    /**
     * Set a setting value by group and key.
     */
    public static function setValue(string $group, string $key, $value, ?int $userId = null): static
    {
        $setting = static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value, 'updated_by' => $userId]
        );

        // Clear cache
        Cache::forget(self::CACHE_PREFIX.$group.'_'.$key);
        Cache::forget(self::CACHE_PREFIX.'group_'.$group);

        return $setting;
    }

    /**
     * Get all settings for a group.
     */
    public static function getGroup(string $group): array
    {
        $cacheKey = self::CACHE_PREFIX.'group_'.$group;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($group) {
            return static::where('group', $group)
                ->get()
                ->mapWithKeys(fn ($setting) => [$setting->key => $setting->typed_value])
                ->toArray();
        });
    }

    /**
     * Get all public settings.
     */
    public static function getPublic(): array
    {
        $cacheKey = self::CACHE_PREFIX.'public';

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return static::where('is_public', true)
                ->get()
                ->groupBy('group')
                ->map(fn ($settings) => $settings->mapWithKeys(
                    fn ($setting) => [$setting->key => $setting->typed_value]
                ))
                ->toArray();
        });
    }

    /**
     * Clear all settings cache.
     */
    public static function clearCache(): void
    {
        $settings = static::all();

        foreach ($settings as $setting) {
            Cache::forget(self::CACHE_PREFIX.$setting->group.'_'.$setting->key);
        }

        $groups = $settings->pluck('group')->unique();
        foreach ($groups as $group) {
            Cache::forget(self::CACHE_PREFIX.'group_'.$group);
        }

        Cache::forget(self::CACHE_PREFIX.'public');
    }

    /**
     * Scope a query to a specific group.
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope a query to only public settings.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}
