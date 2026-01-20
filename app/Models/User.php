<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'password',
        'google_id',
        'avatar',
        'status',
        'mfa_enabled',
        'mfa_required',
        'password_changed_at',
        'password_history',
        'must_change_password',
        'last_login_at',
        'last_login_ip',
        'last_activity_at',
        'failed_login_attempts',
        'locked_until',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'password_history',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_changed_at' => 'datetime',
            'password_history' => 'array',
            'must_change_password' => 'boolean',
            'mfa_enabled' => 'boolean',
            'mfa_required' => 'boolean',
            'last_login_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'locked_until' => 'datetime',
            'failed_login_attempts' => 'integer',
        ];
    }

    /**
     * Get the login username to be used by the controller.
     */
    public function username(): string
    {
        return 'username';
    }

    /**
     * Get the MFA secret for the user.
     */
    public function mfaSecret(): HasOne
    {
        return $this->hasOne(MfaSecret::class);
    }

    /**
     * Get the trusted devices for the user.
     */
    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }

    /**
     * Get the audit logs for the user.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Get the user who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this user.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include suspended users.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Check if user account is locked.
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->isLocked();
    }

    /**
     * Check if password has expired.
     */
    public function isPasswordExpired(): bool
    {
        if (! $this->password_changed_at) {
            return true;
        }

        $expiryDays = config('security.password.expiry_days', 90);

        return $this->password_changed_at->addDays($expiryDays)->isPast();
    }

    /**
     * Check if MFA is required for this user.
     */
    public function requiresMfa(): bool
    {
        if ($this->mfa_required) {
            return true;
        }

        $requiredRoles = config('security.mfa.required_roles', []);

        return $this->hasAnyRole($requiredRoles);
    }

    /**
     * Check if MFA is configured and enabled.
     */
    public function hasMfaEnabled(): bool
    {
        return $this->mfa_enabled && $this->mfaSecret?->isConfigured();
    }

    /**
     * Increment failed login attempts.
     */
    public function incrementFailedAttempts(): void
    {
        $this->failed_login_attempts++;

        $maxAttempts = config('security.login.max_attempts', 5);
        $lockoutMinutes = config('security.login.lockout_minutes', 30);

        if ($this->failed_login_attempts >= $maxAttempts) {
            $this->locked_until = now()->addMinutes($lockoutMinutes);
        }

        $this->save();
    }

    /**
     * Reset failed login attempts.
     */
    public function resetFailedAttempts(): void
    {
        $this->failed_login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }

    /**
     * Record successful login.
     */
    public function recordLogin(string $ip): void
    {
        $this->last_login_at = now();
        $this->last_login_ip = $ip;
        $this->last_activity_at = now();
        $this->failed_login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }

    /**
     * Update last activity timestamp.
     */
    public function touchActivity(): void
    {
        $this->last_activity_at = now();
        $this->save();
    }

    /**
     * Check if password was used before.
     */
    public function wasPasswordUsed(string $password): bool
    {
        $history = $this->password_history ?? [];

        foreach ($history as $oldHash) {
            if (password_verify($password, $oldHash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add current password to history.
     */
    public function addPasswordToHistory(): void
    {
        $history = $this->password_history ?? [];
        $historyCount = config('security.password.history_count', 5);

        // Add current password hash to history
        array_unshift($history, $this->password);

        // Keep only the last N passwords
        $this->password_history = array_slice($history, 0, $historyCount);
        $this->save();
    }

    /**
     * Get status badge HTML.
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = config('security.user_status_badges', []);

        return $badges[$this->status] ?? '<span class="badge bg-secondary">'.ucfirst($this->status).'</span>';
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = config('security.user_status_labels', []);

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Check if user can login (active, not locked, not suspended).
     */
    public function canLogin(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->isLocked()) {
            return false;
        }

        return true;
    }

    /**
     * Get lockout remaining time in minutes.
     */
    public function getLockoutRemainingAttribute(): ?int
    {
        if (! $this->isLocked()) {
            return null;
        }

        return (int) now()->diffInMinutes($this->locked_until);
    }
}
