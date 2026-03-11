<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OvertimePolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policies = \App\Models\OvertimePolicy::latest()->get();
        return view('admin.overtime-policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.overtime-policies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'standard_daily_hours' => 'required|numeric|min:0|max:24',
            'weekday_multiplier' => 'required|numeric|min:1',
            'weekend_multiplier' => 'required|numeric|min:1',
            'holiday_multiplier' => 'required|numeric|min:1',
        ]);

        if ($request->has('is_active')) {
            // Ensure only one policy is active at a time
            \App\Models\OvertimePolicy::where('is_active', true)->update(['is_active' => false]);
        }

        \App\Models\OvertimePolicy::create([
            'name' => $request->name,
            'standard_daily_hours' => $request->standard_daily_hours,
            'weekday_multiplier' => $request->weekday_multiplier,
            'weekend_multiplier' => $request->weekend_multiplier,
            'holiday_multiplier' => $request->holiday_multiplier,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.overtime-policies.index')->with('success', 'Overtime Policy created successfully.');
    }

    public function edit(\App\Models\OvertimePolicy $overtimePolicy)
    {
        return view('admin.overtime-policies.edit', compact('overtimePolicy'));
    }

    public function update(Request $request, \App\Models\OvertimePolicy $overtimePolicy)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'standard_daily_hours' => 'required|numeric|min:0|max:24',
            'weekday_multiplier' => 'required|numeric|min:1',
            'weekend_multiplier' => 'required|numeric|min:1',
            'holiday_multiplier' => 'required|numeric|min:1',
        ]);

        if ($request->has('is_active')) {
            // Ensure only one policy is active at a time
            \App\Models\OvertimePolicy::where('id', '!=', $overtimePolicy->id)->update(['is_active' => false]);
        }

        $overtimePolicy->update([
            'name' => $request->name,
            'standard_daily_hours' => $request->standard_daily_hours,
            'weekday_multiplier' => $request->weekday_multiplier,
            'weekend_multiplier' => $request->weekend_multiplier,
            'holiday_multiplier' => $request->holiday_multiplier,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.overtime-policies.index')->with('success', 'Overtime Policy updated successfully.');
    }

    public function destroy(\App\Models\OvertimePolicy $overtimePolicy)
    {
        $overtimePolicy->delete();
        return redirect()->route('admin.overtime-policies.index')->with('success', 'Overtime Policy deleted successfully.');
    }
}
