<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        protected UserRepository $repository,
        protected AuditService $auditService
    ) {}

    /**
     * Get all users.
     */
    public function getAllUsers(): Collection
    {
        return $this->repository->all();
    }

    /**
     * Get paginated users.
     */
    public function getPaginatedUsers(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage);
    }

    /**
     * Search users.
     */
    public function searchUsers(string $search, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->search($search, $perPage);
    }

    /**
     * Get user by ID.
     */
    public function getUserById(int $id): ?User
    {
        return $this->repository->findById($id);
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data, ?int $createdBy = null): User
    {
        return DB::transaction(function () use ($data, $createdBy) {
            // Set default values
            $data['password'] = Hash::make($data['password']);
            $data['status'] = $data['status'] ?? 'pending';
            $data['password_changed_at'] = now();
            $data['created_by'] = $createdBy;

            $user = $this->repository->create($data);

            // Assign roles if provided
            if (! empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            // Check if MFA is required for assigned roles
            if ($user->requiresMfa()) {
                $user->update(['mfa_required' => true]);
            }

            // Log creation
            $this->auditService->logCreate($user, 'User created: '.$user->name);

            return $user;
        });
    }

    /**
     * Update a user.
     */
    public function updateUser(int $id, array $data, ?int $updatedBy = null): bool
    {
        return DB::transaction(function () use ($id, $data, $updatedBy) {
            $user = $this->repository->findById($id);

            if (! $user) {
                return false;
            }

            $oldValues = $user->toArray();

            // Handle password update
            if (! empty($data['password'])) {
                // Check password history
                if ($user->wasPasswordUsed($data['password'])) {
                    throw new \Exception('Password telah digunakan sebelum ini');
                }

                $user->addPasswordToHistory();
                $data['password'] = Hash::make($data['password']);
                $data['password_changed_at'] = now();
                $data['must_change_password'] = false;
            } else {
                unset($data['password']);
            }

            $data['updated_by'] = $updatedBy;

            $result = $this->repository->update($id, $data);

            // Update roles if provided
            if (isset($data['roles'])) {
                $user->refresh();
                $user->syncRoles($data['roles']);

                // Check if MFA is now required
                if ($user->requiresMfa() && ! $user->mfa_required) {
                    $user->update(['mfa_required' => true]);
                }
            }

            // Log update
            $this->auditService->logUpdate($user, $oldValues, 'User updated: '.$user->name);

            return $result;
        });
    }

    /**
     * Delete a user (soft delete).
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return false;
        }

        // Cannot delete the last super admin
        if ($user->hasRole('super-admin')) {
            $superAdminCount = User::role('super-admin')->count();
            if ($superAdminCount <= 1) {
                throw new \Exception('Tidak boleh memadam Super Admin terakhir');
            }
        }

        $this->auditService->logDelete($user, 'User deleted: '.$user->name);

        return $this->repository->delete($id);
    }

    /**
     * Activate a user.
     */
    public function activateUser(int $id, ?int $updatedBy = null): bool
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return false;
        }

        $result = $user->update([
            'status' => 'active',
            'updated_by' => $updatedBy,
        ]);

        $this->auditService->log('activate', 'User activated: '.$user->name, $user);

        return $result;
    }

    /**
     * Deactivate a user.
     */
    public function deactivateUser(int $id, ?int $updatedBy = null): bool
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return false;
        }

        // Cannot deactivate the last super admin
        if ($user->hasRole('super-admin')) {
            $activeSuperAdmins = User::role('super-admin')->active()->count();
            if ($activeSuperAdmins <= 1) {
                throw new \Exception('Tidak boleh menyahaktifkan Super Admin terakhir');
            }
        }

        $result = $user->update([
            'status' => 'inactive',
            'updated_by' => $updatedBy,
        ]);

        $this->auditService->log('deactivate', 'User deactivated: '.$user->name, $user);

        return $result;
    }

    /**
     * Suspend a user.
     */
    public function suspendUser(int $id, ?string $reason = null, ?int $updatedBy = null): bool
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return false;
        }

        // Cannot suspend super admin
        if ($user->hasRole('super-admin')) {
            throw new \Exception('Tidak boleh menggantung Super Admin');
        }

        $result = $user->update([
            'status' => 'suspended',
            'updated_by' => $updatedBy,
        ]);

        $this->auditService->log(
            'suspend',
            'User suspended: '.$user->name,
            $user,
            metadata: ['reason' => $reason]
        );

        return $result;
    }

    /**
     * Reset user password.
     */
    public function resetPassword(int $id, string $newPassword, ?int $updatedBy = null): bool
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return false;
        }

        $user->addPasswordToHistory();

        $result = $user->update([
            'password' => Hash::make($newPassword),
            'password_changed_at' => now(),
            'must_change_password' => true,
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'updated_by' => $updatedBy,
        ]);

        $this->auditService->logPasswordReset($user);

        return $result;
    }

    /**
     * Force logout user (terminate sessions).
     */
    public function forceLogout(int $id): bool
    {
        $user = $this->repository->findById($id);

        if (! $user) {
            return false;
        }

        // Delete all sessions for this user
        DB::table('sessions')
            ->where('user_id', $id)
            ->delete();

        $this->auditService->log('force_logout', 'User force logged out: '.$user->name, $user);

        return true;
    }

    /**
     * Get user statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->repository->count(),
            'active' => $this->repository->countActive(),
            'by_status' => $this->repository->countByStatus(),
            'recently_active' => $this->repository->getRecentlyActive()->count(),
            'locked' => $this->repository->getLocked()->count(),
            'without_mfa' => $this->repository->getWithoutMfa()->count(),
            'requiring_mfa_setup' => $this->repository->getRequiringMfaSetup()->count(),
            'expired_passwords' => $this->repository->getWithExpiredPasswords()->count(),
        ];
    }

    /**
     * Import users from array.
     */
    public function importUsers(array $users, ?int $createdBy = null): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        foreach ($users as $index => $userData) {
            try {
                $this->createUser($userData, $createdBy);
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'row' => $index + 1,
                    'data' => $userData,
                    'error' => $e->getMessage(),
                ];
            }
        }

        if ($results['success'] > 0) {
            $this->auditService->log(
                'import',
                "Imported {$results['success']} users",
                metadata: [
                    'success_count' => $results['success'],
                    'failed_count' => $results['failed'],
                ]
            );
        }

        return $results;
    }

    /**
     * Get recently active users.
     */
    public function getRecentlyActiveUsers(int $minutes = 30): Collection
    {
        return $this->repository->getRecentlyActive($minutes);
    }

    /**
     * Get users by role.
     */
    public function getUsersByRole(string $roleName): Collection
    {
        return $this->repository->getByRole($roleName);
    }
}
