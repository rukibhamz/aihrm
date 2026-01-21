<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalaryStructure;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function index()
    {
        // List all employees with their salary structure
        $users = User::role('Employee')->with(['salaryStructure', 'employee.department', 'employee.designation'])->paginate(20);
        return view('admin.salary.index', compact('users'));
    }

    public function edit(User $user)
    {
        $structure = $user->salaryStructure ?? new SalaryStructure(['user_id' => $user->id]);
        return view('admin.salary.edit', compact('user', 'structure'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'pension_employee' => 'nullable|numeric|min:0',
            'tax_paye' => 'nullable|numeric|min:0',
        ]);

        $salaryStructure = SalaryStructure::updateOrCreate(
            ['user_id' => $user->id],
            [
                'base_salary' => $validated['base_salary'],
                'housing_allowance' => $validated['housing_allowance'] ?? 0,
                'transport_allowance' => $validated['transport_allowance'] ?? 0,
                'other_allowances' => $validated['other_allowances'] ?? 0,
                'pension_employee' => $validated['pension_employee'] ?? 0,
                'tax_paye' => $validated['tax_paye'] ?? 0,
            ]
        );

        return redirect()->route('admin.salary.index')
            ->with('success', 'Salary structure updated for ' . $user->name);
    }
}
