<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::withCount('employees')->paginate(10);
        return view('admin.designations.index', compact('designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:designations,title',
        ]);

        Designation::create($validated);

        return redirect()->route('admin.designations.index')->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:designations,title,' . $designation->id,
        ]);

        $designation->update($validated);

        return redirect()->route('admin.designations.index')->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        if ($designation->employees()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete designation with assigned employees.']);
        }

        $designation->delete();

        return redirect()->route('admin.designations.index')->with('success', 'Designation deleted successfully.');
    }
}
