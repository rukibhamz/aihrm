<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OnboardingTask;
use App\Models\Department;
use Illuminate\Http\Request;

class OnboardingTaskController extends Controller
{
    public function index()
    {
        $tasks = OnboardingTask::with('department')->latest()->paginate(15);
        return view('admin.onboarding.index', compact('tasks'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.onboarding.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stage' => 'required|in:onboarding,offboarding',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        OnboardingTask::create($validated);

        return redirect()->route('admin.onboarding.index')->with('success', 'Task template created successfully.');
    }

    public function destroy(OnboardingTask $onboarding)
    {
        $onboarding->delete();
        return redirect()->route('admin.onboarding.index')->with('success', 'Task template deleted successfully.');
    }
}
