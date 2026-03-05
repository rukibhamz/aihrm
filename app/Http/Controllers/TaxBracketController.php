<?php

namespace App\Http\Controllers;

use App\Models\TaxBracket;
use Illuminate\Http\Request;

class TaxBracketController extends Controller
{
    public function index()
    {
        $brackets = TaxBracket::orderBy('min_salary')->get();
        return view('admin.tax-brackets.index', compact('brackets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'nullable|numeric|gte:min_salary',
            'rate_percentage' => 'required|numeric|min:0|max:100',
            'fixed_amount_addition' => 'required|numeric|min:0',
        ]);

        TaxBracket::create($validated);

        return redirect()->route('admin.tax-brackets.index')->with('success', 'Tax bracket created successfully.');
    }

    public function update(Request $request, TaxBracket $taxBracket)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'min_salary' => 'required|numeric|min:0',
            'max_salary' => 'nullable|numeric|gte:min_salary',
            'rate_percentage' => 'required|numeric|min:0|max:100',
            'fixed_amount_addition' => 'required|numeric|min:0',
        ]);

        $taxBracket->update($validated);

        return redirect()->route('admin.tax-brackets.index')->with('success', 'Tax bracket updated successfully.');
    }

    public function destroy(TaxBracket $taxBracket)
    {
        $taxBracket->delete();
        return redirect()->route('admin.tax-brackets.index')->with('success', 'Tax bracket deleted successfully.');
    }
}
