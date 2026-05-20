<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
       
        //clease cache (required for Spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'deactivate users',

            // Role Management
            'view roles',
            'create roles',
            'deactivate roles',

            // Time Logs
            'view logs',
            'create logs',
            'deactivate logs',
            'approve logs', // manager action
            'reject logs',  // manager action
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $superadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $manager    = Role::firstOrCreate(['name' => 'Manager']);
        $user       = Role::firstOrCreate(['name' => 'User']);

        // Assign permissions
        $superadmin->syncPermissions($permissions);

        $manager->syncPermissions([
            'view logs',
            'approve logs',
            'reject logs',
        ]);

        $user->syncPermissions([
            'create logs',
            'view logs',
        ]);
    }
}