<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamGoalController extends Controller
{
    public function index()
    {
        // Get all employees managed by current user
        $managedEmployeeUserIds = Employee::where('manager_id', Auth::id())
                                          ->pluck('user_id');

        if ($managedEmployeeUserIds->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'You do not have any direct reports.');
        }

        $goals = Goal::whereIn('user_id', $managedEmployeeUserIds)
                     ->with('user')
                     ->latest()
                     ->paginate(15);

        return view('performance.team-goals.index', compact('goals'));
    }

    public function updateScore(Request $request, Goal $goal)
    {
        // Verify user is manager of the goal owner
        $isManager = Employee::where('user_id', $goal->user_id)
                             ->where('manager_id', Auth::id())
                             ->exists();

        if (!$isManager) {
            abort(403, 'You are not the manager of this employee.');
        }

        if ($goal->status !== 'completed') {
            return back()->with('error', 'You can only score completed goals.');
        }

        $validated = $request->validate([
            'manager_score' => 'required|integer|min:1|max:5',
            'manager_comment' => 'nullable|string',
        ]);

        $goal->manager_score = $validated['manager_score'];
        $goal->manager_comment = $validated['manager_comment'];
        $goal->save();

        return back()->with('success', 'KPI score and feedback submitted successfully.');
    }
}
