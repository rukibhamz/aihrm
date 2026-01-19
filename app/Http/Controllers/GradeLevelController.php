<?php

namespace App\Http\Controllers;

use App\Models\GradeLevel;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    public function index()
    {
        $gradeLevels = GradeLevel::withCount('employees')->paginate(10);
        return view('admin.grade_levels.index', compact('gradeLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:grade_levels,name',
            'basic_salary_range' => 'nullable|string|max:255',
        ]);

        GradeLevel::create($validated);

        return redirect()->route('admin.grade-levels.index')->with('success', 'Grade Level created successfully.');
    }

    public function update(Request $request, GradeLevel $gradeLevel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:grade_levels,name,' . $gradeLevel->id,
            'basic_salary_range' => 'nullable|string|max:255',
        ]);

        $gradeLevel->update($validated);

        return redirect()->route('admin.grade-levels.index')->with('success', 'Grade Level updated successfully.');
    }

    public function destroy(GradeLevel $gradeLevel)
    {
        if ($gradeLevel->employees()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete grade level with assigned employees.']);
        }

        $gradeLevel->delete();

        return redirect()->route('admin.grade-levels.index')->with('success', 'Grade Level deleted successfully.');
    }
}
