<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class MfaSecret extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'secret',
        'is_enabled',
        'recovery_codes',
        'recovery_codes_used',
        'enabled_at',
        'last_used_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_enabled' => 'boolean',
        'recovery_codes' => 'array',
        'recovery_codes_used' => 'integer',
        'enabled_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'secret',
        'recovery_codes',
    ];

    /**
     * Get the user that owns the MFA secret.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set the secret attribute (encrypted).
     */
    public function setSecretAttribute(?string $value): void
    {
        $this->attributes['secret'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Get the secret attribute (decrypted).
     */
    public function getSecretAttribute(?string $value): ?string
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    /**
     * Generate recovery codes.
     */
    public function generateRecoveryCodes(int $count = 10): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4))).'-'.strtoupper(bin2hex(random_bytes(4)));
        }

        $this->recovery_codes = array_map(fn ($code) => bcrypt($code), $codes);
        $this->recovery_codes_used = 0;
        $this->save();

        return $codes; // Return plain codes for display (only shown once)
    }

    /**
     * Verify a recovery code.
     */
    public function verifyRecoveryCode(string $code): bool
    {
        foreach ($this->recovery_codes ?? [] as $index => $hashedCode) {
            if (password_verify($code, $hashedCode)) {
                // Mark code as used by removing it
                $codes = $this->recovery_codes;
                unset($codes[$index]);
                $this->recovery_codes = array_values($codes);
                $this->recovery_codes_used++;
                $this->save();

                return true;
            }
        }

        return false;
    }

    /**
     * Get remaining recovery codes count.
     */
    public function getRemainingCodesCountAttribute(): int
    {
        return count($this->recovery_codes ?? []);
    }

    /**
     * Check if MFA is properly configured.
     */
    public function isConfigured(): bool
    {
        return $this->is_enabled && ! empty($this->secret);
    }

    /**
     * Enable MFA.
     */
    public function enable(): void
    {
        $this->is_enabled = true;
        $this->enabled_at = now();
        $this->save();
    }

    /**
     * Disable MFA.
     */
    public function disable(): void
    {
        $this->is_enabled = false;
        $this->secret = null;
        $this->recovery_codes = null;
        $this->save();
    }

    /**
     * Update last used timestamp.
     */
    public function touchLastUsed(): void
    {
        $this->last_used_at = now();
        $this->save();
    }
}
