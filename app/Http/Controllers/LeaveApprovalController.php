<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralStatusChanged;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        $query = LeaveRequest::with(['user', 'leaveType'])
            ->where('status', 'pending');

        // If user is a manager (not HR/Admin), show only their team's requests
        if (!Auth::user()->hasRole(['Admin', 'HR'])) {
            // Get employees managed by this user
            $managedEmployeeIds = \App\Models\Employee::where('manager_id', Auth::id())
                ->pluck('user_id');
            
            $query->whereIn('user_id', $managedEmployeeIds);
        }

        $requests = $query->latest()->paginate(15);

        return view('leaves.approvals', compact('requests'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        // Check permissions
        if (!Auth::user()->can('approve leave')) {
            abort(403, 'Unauthorized');
        }

        // Check if user is the manager or HR
        $employee = \App\Models\Employee::where('user_id', $leaveRequest->user_id)->first();
        if (!Auth::user()->hasRole(['Admin', 'HR']) && $employee->manager_id != Auth::id()) {
            abort(403, 'You can only approve leave for your team members');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Deduct working days from balance (not calendar days)
        $days = \App\Helpers\LeaveHelper::calculateWorkingDays(
            $leaveRequest->start_date,
            $leaveRequest->end_date
        );

        $balance = \App\Models\LeaveBalance::where('user_id', $leaveRequest->user_id)
            ->where('leave_type_id', $leaveRequest->leave_type_id)
            ->first();

        if ($balance) {
            $balance->increment('used_days', $days);
        }

        // Notify Employee
        $leaveRequest->user->notify(new GeneralStatusChanged(
            "Leave Request Approved",
            "Your leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} has been approved.",
            'success',
            route('leaves.index')
        ));

        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        // Check permissions
        if (!Auth::user()->can('approve leave')) { // Assuming same permission for reject
            abort(403, 'Unauthorized');
        }

        // Check if user is the manager or HR
        $employee = \App\Models\Employee::where('user_id', $leaveRequest->user_id)->first();
        if (!Auth::user()->hasRole(['Admin', 'HR']) && $employee->manager_id != Auth::id()) {
            abort(403, 'You can only reject leave for your team members');
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(), // Track who rejected it
            'approved_at' => now(),
        ]);

        // Notify Employee
        $leaveRequest->user->notify(new GeneralStatusChanged(
            "Leave Request Rejected",
            "Your leave request from {$leaveRequest->start_date} to {$leaveRequest->end_date} has been rejected.",
            'error',
            route('leaves.index')
        ));

        return redirect()->back()->with('success', 'Leave request rejected.');
    }
}
