<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\EmploymentStatus;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmployeeCreationTest extends TestCase
{
    public function test_new_staff_creation_sends_password_setup_email(): void
    {
        Notification::fake();

        $admin = $this->createAdmin();
        $department = Department::create([
            'name' => 'Engineering',
        ]);
        $employmentStatus = EmploymentStatus::create([
            'name' => 'Full Time',
        ]);

        $response = $this->actingAs($admin)->post(route('employees.store'), [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'department_id' => $department->id,
            'designation' => 'Software Engineer',
            'manager_id' => $admin->id,
            'gender' => 'female',
            'role' => 'Employee',
            'employment_status_id' => $employmentStatus->id,
        ]);

        $response->assertRedirect(route('employees.index'));

        $newStaff = User::where('email', 'jane.doe@example.com')->first();

        $this->assertNotNull($newStaff);
        $this->assertTrue((bool) $newStaff->must_change_password);

        Notification::assertSentTo($newStaff, ResetPassword::class);
    }
}
