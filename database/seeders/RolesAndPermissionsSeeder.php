<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions from config (key is permission name, value is description)
        $permissionConfig = config('security.default_permissions', []);

        foreach ($permissionConfig as $permissionName => $description) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Create default roles from config
        $defaultRoles = config('security.default_roles', []);

        foreach ($defaultRoles as $roleData) {
            $role = Role::firstOrCreate([
                'name' => $roleData['name'],
                'guard_name' => 'web',
            ]);

            // Super admin gets all permissions
            if ($roleData['name'] === 'super-admin') {
                $role->syncPermissions(Permission::all());
            }
        }

        // Define role-permission mappings for clinical roles
        $rolePermissions = [
            'admin' => [
                'users.view', 'users.create', 'users.update',
                'roles.view',
                'audit.view', 'audit.export',
                'settings.view',
                'backup.view',
                'sessions.view',
            ],
            'doktor' => [
                // Will get clinical permissions when those modules are implemented
            ],
            'jururawat' => [
                // Will get clinical permissions when those modules are implemented
            ],
            'kerani' => [
                // Will get front desk permissions when those modules are implemented
            ],
            'farmasi' => [
                // Will get pharmacy permissions when those modules are implemented
            ],
        ];

        // Sync permissions for each role
        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                // Only sync permissions that exist
                $existingPermissions = Permission::whereIn('name', $permissions)->pluck('name')->toArray();
                $role->syncPermissions($existingPermissions);
            }
        }

        $this->command->info('Roles and permissions seeded successfully.');
        $this->command->info('Created '.Permission::count().' permissions and '.Role::count().' roles.');
    }
}
