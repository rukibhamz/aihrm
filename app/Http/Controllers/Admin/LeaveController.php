<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['user', 'leaveType'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $leaves = $query->paginate(15)->withQueryString();
        $leaveTypes = \App\Models\LeaveType::all();

        return view('admin.leaves.index', compact('leaves', 'leaveTypes'));
    }

    public function update(Request $request, LeaveRequest $leave)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $leave->update([
            'status' => $validated['status'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // If approved, deduct balance
        if ($validated['status'] === 'approved') {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);
            $days = $start->diffInDays($end) + 1;

            $balance = \App\Models\LeaveBalance::firstOrCreate(
                [
                    'user_id' => $leave->user_id,
                    'leave_type_id' => $leave->leave_type_id
                ],
                ['used_days' => 0, 'total_days' => 0] // Default if not exists
            );

            $balance->increment('used_days', $days);
        }

        return redirect()->back()->with('success', 'Leave request updated successfully.');
    }
    public function calendar(Request $request)
    {
        $date = $request->input('date') ? \Carbon\Carbon::parse($request->date) : \Carbon\Carbon::today();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $leaves = LeaveRequest::with(['user', 'leaveType'])
            ->where('status', 'approved')
            ->where(function($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                      ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                      ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                          $q->where('start_date', '<', $startOfMonth)
                            ->where('end_date', '>', $endOfMonth);
                      });
            })
            ->get();

        return view('admin.leaves.calendar', compact('leaves', 'date'));
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'leave_ids' => 'required|array',
            'leave_ids.*' => 'exists:leave_requests,id'
        ]);

        $count = 0;
        foreach ($request->leave_ids as $id) {
            $leave = LeaveRequest::find($id);
            if ($leave && $leave->status === 'pending') {
                $leave->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Deduct balance
                $start = \Carbon\Carbon::parse($leave->start_date);
                $end = \Carbon\Carbon::parse($leave->end_date);
                $days = $start->diffInDays($end) + 1;

                $balance = \App\Models\LeaveBalance::firstOrCreate(
                    ['user_id' => $leave->user_id, 'leave_type_id' => $leave->leave_type_id],
                    ['used_days' => 0, 'total_days' => 0]
                );
                $balance->increment('used_days', $days);
                $count++;
            }
        }

        return back()->with('success', "Approved {$count} leave requests.");
    }

    public function bulkReject(Request $request)
    {
        $request->validate([
            'leave_ids' => 'required|array',
            'leave_ids.*' => 'exists:leave_requests,id'
        ]);

        $count = LeaveRequest::whereIn('id', $request->leave_ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        return back()->with('success', "Rejected {$count} leave requests.");
    }
}
