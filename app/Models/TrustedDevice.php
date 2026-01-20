<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrustedDevice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'device_hash',
        'device_name',
        'user_agent',
        'ip_address',
        'browser',
        'platform',
        'is_active',
        'last_used_at',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the trusted device.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active devices.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope a query to only include expired devices.
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Check if device is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if device is valid (active and not expired).
     */
    public function isValid(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    /**
     * Touch last used timestamp.
     */
    public function touchLastUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Extend device trust period.
     */
    public function extend(int $days = 30): void
    {
        $this->update(['expires_at' => now()->addDays($days)]);
    }

    /**
     * Revoke device trust.
     */
    public function revoke(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Generate device hash from request.
     */
    public static function generateHash(string $userAgent, string $ip): string
    {
        return hash('sha256', $userAgent.$ip.config('app.key'));
    }

    /**
     * Find or create a trusted device for user.
     */
    public static function trustDevice(
        int $userId,
        string $userAgent,
        string $ip,
        ?string $deviceName = null,
        int $trustDays = 30
    ): static {
        $hash = static::generateHash($userAgent, $ip);

        // Parse user agent for browser and platform
        $browser = static::parseBrowser($userAgent);
        $platform = static::parsePlatform($userAgent);

        return static::updateOrCreate(
            ['user_id' => $userId, 'device_hash' => $hash],
            [
                'device_name' => $deviceName ?? ($browser.' on '.$platform),
                'user_agent' => $userAgent,
                'ip_address' => $ip,
                'browser' => $browser,
                'platform' => $platform,
                'is_active' => true,
                'last_used_at' => now(),
                'expires_at' => now()->addDays($trustDays),
            ]
        );
    }

    /**
     * Check if device is trusted for user.
     */
    public static function isTrusted(int $userId, string $userAgent, string $ip): bool
    {
        $hash = static::generateHash($userAgent, $ip);

        return static::where('user_id', $userId)
            ->where('device_hash', $hash)
            ->active()
            ->exists();
    }

    /**
     * Parse browser from user agent.
     */
    protected static function parseBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        }
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome';
        }
        if (str_contains($userAgent, 'Safari')) {
            return 'Safari';
        }
        if (str_contains($userAgent, 'Edge')) {
            return 'Edge';
        }
        if (str_contains($userAgent, 'Opera')) {
            return 'Opera';
        }

        return 'Unknown Browser';
    }

    /**
     * Parse platform from user agent.
     */
    protected static function parsePlatform(string $userAgent): string
    {
        if (str_contains($userAgent, 'Windows')) {
            return 'Windows';
        }
        if (str_contains($userAgent, 'Mac')) {
            return 'macOS';
        }
        if (str_contains($userAgent, 'Linux')) {
            return 'Linux';
        }
        if (str_contains($userAgent, 'Android')) {
            return 'Android';
        }
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            return 'iOS';
        }

        return 'Unknown Platform';
    }
}
