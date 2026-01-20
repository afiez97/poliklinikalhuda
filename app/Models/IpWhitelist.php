<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpWhitelist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip_address',
        'cidr_range',
        'description',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this whitelist entry.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active entries.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if a given IP matches this whitelist entry.
     */
    public function matchesIp(string $ip): bool
    {
        // Exact match
        if ($this->ip_address === $ip) {
            return true;
        }

        // CIDR range match
        if ($this->cidr_range) {
            return $this->ipInCidr($ip, $this->ip_address.'/'.ltrim($this->cidr_range, '/'));
        }

        return false;
    }

    /**
     * Check if an IP is within a CIDR range.
     */
    protected function ipInCidr(string $ip, string $cidr): bool
    {
        [$subnet, $mask] = explode('/', $cidr);

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            // IPv4
            $subnet = ip2long($subnet);
            $ip = ip2long($ip);
            $mask = -1 << (32 - (int) $mask);

            return ($ip & $mask) === ($subnet & $mask);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            // IPv6 - simplified check
            $subnet = inet_pton($subnet);
            $ip = inet_pton($ip);
            $mask = (int) $mask;

            // Convert to binary and compare prefix
            $subnetBits = '';
            $ipBits = '';
            for ($i = 0; $i < 16; $i++) {
                $subnetBits .= str_pad(decbin(ord($subnet[$i])), 8, '0', STR_PAD_LEFT);
                $ipBits .= str_pad(decbin(ord($ip[$i])), 8, '0', STR_PAD_LEFT);
            }

            return substr($subnetBits, 0, $mask) === substr($ipBits, 0, $mask);
        }

        return false;
    }

    /**
     * Get full CIDR notation.
     */
    public function getFullCidrAttribute(): string
    {
        if ($this->cidr_range) {
            return $this->ip_address.'/'.ltrim($this->cidr_range, '/');
        }

        return $this->ip_address;
    }

    /**
     * Check if a given IP is whitelisted (static helper).
     */
    public static function isWhitelisted(string $ip): bool
    {
        $entries = static::active()->get();

        foreach ($entries as $entry) {
            if ($entry->matchesIp($ip)) {
                return true;
            }
        }

        return false;
    }
}
