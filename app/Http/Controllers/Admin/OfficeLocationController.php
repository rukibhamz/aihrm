<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficeLocation;
use Illuminate\Http\Request;

class OfficeLocationController extends Controller
{
    public function index()
    {
        $locations = OfficeLocation::all();
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:1',
            'is_default' => 'boolean',
        ]);

        if ($request->has('is_default') && $request->is_default) {
            OfficeLocation::where('is_default', true)->update(['is_default' => false]);
        }

        OfficeLocation::create($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(OfficeLocation $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, OfficeLocation $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meters' => 'required|integer|min:1',
            'is_default' => 'boolean',
        ]);

        if ($request->has('is_default') && $request->is_default) {
            OfficeLocation::where('id', '!=', $location->id)->where('is_default', true)->update(['is_default' => false]);
        }

        $location->update($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(OfficeLocation $location)
    {
        if ($location->users()->count() > 0) {
            return back()->with('error', 'Cannot delete location with assigned users.');
        }
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully.');
    }
}
