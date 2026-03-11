<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OffboardingTaskController extends Controller
{
    public function index()
    {
        $tasks = \App\Models\OffboardingTask::latest()->get();
        return view('admin.resignations.offboarding.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\OffboardingTask::create($validated + ['is_active' => true]);

        return redirect()->route('admin.offboarding.index')->with('success', 'Offboarding task template created.');
    }

    public function edit(\App\Models\OffboardingTask $offboarding)
    {
        return view('admin.resignations.offboarding.edit', compact('offboarding'));
    }

    public function update(Request $request, \App\Models\OffboardingTask $offboarding)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $offboarding->update($validated);

        return redirect()->route('admin.offboarding.index')->with('success', 'Offboarding task template updated.');
    }

    public function destroy(\App\Models\OffboardingTask $offboarding)
    {
        $offboarding->delete();
        return redirect()->route('admin.offboarding.index')->with('success', 'Offboarding task template deleted.');
    }
}
