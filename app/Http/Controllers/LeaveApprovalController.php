<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        // Show pending leave requests for approval
        // Managers see their team's requests
        // HR sees all requests
        
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
        // Check if user has permission to approve
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
        ]);

        return redirect()->back()->with('success', 'Leave request approved successfully.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    public function index()
    {
        // Show pending leave requests for approval
        // Managers see their team's requests
        // HR sees all requests
        
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

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        // Check if user has permission to approve/reject
        if (!Auth::user()->can('approve leave') && !Auth::user()->can('reject leave')) {
            abort(403, 'Unauthorized');
        }

        // Check if user is the manager or HR
        $employee = \App\Models\Employee::where('user_id', $leaveRequest->user_id)->first();
        
        if (!Auth::user()->hasRole(['Admin', 'HR']) && $employee->manager_id != Auth::id()) {
            abort(403, 'You can only approve/reject leave for your team members');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|required_if:status,rejected|string|max:255',
        ]);

        $leaveRequest->update([
            'status' => $validated['status'],
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Deduct balance if approved
        if ($validated['status'] === 'approved') {
            $start = \Carbon\Carbon::parse($leaveRequest->start_date);
            $end = \Carbon\Carbon::parse($leaveRequest->end_date);
            $days = $start->diffInDays($end) + 1;

            $balance = \App\Models\LeaveBalance::where('user_id', $leaveRequest->user_id)
                ->where('leave_type_id', $leaveRequest->leave_type_id)
                ->first();

            if ($balance) {
                $balance->increment('used_days', $days);
            }
        }

        return redirect()->route('leaves.approvals')->with('success', 'Leave request ' . $validated['status']);
    }
}
