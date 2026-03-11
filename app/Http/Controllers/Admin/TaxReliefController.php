<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaxReliefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reliefs = \App\Models\TaxRelief::latest()->get();
        return view('admin.tax-reliefs.index', compact('reliefs'));
    }

    public function create()
    {
        $users = \App\Models\User::role('Employee')->get();
        return view('admin.tax-reliefs.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed_amount,percentage_of_gross',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $relief = \App\Models\TaxRelief::create([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->has('user_ids')) {
            $relief->employees()->sync($request->user_ids);
        }

        return redirect()->route('admin.tax-reliefs.index')->with('success', 'Tax Relief created successfully.');
    }

    public function edit(\App\Models\TaxRelief $taxRelief)
    {
        $users = \App\Models\User::role('Employee')->get();
        return view('admin.tax-reliefs.edit', compact('taxRelief', 'users'));
    }

    public function update(Request $request, \App\Models\TaxRelief $taxRelief)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed_amount,percentage_of_gross',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $taxRelief->update([
            'name' => $request->name,
            'type' => $request->type,
            'amount' => $request->amount,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        if ($request->has('user_ids')) {
            $taxRelief->employees()->sync($request->user_ids);
        } else {
            $taxRelief->employees()->detach();
        }

        return redirect()->route('admin.tax-reliefs.index')->with('success', 'Tax Relief updated successfully.');
    }

    public function destroy(\App\Models\TaxRelief $taxRelief)
    {
        $taxRelief->delete();
        return redirect()->route('admin.tax-reliefs.index')->with('success', 'Tax Relief deleted successfully.');
    }
}
