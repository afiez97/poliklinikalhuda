<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log an action.
     */
    public function log(
        string $action,
        ?string $description = null,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $metadata = null
    ): AuditLog {
        $user = Auth::user();

        return AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'action' => $action,
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model?->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }

    /**
     * Log a create action.
     */
    public function logCreate(Model $model, ?string $description = null): AuditLog
    {
        return $this->log(
            action: 'create',
            description: $description ?? 'Created '.class_basename($model),
            model: $model,
            newValues: $model->toArray()
        );
    }

    /**
     * Log an update action.
     */
    public function logUpdate(Model $model, array $oldValues, ?string $description = null): AuditLog
    {
        return $this->log(
            action: 'update',
            description: $description ?? 'Updated '.class_basename($model),
            model: $model,
            oldValues: $oldValues,
            newValues: $model->toArray()
        );
    }

    /**
     * Log a delete action.
     */
    public function logDelete(Model $model, ?string $description = null): AuditLog
    {
        return $this->log(
            action: 'delete',
            description: $description ?? 'Deleted '.class_basename($model),
            model: $model,
            oldValues: $model->toArray()
        );
    }

    /**
     * Log a login action.
     */
    public function logLogin(?string $username = null, bool $success = true): AuditLog
    {
        $user = Auth::user();

        return $this->log(
            action: $success ? 'login' : 'failed_login',
            description: $success
                ? 'User logged in successfully'
                : 'Failed login attempt for: '.($username ?? 'unknown'),
            metadata: [
                'username' => $username ?? $user?->username,
                'success' => $success,
            ]
        );
    }

    /**
     * Log a logout action.
     */
    public function logLogout(): AuditLog
    {
        return $this->log(
            action: 'logout',
            description: 'User logged out'
        );
    }

    /**
     * Log an export action.
     */
    public function logExport(
        string $type,
        string $format,
        int $recordCount,
        ?string $description = null
    ): AuditLog {
        return $this->log(
            action: 'export',
            description: $description ?? "Exported {$recordCount} {$type} records to {$format}",
            metadata: [
                'type' => $type,
                'format' => $format,
                'record_count' => $recordCount,
            ]
        );
    }

    /**
     * Log a view action for sensitive data.
     */
    public function logView(Model $model, ?string $description = null): AuditLog
    {
        return $this->log(
            action: 'view',
            description: $description ?? 'Viewed '.class_basename($model),
            model: $model
        );
    }

    /**
     * Log MFA enabled.
     */
    public function logMfaEnabled(): AuditLog
    {
        return $this->log(
            action: 'mfa_enabled',
            description: 'MFA enabled for user account'
        );
    }

    /**
     * Log MFA disabled.
     */
    public function logMfaDisabled(): AuditLog
    {
        return $this->log(
            action: 'mfa_disabled',
            description: 'MFA disabled for user account'
        );
    }

    /**
     * Log password reset.
     */
    public function logPasswordReset(?Model $user = null): AuditLog
    {
        return $this->log(
            action: 'password_reset',
            description: 'Password reset'.($user ? ' for user: '.$user->name : ''),
            model: $user
        );
    }

    /**
     * Log approval action.
     */
    public function logApprove(Model $model, ?string $description = null): AuditLog
    {
        return $this->log(
            action: 'approve',
            description: $description ?? 'Approved '.class_basename($model),
            model: $model
        );
    }

    /**
     * Log rejection action.
     */
    public function logReject(Model $model, ?string $reason = null): AuditLog
    {
        return $this->log(
            action: 'reject',
            description: 'Rejected '.class_basename($model),
            model: $model,
            metadata: ['reason' => $reason]
        );
    }

    /**
     * Get audit logs with filters.
     */
    public function getFiltered(array $filters = [], int $perPage = 15)
    {
        $query = AuditLog::with('user')->latest('created_at');

        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (! empty($filters['model_type'])) {
            $query->where('auditable_type', $filters['model_type']);
        }

        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['ip_address'])) {
            $query->where('ip_address', $filters['ip_address']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', "%{$search}%")
                    ->orWhere('user_name', 'LIKE', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get distinct actions for filter dropdown.
     */
    public function getDistinctActions(): array
    {
        return AuditLog::distinct()->pluck('action')->toArray();
    }

    /**
     * Get logs for a specific model.
     */
    public function getForModel(Model $model, int $limit = 50)
    {
        return AuditLog::with('user')
            ->forModel(get_class($model), $model->id)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent activity for a user.
     */
    public function getRecentForUser(int $userId, int $limit = 20)
    {
        return AuditLog::byUser($userId)
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get login history for a user.
     */
    public function getLoginHistory(int $userId, int $limit = 50)
    {
        return AuditLog::byUser($userId)
            ->whereIn('action', ['login', 'logout', 'failed_login'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get security alerts (failed logins, suspicious activity).
     */
    public function getSecurityAlerts(int $hours = 24)
    {
        return AuditLog::whereIn('action', ['failed_login', 'mfa_disabled', 'password_reset'])
            ->where('created_at', '>=', now()->subHours($hours))
            ->latest('created_at')
            ->get();
    }

    /**
     * Get activity statistics.
     */
    public function getStatistics(int $days = 7): array
    {
        $startDate = now()->subDays($days);

        return [
            'total_actions' => AuditLog::where('created_at', '>=', $startDate)->count(),
            'logins' => AuditLog::action('login')->where('created_at', '>=', $startDate)->count(),
            'failed_logins' => AuditLog::action('failed_login')->where('created_at', '>=', $startDate)->count(),
            'creates' => AuditLog::action('create')->where('created_at', '>=', $startDate)->count(),
            'updates' => AuditLog::action('update')->where('created_at', '>=', $startDate)->count(),
            'deletes' => AuditLog::action('delete')->where('created_at', '>=', $startDate)->count(),
            'exports' => AuditLog::action('export')->where('created_at', '>=', $startDate)->count(),
        ];
    }
}
