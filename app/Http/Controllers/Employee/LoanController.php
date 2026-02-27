<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LoanDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of personal loans.
     */
    public function index()
    {
        $loans = LoanDeduction::with('creator')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('employee.loans.index', compact('loans'));
    }

    /**
     * Store a newly created loan application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_amount' => 'required|numeric|min:0',
            'monthly_deduction' => 'required|numeric|min:0|lte:loan_amount',
            'start_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string|max:500',
        ]);

        // Check if there's already an active loan
        $activeLoan = LoanDeduction::getActiveDeduction(Auth::id());

        if ($activeLoan) {
            return back()->with('error', 'You already have an active loan. Please complete it before requesting another.');
        }

        LoanDeduction::create([
            ...$validated,
            'user_id' => Auth::id(),
            'remaining_balance' => $validated['loan_amount'],
            'status' => 'pending',
        ]);

        return redirect()->route('my-loans.index')->with('success', 'Loan request submitted successfully.');
    }
}
