<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations for test database
        $this->artisan('migrate');
        
        // Seed roles and permissions
        $this->seedRolesAndPermissions();
    }

    protected function seedRolesAndPermissions(): void
    {
        $admin = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
        $employee = \Spatie\Permission\Models\Role::create(['name' => 'Employee']);
        
        $permissions = [
            'view employees',
            'create employees',
            'approve leave',
            'approve financial request',
            'mark as paid',
        ];
        
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }
        
        $admin->givePermissionTo($permissions);
    }

    protected function createAdmin(): \App\Models\User
    {
        $user = \App\Models\User::factory()->create();
        $user->assignRole('Admin');
        return $user;
    }

    protected function createEmployee(): \App\Models\User
    {
        $user = \App\Models\User::factory()->create();
        $user->assignRole('Employee');
        return $user;
    }
}
