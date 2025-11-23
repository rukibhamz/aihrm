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
        ]);

        Goal::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
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

        $validated = $request->validate([
            'status' => 'required|in:not_started,in_progress,completed,cancelled',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $goal->update($validated);

        return back()->with('success', 'Goal updated successfully.');
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
