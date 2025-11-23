<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = LeaveRequest::with('leaveType')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::all();
        return view('leaves.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        // Calculate Duration
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $days = $start->diffInDays($end) + 1;

        // Check Balance
        $balance = \App\Models\LeaveBalance::where('user_id', Auth::id())
            ->where('leave_type_id', $validated['leave_type_id'])
            ->first();

        if (!$balance) {
            // Create default balance if not exists (for MVP)
            $balance = \App\Models\LeaveBalance::create([
                'user_id' => Auth::id(),
                'leave_type_id' => $validated['leave_type_id'],
                'year' => date('Y'),
                'total_days' => 20, // Default allowance
                'used_days' => 0,
            ]);
        }

        $remaining = $balance->total_days - $balance->used_days;

        if ($days > $remaining) {
            return back()->withErrors(['start_date' => "Insufficient leave balance. You have $remaining days remaining, but requested $days days."])->withInput();
        }

        // Create Request
        LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);
        
        // Note: Balance is deducted upon APPROVAL, not request. 
        // Logic for deduction should be in LeaveApprovalController.

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }
}
