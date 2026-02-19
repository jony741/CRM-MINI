<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Customer permissions
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',
            'customers.assign',

            // Note permissions
            'notes.view',
            'notes.create',
            'notes.update',
            'notes.delete',

            // User management (admin only)
            'users.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::all());

        $managerRole = Role::firstOrCreate(['name' => 'Manager']);
        $managerRole->syncPermissions([
            'customers.view',
            'customers.create',
            'customers.update',
            'customers.delete',
            'customers.assign',
            'notes.view',
            'notes.create',
            'notes.update',
            'notes.delete',
        ]);

        $userRole = Role::firstOrCreate(['name' => 'User']);
        $userRole->syncPermissions([
            'customers.view',
            'customers.create',
            'customers.update',
            'notes.view',
            'notes.create',
            'notes.update',
            'notes.delete',
        ]);
    }
}
