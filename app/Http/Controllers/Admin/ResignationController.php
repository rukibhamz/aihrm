<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resignation;
use App\Models\Asset;
use Illuminate\Http\Request;

class ResignationController extends Controller
{
    public function index()
    {
        $query = Resignation::with('user.employee.designation');

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $resignations = $query->latest()->paginate(10);

        return view('admin.resignations.index', compact('resignations'));
    }

    public function show(Resignation $resignation)
    {
        $resignation->load('user.employee.designation', 'user.employee.department');
        // Fetch assets assigned to this user
        $assets = Asset::where('user_id', $resignation->user_id)->get();
        
        return view('admin.resignations.show', compact('resignation', 'assets'));
    }

    public function update(Request $request, Resignation $resignation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'hr_comments' => 'nullable|string',
            'exit_interview_notes' => 'nullable|string',
        ]);

        $oldStatus = $resignation->status;
        $resignation->update($validated);

        // Generate offboarding tasks if approved and didn't have them before
        if ($resignation->status === 'approved' && $oldStatus !== 'approved' && $resignation->offboardingTasks()->count() === 0) {
            $activeTasks = \App\Models\OffboardingTask::where('is_active', true)->get();
            foreach ($activeTasks as $task) {
                \App\Models\EmployeeOffboardingTask::create([
                    'resignation_id' => $resignation->id,
                    'offboarding_task_id' => $task->id,
                    'is_completed' => false,
                ]);
            }
        }

        return redirect()->route('admin.resignations.show', $resignation)->with('success', 'Resignation status updated.');
    }

    public function assetsCheck(Resignation $resignation)
    {
        // Dedicated view just for assets if needed, but show view covers it.
        // Keeping this method if we need a specific JSON endpoint or print view later.
        $assets = Asset::where('user_id', $resignation->user_id)->get();
        return view('admin.resignations.assets', compact('resignation', 'assets'));
    }

    public function updateOffboardingTask(Request $request, Resignation $resignation, \App\Models\EmployeeOffboardingTask $task)
    {
        if ($task->resignation_id !== $resignation->id) {
            abort(403);
        }

        $validated = $request->validate([
            'is_completed' => 'required|boolean',
            'comments' => 'nullable|string',
        ]);

        $task->update([
            'is_completed' => $validated['is_completed'],
            'comments' => $validated['comments'] ?? $task->comments,
            'completed_by' => $validated['is_completed'] ? auth()->id() : null,
            'completed_at' => $validated['is_completed'] ? now() : null,
        ]);

        return back()->with('success', 'Offboarding task status updated.');
    }
}
