<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/users')]
#[Middleware(['web', 'auth'])]
class UserController extends Controller
{
    use HandlesApiResponses;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Display a listing of users.
     */
    #[Get('/', name: 'admin.users.index')]
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $search = $request->get('search');

        $users = $search
            ? $this->userService->searchUsers($search)
            : $this->userService->getPaginatedUsers();

        $statistics = $this->userService->getStatistics();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'statistics', 'search', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    #[Get('/create', name: 'admin.users.create')]
    public function create()
    {
        $this->authorize('create', User::class);

        $roles = Role::all();
        $statuses = config('security.user_statuses', []);

        return view('admin.users.create', compact('roles', 'statuses'));
    }

    /**
     * Store a newly created user.
     */
    #[Post('/', name: 'admin.users.store')]
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->createUser(
                $request->validated(),
                auth()->id()
            );

            return $this->successRedirect(
                'admin.users.index',
                __('Pengguna berjaya dicipta: :name', ['name' => $user->name])
            );
        } catch (\Exception $e) {
            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'data' => $request->validated(),
            ]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Display the specified user.
     */
    #[Get('/{user}', name: 'admin.users.show')]
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load(['roles', 'mfaSecret', 'trustedDevices', 'auditLogs' => function ($query) {
            $query->latest()->limit(20);
        }]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    #[Get('/{user}/edit', name: 'admin.users.edit')]
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        $statuses = config('security.user_statuses', []);

        return view('admin.users.edit', compact('user', 'roles', 'userRoles', 'statuses'));
    }

    /**
     * Update the specified user.
     */
    #[Patch('/{user}', name: 'admin.users.update')]
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $this->userService->updateUser(
                $user->id,
                $request->validated(),
                auth()->id()
            );

            return $this->successRedirect(
                'admin.users.index',
                __('Pengguna berjaya dikemaskini: :name', ['name' => $user->name])
            );
        } catch (\Exception $e) {
            Log::error('Failed to update user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Remove the specified user.
     */
    #[Delete('/{user}', name: 'admin.users.destroy')]
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        try {
            $this->userService->deleteUser($user->id);

            return $this->successRedirect(
                'admin.users.index',
                __('Pengguna berjaya dipadam: :name', ['name' => $user->name])
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete user', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Activate a user.
     */
    #[Patch('/{user}/activate', name: 'admin.users.activate')]
    public function activate(User $user)
    {
        $this->authorize('update', $user);

        try {
            $this->userService->activateUser($user->id, auth()->id());

            return $this->successRedirect(
                'admin.users.index',
                __('Pengguna berjaya diaktifkan: :name', ['name' => $user->name])
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Deactivate a user.
     */
    #[Patch('/{user}/deactivate', name: 'admin.users.deactivate')]
    public function deactivate(User $user)
    {
        $this->authorize('update', $user);

        try {
            $this->userService->deactivateUser($user->id, auth()->id());

            return $this->successRedirect(
                'admin.users.index',
                __('Pengguna berjaya dinyahaktifkan: :name', ['name' => $user->name])
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Suspend a user.
     */
    #[Patch('/{user}/suspend', name: 'admin.users.suspend')]
    public function suspend(Request $request, User $user)
    {
        $this->authorize('update', $user);

        try {
            $this->userService->suspendUser(
                $user->id,
                $request->get('reason'),
                auth()->id()
            );

            return $this->successRedirect(
                'admin.users.index',
                __('Pengguna berjaya digantung: :name', ['name' => $user->name])
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Reset user password.
     */
    #[Patch('/{user}/reset-password', name: 'admin.users.reset-password')]
    public function resetPassword(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $request->validate([
            'password' => ['required', 'confirmed', 'min:12'],
        ]);

        try {
            $this->userService->resetPassword(
                $user->id,
                $request->password,
                auth()->id()
            );

            return $this->successRedirect(
                'admin.users.show',
                __('Kata laluan berjaya ditetapkan semula untuk: :name', ['name' => $user->name]),
                ['user' => $user->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Force logout user.
     */
    #[Patch('/{user}/force-logout', name: 'admin.users.force-logout')]
    public function forceLogout(User $user)
    {
        $this->authorize('update', $user);

        try {
            $this->userService->forceLogout($user->id);

            return $this->successRedirect(
                'admin.users.show',
                __('Pengguna berjaya dilog keluar: :name', ['name' => $user->name]),
                ['user' => $user->id]
            );
        } catch (\Exception $e) {
            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Show import form.
     */
    #[Get('/import', name: 'admin.users.import.form')]
    public function importForm()
    {
        $this->authorize('create', User::class);

        return view('admin.users.import');
    }

    /**
     * Import users from file.
     */
    #[Post('/import', name: 'admin.users.import')]
    public function import(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx', 'max:5120'],
        ]);

        // TODO: Implement file parsing and user import
        // This would use a library like Maatwebsite/Excel

        return $this->errorRedirect('Import functionality coming soon.');
    }
}
