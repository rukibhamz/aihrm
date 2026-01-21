<?php

namespace App\Http\Controllers;

use App\Models\FinancialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralStatusChanged;

class FinancialApprovalController extends Controller
{
    public function index()
    {
        // Show pending financial requests for approval
        $query = FinancialRequest::with(['user', 'category'])
            ->whereIn('status', ['pending', 'approved_manager']);

        // Managers see pending requests (not yet approved by manager)
        if (Auth::user()->hasRole('Manager')) {
            $query->where('status', 'pending');
        }

        // Finance sees manager-approved requests
        if (Auth::user()->hasRole('Finance')) {
            $query->where('status', 'approved_manager');
        }

        $requests = $query->latest()->paginate(15);

        return view('finance.approvals', compact('requests'));
    }

    public function approveManager(FinancialRequest $financialRequest)
    {
        if (!Auth::user()->can('approve financial request')) {
            abort(403, 'Unauthorized');
        }

        $financialRequest->update([
            'status' => 'approved_manager',
            'approved_by_manager' => Auth::id(),
        ]);

        // Notify Employee
        $financialRequest->user->notify(new GeneralStatusChanged(
            "Expense Claim Reviewed",
            "Your claim for {$financialRequest->amount} has been approved by your manager and sent to Finance.",
            'info',
            route('finance.index')
        ));

        return redirect()->back()->with('success', 'Financial request approved. Sent to Finance for final approval.');
    }

    public function approveFinance(FinancialRequest $financialRequest)
    {
        if (!Auth::user()->can('approve financial request')) {
            abort(403, 'Unauthorized');
        }

        $financialRequest->update([
            'status' => 'approved_finance',
            'approved_by_finance' => Auth::id(),
        ]);

        // Notify Employee
        $financialRequest->user->notify(new GeneralStatusChanged(
            "Expense Claim Approved",
            "Your claim for {$financialRequest->amount} has been approved by Finance.",
            'success',
            route('finance.index')
        ));

        return redirect()->back()->with('success', 'Financial request approved by Finance.');
    }

    public function markPaid(FinancialRequest $financialRequest)
    {
        if (!Auth::user()->can('mark as paid')) {
            abort(403, 'Unauthorized');
        }

        $financialRequest->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Notify Employee
        $financialRequest->user->notify(new GeneralStatusChanged(
            "Expense Claim Paid",
            "The payment for your claim of {$financialRequest->amount} has been processed.",
            'success',
            route('finance.index')
        ));

        return redirect()->back()->with('success', 'Financial request marked as paid.');
    }

    public function reject(FinancialRequest $financialRequest)
    {
        if (!Auth::user()->can('reject financial request')) {
            abort(403, 'Unauthorized');
        }

        $financialRequest->update([
            'status' => 'rejected',
        ]);

        // Notify Employee
        $financialRequest->user->notify(new GeneralStatusChanged(
            "Expense Claim Rejected",
            "Your claim for {$financialRequest->amount} has been rejected.",
            'error',
            route('finance.index')
        ));

        return redirect()->back()->with('success', 'Financial request rejected.');
    }
}
