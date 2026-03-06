<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ApprovalChain;
use App\Models\ApprovalChainStep;
use Spatie\Permission\Models\Role;
use App\Models\User;

class ApprovalChainController extends Controller
{
    public function index()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('approval_chains')) {
            $chains = collect();
            return view('admin.approval-chains.index', compact('chains'))->with('error', 'The Approval system is not fully initialized. Please run the repair tool.');
        }

        $chains = ApprovalChain::withCount('steps')->get();
        return view('admin.approval-chains.index', compact('chains'));
    }


    public function create()
    {
        $modules = [
            'App\Models\LeaveRequest' => 'Leave Request',
            'App\Models\SalaryAdvance' => 'Salary Advance',
            'App\Models\LoanDeduction' => 'Loan Deduction',
        ];
        return view('admin.approval-chains.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'module_type' => 'required|string|unique:approval_chains,module_type',
        ]);

        ApprovalChain::create($request->all());

        return redirect()->route('admin.approval-chains.index')->with('success', 'Approval Chain created successfully.');
    }

    public function show(ApprovalChain $approvalChain)
    {
        $approvalChain->load('steps');
        $roles = Role::all();
        $users = User::all();
        $types = [
            'role' => 'Role',
            'specific_user' => 'Specific User',
            'line_manager' => 'Line Manager',
        ];
        return view('admin.approval-chains.show', compact('approvalChain', 'roles', 'users', 'types'));
    }

    public function destroy(ApprovalChain $approvalChain)
    {
        $approvalChain->delete();
        return redirect()->route('admin.approval-chains.index')->with('success', 'Approval Chain deleted successfully.');
    }

    public function storeStep(Request $request, ApprovalChain $chain)
    {
        $request->validate([
            'approver_type' => 'required|string|in:role,specific_user,line_manager',
            'approver_id' => 'required_if:approver_type,role,specific_user',
            'step_order' => 'required|integer',
        ]);

        $chain->steps()->create($request->all());

        return back()->with('success', 'Approval step added successfully.');
    }

    public function destroyStep(ApprovalChainStep $step)
    {
        $step->delete();
        return back()->with('success', 'Approval step removed successfully.');
    }
}
