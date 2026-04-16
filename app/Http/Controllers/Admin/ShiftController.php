<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::all();
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'grace_period_minutes' => 'required|integer|min:0',
            'is_default' => 'boolean',
        ]);

        if ($request->has('is_default') && $request->is_default) {
            Shift::where('is_default', true)->update(['is_default' => false]);
        }

        Shift::create($validated);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift created successfully.');
    }

    public function edit(Shift $shift)
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'grace_period_minutes' => 'required|integer|min:0',
            'is_default' => 'boolean',
        ]);

        if ($request->has('is_default') && $request->is_default) {
            Shift::where('id', '!=', $shift->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $shift->update($validated);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        if ($shift->users()->count() > 0) {
            return back()->with('error', 'Cannot delete shift with assigned users.');
        }
        $shift->delete();
        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted successfully.');
    }
}
