<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use App\Models\GradeLevel;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::withCount('requests')->with(['grades', 'employmentStatuses'])->get();
        $gradeLevels = GradeLevel::all();
        $employmentStatuses = EmploymentStatus::all();
        return view('admin.leave_types.index', compact('leaveTypes', 'gradeLevels', 'employmentStatuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'days_allowed' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_paid' => 'boolean',
            'employment_statuses' => 'nullable|array',
            'employment_statuses.*' => 'exists:employment_statuses,id',
            'grade_levels' => 'nullable|array',
            'grade_levels.*' => 'nullable|integer|min:0',
        ]);

        $leaveType = LeaveType::create([
            'name' => $validated['name'],
            'days_allowed' => $validated['days_allowed'],
            'description' => $validated['description'] ?? null,
            'is_paid' => $validated['is_paid'] ?? 0,
        ]);

        if (isset($validated['employment_statuses'])) {
            $leaveType->employmentStatuses()->sync($validated['employment_statuses']);
        }

        if (isset($validated['grade_levels'])) {
            // Delete old grades
            $leaveType->grades()->delete();
            foreach ($validated['grade_levels'] as $gradeId => $days) {
                if (!is_null($days) && $days !== '') {
                    $leaveType->grades()->create([
                        'grade_level_id' => $gradeId,
                        'days_allowed' => $days
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Leave Type created successfully.');
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'days_allowed' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_paid' => 'boolean',
            'employment_statuses' => 'nullable|array',
            'employment_statuses.*' => 'exists:employment_statuses,id',
            'grade_levels' => 'nullable|array',
            'grade_levels.*' => 'nullable|integer|min:0',
        ]);

        $leaveType->update([
            'name' => $validated['name'],
            'days_allowed' => $validated['days_allowed'],
            'description' => $validated['description'] ?? null,
            'is_paid' => $validated['is_paid'] ?? 0,
        ]);

        if (isset($validated['employment_statuses'])) {
            $leaveType->employmentStatuses()->sync($validated['employment_statuses']);
        } else {
            $leaveType->employmentStatuses()->detach();
        }

        // Delete old grades and replace
        $leaveType->grades()->delete();
        if (isset($validated['grade_levels'])) {
            foreach ($validated['grade_levels'] as $gradeId => $days) {
                if (!is_null($days) && $days !== '') {
                    $leaveType->grades()->create([
                        'grade_level_id' => $gradeId,
                        'days_allowed' => $days
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Leave Type updated successfully.');
    }

    public function destroy(LeaveType $leaveType)
    {
        if ($leaveType->requests()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete Leave Type with existing requests.');
        }

        $leaveType->delete();

        return redirect()->back()->with('success', 'Leave Type deleted successfully.');
    }
}
