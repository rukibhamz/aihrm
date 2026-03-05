<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ApprovalRequest;
use App\Models\ApprovalLog;
use Illuminate\Support\Facades\Auth;

class ApprovalInboxController extends Controller
{
    public function index()
    {
        // For AIHRM, we'll implement a query that finds requests where:
        // 1. The request status is 'pending'
        // 2. The Current Step order matches a step in the chain where:
        //    - Type is 'line_manager' AND User's manager is Auth::user()
        //    - Type is 'role' AND Auth::user() has that role
        //    - Type is 'specific_user' AND Auth::user()->id is approver_id
        
        $pendingRequests = ApprovalRequest::with(['approvable', 'chain.steps'])
            ->where('status', 'pending')
            ->get()
            ->filter(function($request) {
                $step = $request->currentStep();
                if (!$step) return false;

                if ($step->approver_type === 'line_manager') {
                    return $request->approvable->user->manager_id === Auth::id();
                }

                if ($step->approver_type === 'role') {
                    // Check if user has role (Spatie)
                    return Auth::user()->hasRole($step->approver_id) || Auth::user()->roles->where('id', $step->approver_id)->count() > 0;
                }

                if ($step->approver_type === 'specific_user') {
                    return (string)$step->approver_id === (string)Auth::id();
                }

                return false;
            });

        return view('admin.approvals.index', compact('pendingRequests'));
    }

    public function show(ApprovalRequest $request)
    {
        $request->load(['approvable.user', 'chain.steps', 'logs.user']);
        return view('admin.approvals.show', compact('request'));
    }

    public function act(Request $request, ApprovalRequest $approvalRequest)
    {
        $request->validate([
            'action' => 'required|in:approved,rejected',
            'comments' => 'nullable|string',
        ]);

        $step = $approvalRequest->currentStep();

        // Write Log
        $approvalRequest->logs()->create([
            'user_id' => Auth::id(),
            'step_order' => $approvalRequest->current_step_order,
            'action' => $request->action,
            'comments' => $request->comments,
        ]);

        if ($request->action === 'rejected') {
            $approvalRequest->update(['status' => 'rejected', 'completed_at' => now()]);
            // Update the source model
            $approvalRequest->approvable->update(['status' => 'rejected']);
        } else {
            // Check if there are more steps
            $nextStep = $approvalRequest->chain->steps()
                ->where('step_order', '>', $approvalRequest->current_step_order)
                ->first();

            if ($nextStep) {
                $approvalRequest->increment('current_step_order');
            } else {
                // Final Step Approved
                $approvalRequest->update(['status' => 'approved', 'completed_at' => now()]);
                
                // Update the source model
                $approvalRequest->approvable->update(['status' => 'approved']);
            }
        }

        return redirect()->route('approvals.index')->with('success', 'Action recorded successfully.');
    }
}
