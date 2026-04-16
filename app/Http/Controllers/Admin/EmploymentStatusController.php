<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmploymentStatus;
use Illuminate\Http\Request;

class EmploymentStatusController extends Controller
{
    public function index()
    {
        $statuses = EmploymentStatus::withCount(['employees'])->get();
        return view('admin.employment_statuses.index', compact('statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:employment_statuses,name',
            'description' => 'nullable|string|max:500',
        ]);

        EmploymentStatus::create($validated);

        return redirect()->back()->with('success', 'Employment status created successfully.');
    }

    public function update(Request $request, EmploymentStatus $employmentStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:employment_statuses,name,' . $employmentStatus->id,
            'description' => 'nullable|string|max:500',
        ]);

        $employmentStatus->update($validated);

        return redirect()->back()->with('success', 'Employment status updated successfully.');
    }

    public function destroy(EmploymentStatus $employmentStatus)
    {
        if ($employmentStatus->employees()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete status that is currently assigned to employees.');
        }

        if ($employmentStatus->leaveTypes()->exists()) {
             return redirect()->back()->with('error', 'Cannot delete status that is associated with leave types.');
        }

        $employmentStatus->delete();

        return redirect()->back()->with('success', 'Employment status deleted successfully.');
    }

    public function show()
    {
        return redirect()->route('admin.employment-statuses.index');
    }

    public function create()
    {
        return redirect()->route('admin.employment-statuses.index');
    }

    public function edit()
    {
        return redirect()->route('admin.employment-statuses.index');
    }
}
