<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

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
        $roles = Role::all();
        // Get all potential managers (anyone with Manager role or just all employees for flexibility)
        // For now, let's allow any employee to be a manager for simplicity
        $managers = Employee::with('user')->where('status', 'active')->get();
        
        return view('employees.create', compact('departments', 'designations', 'managers', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed', // Add validation for optional password
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string|max:255',
            'grade_level_id' => 'nullable|exists:grade_levels,id',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'dob' => 'nullable|date',
            'join_date' => 'nullable|date',
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|max:2048',
        ]);

        // 1. Create User Account
        // Use provided password or generate a random one
        $rawPassword = $request->filled('password') ? $request->password : \Illuminate\Support\Str::random(10);
        
        $user = \App\Models\User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($rawPassword),
            'must_change_password' => !$request->filled('password'), // Only force change if auto-generated
        ]);
        
        // Assign selected role
        $user->assignRole($validated['role']);

        // Create or find designation by title
        $designation = Designation::firstOrCreate(['title' => $request->designation]);

        // 2. Create Employee Profile
        $employee = Employee::create([
            'user_id' => $user->id,
            'department_id' => $validated['department_id'],
            'designation_id' => $designation->id,
            'grade_level_id' => $validated['grade_level_id'] ?? null,
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
                'user_id' => $user->id, // Changed to user_id as per Document model
                'title' => 'Profile Photo',
                'type' => 'id_proof', // Using generic type for now or 'photo' if enum allowed
                'file_path' => $path,
                'status' => 'active'
            ]);
        }

        // 4. Auto-assign Onboarding Tasks
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

        $message = $request->filled('password') 
            ? "Employee created successfully." 
            : "Employee created successfully. Temporary password: {$rawPassword}";

        return redirect()->route('employees.index')->with('success', $message);
    }
    
    public function resetPassword(Request $request, Employee $employee)
    {
        // Ensure only admin can do this (Middleware should handle it, but good to be safe)
        if (!auth()->user()->hasRole('Admin')) {
             abort(403);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $employee->user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'must_change_password' => false, // Reset this flag if admin manually resets it to a known value
        ]);

        // Log this action (Auditable trait handles model updates, but password reset on User might need explicit log if User isn't auditing password field securely)
        // Since User model is Auditable, it will log the update, but 'password' field is typically hidden or hashed.
        // We can add a custom log if needed, but the User update event is sufficient for now.

        return redirect()->back()->with('success', 'Password reset successfully for ' . $employee->user->name);
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
        $roles = Role::all();
        $managers = Employee::with('user')
            ->where('status', 'active')
            ->where('id', '!=', $employee->id) // Prevent self-selection
            ->get();
            
        return view('employees.edit', compact('employee', 'departments', 'designations', 'managers', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->user_id,
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string|max:255',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'dob' => 'nullable|date',
            'join_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,terminated',
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|max:2048',
        ]);

        // 1. Update User Account
        $employee->user->update([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ]);

        // Sync role
        $employee->user->syncRoles([$validated['role']]);

        // Create or find designation by title
        $designation = Designation::firstOrCreate(['title' => $request->designation]);

        // 2. Update Employee Profile
        $employee->update([
            'department_id' => $validated['department_id'],
            'designation_id' => $designation->id,
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
