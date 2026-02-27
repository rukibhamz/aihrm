<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\SalaryAdvance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryAdvanceController extends Controller
{
    /**
     * Display a listing of personal salary advances.
     */
    public function index()
    {
        $advances = SalaryAdvance::with('approver')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('employee.advances.index', compact('advances'));
    }

    /**
     * Store a newly created salary advance application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'deduction_month' => 'required|integer|min:1|max:12',
            'deduction_year' => 'required|integer|min:2024|max:2030',
            'reason' => 'nullable|string|max:500',
        ]);

        // Check if there's already a pending advance for this period
        $existing = SalaryAdvance::getPendingAdvance(
            Auth::id(), 
            $validated['deduction_month'], 
            $validated['deduction_year']
        );

        if ($existing) {
            return back()->with('error', 'You already have a pending advance request for this month.');
        }

        SalaryAdvance::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('my-advances.index')->with('success', 'Salary advance request submitted successfully.');
    }
}
