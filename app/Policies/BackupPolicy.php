<?php

namespace App\Policies;

use App\Models\Backup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BackupPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('backup.view');
    }

    public function view(User $user, Backup $backup): bool
    {
        return $user->can('backup.view');
    }

    public function create(User $user): bool
    {
        return $user->can('backup.create');
    }

    public function download(User $user, Backup $backup): bool
    {
        return $user->can('backup.download');
    }

    public function delete(User $user, Backup $backup): bool
    {
        return $user->can('backup.delete');
    }

    public function restore(User $user, Backup $backup): bool
    {
        return $user->can('backup.restore');
    }
}
