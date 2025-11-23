<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'designation', 'user'])->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        // Get all potential managers (anyone with Manager role or just all employees for flexibility)
        // For now, let's allow any employee to be a manager for simplicity
        $managers = Employee::with('user')->where('status', 'active')->get();
        
        return view('employees.create', compact('departments', 'designations', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'dob' => 'nullable|date',
            'join_date' => 'nullable|date',
            'photo' => 'nullable|image|max:2048',
        ]);

        // 1. Create User Account
        $password = 'password'; // Default password
        $user = \App\Models\User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($password),
        ]);
        
        // Assign default role (Employee)
        $user->assignRole('Employee');

        // 2. Create Employee Profile
        $employee = Employee::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'designation_id' => $validated['designation_id'],
            'manager_id' => $validated['manager_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'join_date' => $validated['join_date'] ?? now(),
            'status' => 'active',
        ]);

        // 3. Handle File Upload (Photo)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employee_photos', 'public');
            \App\Models\Document::create([
                'employee_id' => $employee->id,
                'type' => 'Profile Photo',
                'file_path' => $path,
            ]);
        }

        // 4. Auto-assign Onboarding Tasks
        // Get global tasks + department specific tasks
        $tasks = \App\Models\OnboardingTask::where('stage', 'onboarding')
            ->where(function($query) use ($validated) {
                $query->whereNull('department_id')
                      ->orWhere('department_id', $validated['department_id']);
            })
            ->get();

        foreach ($tasks as $task) {
            \App\Models\EmployeeTask::create([
                'user_id' => $user->id,
                'onboarding_task_id' => $task->id,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('employees.index')->with('success', 'Employee created successfully. Default password is "password".');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'department', 'designation', 'manager']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $designations = Designation::all();
        $managers = Employee::with('user')
            ->where('status', 'active')
            ->where('id', '!=', $employee->id) // Prevent self-selection
            ->get();
            
        return view('employees.edit', compact('employee', 'departments', 'designations', 'managers'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->user_id,
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'dob' => 'nullable|date',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
            'photo' => 'nullable|image|max:2048',
        ]);

        // 1. Update User Account
        $employee->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ]);

        // 2. Update Employee Profile
        $employee->update([
            'department_id' => $validated['department_id'],
            'designation_id' => $validated['designation_id'],
            'manager_id' => $validated['manager_id'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'join_date' => $validated['join_date'] ?? null,
            'status' => $validated['status'],
        ]);

        // 3. Handle File Upload (Photo)
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('employee_photos', 'public');
            
            // Check if photo document exists
            $photoDoc = \App\Models\Document::where('employee_id', $employee->id)
                ->where('type', 'Profile Photo')
                ->first();

            if ($photoDoc) {
                // Delete old file
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photoDoc->file_path);
                $photoDoc->update(['file_path' => $path]);
            } else {
                \App\Models\Document::create([
                    'employee_id' => $employee->id,
                    'type' => 'Profile Photo',
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }
}
