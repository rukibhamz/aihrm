<?php

namespace App\Http\Controllers;

use App\Models\OnboardingTask;
use App\Models\EmployeeTask;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    // Admin: List Templates
    public function index()
    {
        $tasks = OnboardingTask::with('department')->latest()->paginate(15);
        return view('admin.onboarding.index', compact('tasks'));
    }

    // Admin: Create Template
    public function create()
    {
        $departments = Department::all();
        return view('admin.onboarding.create', compact('departments'));
    }

    // Admin: Store Template
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stage' => 'required|in:onboarding,offboarding',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        OnboardingTask::create($validated);

        return redirect()->route('admin.onboarding.index')->with('success', 'Task template created.');
    }

    // Admin: Delete Template
    public function destroy(OnboardingTask $task)
    {
        $task->delete();
        return back()->with('success', 'Task template deleted.');
    }

    // Employee: View My Checklist
    public function myTasks()
    {
        $tasks = EmployeeTask::where('user_id', Auth::id())
            ->with('task')
            ->get()
            ->groupBy('task.stage');

        return view('onboarding.index', compact('tasks'));
    }

    // Employee: Complete Task
    public function completeTask(EmployeeTask $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Task marked as complete.');
    }
}
