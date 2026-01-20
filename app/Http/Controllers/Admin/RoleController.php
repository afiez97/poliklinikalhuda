<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Traits\HandlesApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('admin/roles')]
#[Middleware(['web', 'auth'])]
class RoleController extends Controller
{
    use HandlesApiResponses;

    public function __construct(
        protected AuditService $auditService
    ) {}

    /**
     * Display a listing of roles.
     */
    #[Get('/', name: 'admin.roles.index')]
    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::withCount('users', 'permissions')->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     */
    #[Get('/create', name: 'admin.roles.create')]
    public function create()
    {
        $this->authorize('create', Role::class);

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role.
     */
    #[Post('/', name: 'admin.roles.store')]
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name', 'regex:/^[a-z0-9-]+$/'],
            'display_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $role = Role::create([
                    'name' => $validated['name'],
                    'guard_name' => 'web',
                ]);

                // Store display name and description in a custom way or use role's name
                // For now, we'll use a simple approach

                if (! empty($validated['permissions'])) {
                    $role->syncPermissions($validated['permissions']);
                }

                $this->auditService->log('create', 'Role created: '.$role->name);
            });

            return $this->successRedirect(
                'admin.roles.index',
                __('Peranan berjaya dicipta: :name', ['name' => $validated['display_name']])
            );
        } catch (\Exception $e) {
            Log::error('Failed to create role', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Display the specified role.
     */
    #[Get('/{role}', name: 'admin.roles.show')]
    public function show(Role $role)
    {
        $this->authorize('view', $role);

        $role->load(['permissions', 'users']);

        $permissionsByGroup = $role->permissions->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.roles.show', compact('role', 'permissionsByGroup'));
    }

    /**
     * Show the form for editing the specified role.
     */
    #[Get('/{role}/edit', name: 'admin.roles.edit')]
    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role.
     */
    #[Patch('/{role}', name: 'admin.roles.update')]
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        // Prevent editing super-admin role name
        if ($role->name === 'super-admin' && $request->name !== 'super-admin') {
            return $this->errorRedirect('Tidak boleh mengubah nama peranan Super Admin.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:roles,name,'.$role->id, 'regex:/^[a-z0-9-]+$/'],
            'display_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        try {
            DB::transaction(function () use ($role, $validated) {
                $role->update(['name' => $validated['name']]);

                $role->syncPermissions($validated['permissions'] ?? []);

                $this->auditService->log('update', 'Role updated: '.$role->name);
            });

            return $this->successRedirect(
                'admin.roles.index',
                __('Peranan berjaya dikemaskini: :name', ['name' => $validated['display_name']])
            );
        } catch (\Exception $e) {
            Log::error('Failed to update role', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Remove the specified role.
     */
    #[Delete('/{role}', name: 'admin.roles.destroy')]
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        // Prevent deleting super-admin role
        if ($role->name === 'super-admin') {
            return $this->errorRedirect('Tidak boleh memadam peranan Super Admin.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return $this->errorRedirect(
                __('Peranan ":name" masih mempunyai :count pengguna. Sila alihkan pengguna dahulu.', [
                    'name' => $role->name,
                    'count' => $role->users()->count(),
                ])
            );
        }

        try {
            $roleName = $role->name;
            $role->delete();

            $this->auditService->log('delete', 'Role deleted: '.$roleName);

            return $this->successRedirect(
                'admin.roles.index',
                __('Peranan berjaya dipadam: :name', ['name' => $roleName])
            );
        } catch (\Exception $e) {
            Log::error('Failed to delete role', ['error' => $e->getMessage()]);

            return $this->errorRedirect($e->getMessage());
        }
    }

    /**
     * Display permission matrix.
     */
    #[Get('/matrix', name: 'admin.roles.matrix')]
    public function matrix()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('admin.roles.matrix', compact('roles', 'permissions'));
    }
}
