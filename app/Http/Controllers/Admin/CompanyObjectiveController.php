<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyObjectiveController extends Controller
{
    public function index()
    {
        $objectives = \App\Models\CompanyObjective::withCount('goals')->latest('start_date')->get();
        
        // Calculate dynamic progress based on linked goals
        foreach($objectives as $objective) {
            $this->calculateObjectiveProgress($objective);
        }

        return view('admin.performance.objectives.index', compact('objectives'));
    }

    public function create()
    {
        return view('admin.performance.objectives.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,completed,cancelled',
        ]);

        \App\Models\CompanyObjective::create($request->all());

        return redirect()->route('admin.performance.objectives.index')->with('success', 'Company Objective created successfully.');
    }

    public function show(\App\Models\CompanyObjective $objective)
    {
        $objective->load(['goals.user']);
        $this->calculateObjectiveProgress($objective);
        
        return view('admin.performance.objectives.show', compact('objective'));
    }

    public function edit(\App\Models\CompanyObjective $objective)
    {
        return view('admin.performance.objectives.edit', compact('objective'));
    }

    public function update(Request $request, \App\Models\CompanyObjective $objective)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:draft,active,completed,cancelled',
        ]);

        $objective->update($request->all());

        return redirect()->route('admin.performance.objectives.index')->with('success', 'Company Objective updated successfully.');
    }

    public function destroy(\App\Models\CompanyObjective $objective)
    {
        if ($objective->goals()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete objective that has linked individual goals.']);
        }
        
        $objective->delete();
        return redirect()->route('admin.performance.objectives.index')->with('success', 'Company Objective deleted successfully.');
    }

    private function calculateObjectiveProgress(\App\Models\CompanyObjective $objective)
    {
        if ($objective->goals()->count() === 0) {
            return;
        }

        $goals = $objective->goals;
        $totalWeight = $goals->sum('weight') ?: $goals->count();
        $weightedProgressSum = 0;

        foreach ($goals as $goal) {
            $weight = $goal->weight ?: 1;
            $goalProgress = $goal->type === 'metric' ? $goal->calculateProgress() : $goal->progress;
            $weightedProgressSum += ($goalProgress * $weight);
        }

        $calculatedProgress = round($weightedProgressSum / $totalWeight);
        
        if ($objective->progress !== $calculatedProgress) {
            $objective->update(['progress' => $calculatedProgress]);
            $objective->progress = $calculatedProgress;
        }
    }
}
