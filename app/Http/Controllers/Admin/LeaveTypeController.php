<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::withCount('requests')->get();
        return view('admin.leave_types.index', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'days_allowed' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_paid' => 'boolean',
        ]);

        LeaveType::create($validated);

        return redirect()->back()->with('success', 'Leave Type created successfully.');
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name,' . $leaveType->id,
            'days_allowed' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_paid' => 'boolean',
        ]);

        $leaveType->update($validated);

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
