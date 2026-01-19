<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveBalance;
use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveBalanceController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['leaveBalances.leaveType', 'employee.department'])
            ->whereHas('employee'); // Only employees

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $users = $query->paginate(15)->withQueryString();
        $leaveTypes = LeaveType::all();

        return view('admin.leave_balances.index', compact('users', 'leaveTypes'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'balances' => 'required|array',
            'balances.*.leave_type_id' => 'required|exists:leave_types,id',
            'balances.*.total_days' => 'required|integer|min:0',
            'balances.*.used_days' => 'required|integer|min:0',
        ]);

        foreach ($validated['balances'] as $balanceData) {
            LeaveBalance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'leave_type_id' => $balanceData['leave_type_id']
                ],
                [
                    'total_days' => $balanceData['total_days'],
                    'used_days' => $balanceData['used_days']
                ]
            );
        }

        return redirect()->back()->with('success', 'Leave balances updated successfully.');
    }
}
