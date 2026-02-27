<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::where('user_id', Auth::id())->latest()->paginate(10);
        return view('performance.goals.index', compact('goals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'type' => 'required|in:text,metric',
            'target_value' => 'nullable|required_if:type,metric|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'weight' => 'integer|min:1|max:10',
        ]);

        $cycleFrequency = \App\Models\Setting::get('performance_cycle_frequency', 'annual');
        $now = now();
        $cycleName = match($cycleFrequency) {
            'monthly' => $now->format('M Y'),
            'quarterly' => 'Q' . ceil($now->month / 3) . ' ' . $now->year,
            'half_yearly' => 'H' . ceil($now->month / 6) . ' ' . $now->year,
            default => (string) $now->year,
        };

        $goal = Goal::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'type' => $validated['type'],
            'target_value' => $validated['type'] === 'metric' ? $validated['target_value'] : null,
            'current_value' => 0,
            'unit' => $validated['unit'],
            'weight' => $validated['weight'] ?? 1,
            'cycle_name' => $cycleName,
            'status' => 'not_started',
            'progress' => 0,
        ]);

        return back()->with('success', 'Goal created successfully.');
    }

    public function update(Request $request, Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        if ($goal->status === 'completed') {
            return back()->withErrors(['status' => 'This goal has been marked as completed and can no longer be updated.']);
        }

        $validated = $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,cancelled',
            'current_value' => 'nullable|numeric|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        // Logic for metric vs text goals
        if ($goal->type === 'metric') {
            $currentValue = $validated['current_value'] ?? $goal->current_value;
            $goal->current_value = $currentValue;
            
            // Auto-calculate progress
            if ($goal->target_value > 0) {
                $percentage = ($currentValue / $goal->target_value) * 100;
                $goal->progress = min(100, max(0, round($percentage)));
            }
        } else {
            // Manual progress for text goals
            $goal->progress = $validated['progress'] ?? $goal->progress;
        }

        $goal->status = $validated['status'];
        $goal->save();

        return back()->with('success', 'Goal progress updated successfully.');
    }

    public function destroy(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        $goal->delete();

        return back()->with('success', 'Goal deleted successfully.');
    }
}
