<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Create a new repository instance.
     */
    public function __construct(
        protected User $model
    ) {}

    /**
     * Get all users.
     */
    public function all(): Collection
    {
        return $this->model->with('roles')->get();
    }

    /**
     * Get paginated users.
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('roles')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Search users.
     */
    public function search(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('roles')
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find user by ID.
     */
    public function findById(int $id): ?User
    {
        return $this->model->with(['roles', 'mfaSecret'])->find($id);
    }

    /**
     * Find user by username.
     */
    public function findByUsername(string $username): ?User
    {
        return $this->model->where('username', $username)->first();
    }

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update a user.
     */
    public function update(int $id, array $data): bool
    {
        $user = $this->findById($id);

        if (! $user) {
            return false;
        }

        return $user->update($data);
    }

    /**
     * Delete a user (soft delete).
     */
    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        if (! $user) {
            return false;
        }

        return $user->delete();
    }

    /**
     * Restore a soft-deleted user.
     */
    public function restore(int $id): bool
    {
        $user = $this->model->withTrashed()->find($id);

        if (! $user) {
            return false;
        }

        return $user->restore();
    }

    /**
     * Force delete a user.
     */
    public function forceDelete(int $id): bool
    {
        $user = $this->model->withTrashed()->find($id);

        if (! $user) {
            return false;
        }

        return $user->forceDelete();
    }

    /**
     * Get active users.
     */
    public function getActive(): Collection
    {
        return $this->model->active()->with('roles')->get();
    }

    /**
     * Get users by status.
     */
    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->with('roles')->get();
    }

    /**
     * Get users by role.
     */
    public function getByRole(string $roleName): Collection
    {
        return $this->model->role($roleName)->with('roles')->get();
    }

    /**
     * Get users with trashed.
     */
    public function getAllWithTrashed(): Collection
    {
        return $this->model->withTrashed()->with('roles')->get();
    }

    /**
     * Get only trashed users.
     */
    public function getTrashed(): Collection
    {
        return $this->model->onlyTrashed()->with('roles')->get();
    }

    /**
     * Get users count.
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Get active users count.
     */
    public function countActive(): int
    {
        return $this->model->active()->count();
    }

    /**
     * Get users count by status.
     */
    public function countByStatus(): array
    {
        return $this->model->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get recently active users.
     */
    public function getRecentlyActive(int $minutes = 30): Collection
    {
        return $this->model->where('last_activity_at', '>=', now()->subMinutes($minutes))
            ->with('roles')
            ->get();
    }

    /**
     * Get users with expired passwords.
     */
    public function getWithExpiredPasswords(int $expiryDays = 90): Collection
    {
        return $this->model->where(function ($query) use ($expiryDays) {
            $query->whereNull('password_changed_at')
                ->orWhere('password_changed_at', '<=', now()->subDays($expiryDays));
        })
            ->active()
            ->get();
    }

    /**
     * Get locked users.
     */
    public function getLocked(): Collection
    {
        return $this->model->whereNotNull('locked_until')
            ->where('locked_until', '>', now())
            ->get();
    }

    /**
     * Get users without MFA.
     */
    public function getWithoutMfa(): Collection
    {
        return $this->model->where('mfa_enabled', false)
            ->active()
            ->get();
    }

    /**
     * Get users requiring MFA setup.
     */
    public function getRequiringMfaSetup(): Collection
    {
        return $this->model->where('mfa_required', true)
            ->where('mfa_enabled', false)
            ->active()
            ->get();
    }

    /**
     * Update user's last activity.
     */
    public function updateLastActivity(int $id): bool
    {
        return $this->model->where('id', $id)
            ->update(['last_activity_at' => now()]);
    }

    /**
     * Check if username exists.
     */
    public function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $query = $this->model->where('username', $username);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Check if email exists.
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = $this->model->where('email', $email);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
