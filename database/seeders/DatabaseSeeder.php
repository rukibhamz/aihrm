<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Designation;
use App\Models\LeaveType;
use App\Models\FinancialRequestCategory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@aihrm.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Departments
        $depts = ['Human Resources', 'Engineering', 'Sales', 'Marketing', 'Finance'];
        foreach ($depts as $d) {
            Department::firstOrCreate(['name' => $d]);
        }

        // 3. Designations
        $desigs = [
            ['title' => 'HR Manager', 'grade' => 5],
            ['title' => 'Software Engineer', 'grade' => 3],
            ['title' => 'Sales Executive', 'grade' => 2],
            ['title' => 'Finance Lead', 'grade' => 4],
            ['title' => 'Intern', 'grade' => 1],
        ];
        foreach ($desigs as $d) {
            Designation::firstOrCreate(['title' => $d['title']], ['grade_level' => $d['grade']]);
        }

        // 4. Leave Types
        $leaves = [
            ['name' => 'Annual Leave', 'days' => 20],
            ['name' => 'Sick Leave', 'days' => 10],
            ['name' => 'Maternity Leave', 'days' => 90],
            ['name' => 'Paternity Leave', 'days' => 14],
            ['name' => 'Casual Leave', 'days' => 5],
        ];
        foreach ($leaves as $l) {
            LeaveType::firstOrCreate(['name' => $l['name']], ['days_allowed' => $l['days']]);
        }

        // 5. Financial Categories
        $cats = ['Travel', 'Office Supplies', 'Training', 'Salary Advance', 'Reimbursement'];
        foreach ($cats as $c) {
            FinancialRequestCategory::firstOrCreate(['name' => $c]);
        }

        // 6. Roles & Permissions
        $this->call(RolePermissionSeeder::class);
    }
}
