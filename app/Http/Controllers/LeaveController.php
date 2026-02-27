<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\User;
use App\Notifications\ReliefOfficerAssigned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        // Check if viewing another employee's leaves (admin feature)
        $userId = $request->query('user_id', Auth::id());
        
        $leaves = LeaveRequest::with('leaveType')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);
            
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        $employee = Auth::user()->employee;
        $statusId = $employee ? $employee->employment_status_id : null;
        $departmentId = $employee ? $employee->department_id : null;

        $leaveTypes = LeaveType::whereDoesntHave('employmentStatuses')
            ->orWhereHas('employmentStatuses', function($q) use ($statusId) {
                $q->where('employment_status_id', $statusId);
            })->get();

        $users = User::whereHas('employee', function($q) use ($departmentId) {
            if ($departmentId) {
                $q->where('department_id', $departmentId);
            }
        })->where('id', '!=', Auth::id())->where('status', 'active')->get();

        return view('leaves.create', compact('leaveTypes', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $employee = Auth::user()->employee;
        $leaveType = LeaveType::find($validated['leave_type_id']);

        // Gender-based restrictions
        if ($leaveType->name === 'Maternity Leave' && $employee->gender !== 'female') {
            return back()->withErrors(['leave_type_id' => 'Maternity Leave is only available for female employees.'])->withInput();
        }

        if ($leaveType->name === 'Paternity Leave' && $employee->gender !== 'male') {
            return back()->withErrors(['leave_type_id' => 'Paternity Leave is only available for male employees.'])->withInput();
        }

        // Calculate Working Days (not calendar days)
        $days = \App\Helpers\LeaveHelper::calculateWorkingDays(
            $validated['start_date'],
            $validated['end_date']
        );

        // Check Balance
        $balance = \App\Models\LeaveBalance::where('user_id', Auth::id())
            ->where('leave_type_id', $validated['leave_type_id'])
            ->first();

        if (!$balance) {
            $allowedDays = $leaveType->getDaysAllowedForUser(Auth::user());
            
            // Create default balance if not exists (for MVP)
            $balance = \App\Models\LeaveBalance::create([
                'user_id' => Auth::id(),
                'leave_type_id' => $validated['leave_type_id'],
                'year' => date('Y'),
                'total_days' => $allowedDays,
                'used_days' => 0,
            ]);
        }

        $remaining = $balance->total_days - $balance->used_days;

        if ($days > $remaining) {
            return back()->withErrors(['start_date' => "Insufficient leave balance. You have $remaining working days remaining, but requested $days working days."])->withInput();
        }

        // Create Request
        $leaveRequest = LeaveRequest::create([
            'user_id' => Auth::id(),
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'relief_officer_id' => $request->relief_officer_id,
            'relief_officer_status' => $request->relief_officer_id ? 'pending' : 'none',
            'status' => 'pending',
        ]);
        
        // Notify Relief Officer
        if ($leaveRequest->relief_officer_id) {
            $reliefOfficer = User::find($leaveRequest->relief_officer_id);
            $reliefOfficer->notify(new ReliefOfficerAssigned($leaveRequest));
        }
        
        // Note: Balance is deducted upon APPROVAL, not request. 
        // Logic for deduction should be in LeaveApprovalController.

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    public function show(LeaveRequest $leaf)
    {
        $leaf->load(['leaveType', 'user.employee']);
        return view('leaves.show', compact('leaf'));
    }

    public function reliefRequests()
    {
        $requests = LeaveRequest::with(['user.employee', 'leaveType'])
            ->where('relief_officer_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('leaves.relief-requests', compact('requests'));
    }

    public function edit(LeaveRequest $leaf)
    {
        // Only allow editing if status is pending and user owns the request
        if ($leaf->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($leaf->status !== 'pending') {
            return redirect()->route('leaves.show', $leaf)
                ->with('error', 'Only pending leave requests can be edited.');
        }

        $employee = Auth::user()->employee;
        $statusId = $employee ? $employee->employment_status_id : null;
        $departmentId = $employee ? $employee->department_id : null;

        $leaveTypes = LeaveType::whereDoesntHave('employmentStatuses')
            ->orWhereHas('employmentStatuses', function($q) use ($statusId) {
                $q->where('employment_status_id', $statusId);
            })->get();

        $users = User::whereHas('employee', function($q) use ($departmentId) {
            if ($departmentId) {
                $q->where('department_id', $departmentId);
            }
        })->where('id', '!=', Auth::id())->where('status', 'active')->get();
        
        return view('leaves.edit', compact('leaf', 'leaveTypes', 'users'));
    }

    public function update(Request $request, LeaveRequest $leaf)
    {
        // Only allow updating if status is pending and user owns the request
        if ($leaf->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($leaf->status !== 'pending') {
            return redirect()->route('leaves.show', $leaf)
                ->with('error', 'Only pending leave requests can be updated.');
        }

        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $employee = Auth::user()->employee;
        $leaveType = LeaveType::find($validated['leave_type_id']);

        // Gender-based restrictions
        if ($leaveType->name === 'Maternity Leave' && $employee->gender !== 'female') {
            return back()->withErrors(['leave_type_id' => 'Maternity Leave is only available for female employees.'])->withInput();
        }

        if ($leaveType->name === 'Paternity Leave' && $employee->gender !== 'male') {
            return back()->withErrors(['leave_type_id' => 'Paternity Leave is only available for male employees.'])->withInput();
        }

        // Calculate Working Days
        $days = \App\Helpers\LeaveHelper::calculateWorkingDays(
            $validated['start_date'],
            $validated['end_date']
        );

        // Check Balance
        $balance = \App\Models\LeaveBalance::where('user_id', Auth::id())
            ->where('leave_type_id', $validated['leave_type_id'])
            ->first();

        if (!$balance) {
            $balance = \App\Models\LeaveBalance::create([
                'user_id' => Auth::id(),
                'leave_type_id' => $validated['leave_type_id'],
                'year' => date('Y'),
                'total_days' => 20,
                'used_days' => 0,
            ]);
        }

        $remaining = $balance->total_days - $balance->used_days;

        if ($days > $remaining) {
            return back()->withErrors(['start_date' => "Insufficient leave balance. You have $remaining working days remaining, but requested $days working days."])->withInput();
        }

        // Update the leave request
        $leaf->update([
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'relief_officer_id' => $request->relief_officer_id,
            'relief_officer_status' => $request->relief_officer_id ? 'pending' : 'none',
        ]);

        // Notify Relief Officer if changed
        if ($leaf->relief_officer_id && $leaf->wasChanged('relief_officer_id')) {
            $reliefOfficer = User::find($leaf->relief_officer_id);
            $reliefOfficer->notify(new ReliefOfficerAssigned($leaf));
        }

        return redirect()->route('leaves.show', $leaf)
            ->with('success', 'Leave request updated successfully.');
    }

    public function destroy(LeaveRequest $leaf)
    {
        // Only allow cancellation if status is pending and user owns the request
        if ($leaf->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($leaf->status !== 'pending') {
            return redirect()->route('leaves.show', $leaf)
                ->with('error', 'Only pending leave requests can be cancelled.');
        }

        $leaf->delete();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request cancelled successfully.');
    }

    public function updateReliefStatus(Request $request, LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->relief_officer_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'relief_officer_status' => 'required|in:accepted,rejected',
        ]);

        $leaveRequest->update([
            'relief_officer_status' => $validated['relief_officer_status']
        ]);

        $statusText = $validated['relief_officer_status'] === 'accepted' ? 'accepted' : 'rejected';
        
        // Optional: Notify requester that relief was accepted/rejected
        // $leaveRequest->user->notify(new ReliefStatusUpdated($leaveRequest));

        return back()->with('success', "You have $statusText the relief officer request.");
    }
}
