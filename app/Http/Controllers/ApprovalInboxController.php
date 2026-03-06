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
        if (!\Illuminate\Support\Facades\Schema::hasTable('approval_requests')) {
            $pendingRequests = collect();
            return view('admin.approvals.index', compact('pendingRequests'))->with('error', 'The Approval system is not fully initialized. Please run the repair tool.');
        }

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

            // Notify Requester
            $requester = $approvalRequest->approvable->user;
            $requester->notify(new \App\Notifications\GeneralStatusChanged(
                "Request Rejected",
                "Your request for " . class_basename($approvalRequest->approvable_type) . " has been rejected.",
                'danger'
            ));
        } else {
            // Check if there are more steps
            $nextStep = $approvalRequest->chain->steps()
                ->where('step_order', '>', $approvalRequest->current_step_order)
                ->orderBy('step_order', 'asc')
                ->first();

            if ($nextStep) {
                $approvalRequest->increment('current_step_order');
                
                // Notify Next Approvers (Implementation logic for finding next approver would go here)
                // For now, we notify the requester about the progress
                $approvalRequest->approvable->user->notify(new \App\Notifications\GeneralStatusChanged(
                    "Request Progressing",
                    "Your request has been approved at Step " . ($approvalRequest->current_step_order - 1) . " and moved to the next stage.",
                    'info'
                ));
            } else {
                // Final Step Approved
                $approvalRequest->update(['status' => 'approved', 'completed_at' => now()]);
                
                // Update the source model
                $approvalRequest->approvable->update(['status' => 'approved']);

                // Notify Requester
                $requester = $approvalRequest->approvable->user;
                $requester->notify(new \App\Notifications\GeneralStatusChanged(
                    "Request Approved",
                    "Congratulations! Your request for " . class_basename($approvalRequest->approvable_type) . " has been fully approved.",
                    'success'
                ));
            }
        }


        return redirect()->route('approvals.index')->with('success', 'Action recorded successfully.');
    }
}
