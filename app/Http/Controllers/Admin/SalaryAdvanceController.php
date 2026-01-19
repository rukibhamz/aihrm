<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryAdvance;
use App\Models\User;
use Illuminate\Http\Request;
class SalaryAdvanceController extends Controller
{
    public function index()
    {
        $advances = SalaryAdvance::with(['user', 'approver'])->latest()->paginate(10);
        $employees = User::whereHas('roles', function($q) {
            $q->where('name', 'employee');
        })->get();
        
        return view('admin.advances.index', compact('advances', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'deduction_month' => 'required|integer|min:1|max:12',
            'deduction_year' => 'required|integer|min:2024|max:2030',
            'reason' => 'nullable|string|max:500',
        ]);

        SalaryAdvance::create([
            ...$validated,
            'status' => 'pending',
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('admin.advances.index')->with('success', 'Salary advance added successfully.');
    }

    public function destroy(SalaryAdvance $advance)
    {
        if ($advance->status === 'deducted') {
            return back()->withErrors(['error' => 'Cannot delete an advance that has already been deducted.']);
        }
        
        $advance->delete();
        return back()->with('success', 'Salary advance deleted successfully.');
    }
}
