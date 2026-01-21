<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanDeduction;
use App\Models\User;
use Illuminate\Http\Request;
class LoanDeductionController extends Controller
{
    public function index()
    {
        $loans = LoanDeduction::with(['user', 'creator'])->latest()->paginate(10);
        $employees = User::whereHas('roles', function($q) {
            $q->where('name', 'employee');
        })->get();
        
        return view('admin.loans.index', compact('loans', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'loan_amount' => 'required|numeric|min:0',
            'monthly_deduction' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ]);

        LoanDeduction::create([
            ...$validated,
            'remaining_balance' => $validated['loan_amount'],
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.loans.index')->with('success', 'Loan added successfully.');
    }

    public function destroy(LoanDeduction $loan)
    {
        $loan->delete();
        return back()->with('success', 'Loan deleted successfully.');
    }
}
