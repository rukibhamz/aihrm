<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Employee Permissions
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            
            // Leave Permissions
            'view leaves',
            'create leave request',
            'approve leave',
            'reject leave',
            
            // Finance Permissions
            'view financial requests',
            'create financial request',
            'approve financial request',
            'reject financial request',
            'mark as paid',
            
            // Reports
            'view reports',
            'export reports',
            
            // Settings
            'manage settings',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // 1. Admin - Full Access
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        // 2. HR - Employee & Leave Management
        $hr = Role::firstOrCreate(['name' => 'HR']);
        $hr->givePermissionTo([
            'view employees',
            'create employees',
            'edit employees',
            'view leaves',
            'approve leave',
            'reject leave',
            'view reports',
            'export reports',
        ]);

        // 3. Manager - Approval Rights for Team
        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view employees',
            'view leaves',
            'approve leave',
            'reject leave',
            'view financial requests',
            'approve financial request',
            'reject financial request',
        ]);

        // 4. Finance - Financial Request Management
        $finance = Role::firstOrCreate(['name' => 'Finance']);
        $finance->givePermissionTo([
            'view financial requests',
            'approve financial request',
            'reject financial request',
            'mark as paid',
            'view reports',
        ]);

        // 5. Employee - Self Service Only
        $employee = Role::firstOrCreate(['name' => 'Employee']);
        $employee->givePermissionTo([
            'create leave request',
            'create financial request',
            'view leaves',
            'view financial requests',
        ]);

        // Assign Admin role to default admin user
        $adminUser = User::where('email', 'admin@aihrm.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('Admin');
        }
    }
}
