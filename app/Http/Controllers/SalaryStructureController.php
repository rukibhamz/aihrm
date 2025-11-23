<?php

namespace App\Http\Controllers;

use App\Models\SalaryStructure;
use App\Models\Employee;
use Illuminate\Http\Request;

class SalaryStructureController extends Controller
{
    public function index()
    {
        $structures = SalaryStructure::with(['user.employee.department', 'user.employee.designation'])->paginate(15);
        return view('admin.salary.index', compact('structures'));
    }

    public function create()
    {
        // Get employees who don't have a salary structure yet
        $existingUserIds = SalaryStructure::pluck('user_id');
        $employees = Employee::with('user')
            ->whereNotIn('user_id', $existingUserIds)
            ->where('status', 'active')
            ->get();

        return view('admin.salary.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:salary_structures,user_id',
            'base_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'pension_employee' => 'nullable|numeric|min:0',
            'tax_paye' => 'nullable|numeric|min:0',
        ]);

        SalaryStructure::create($validated);

        return redirect()->route('admin.salary.index')->with('success', 'Salary structure assigned successfully.');
    }

    public function edit(SalaryStructure $salary)
    {
        return view('admin.salary.edit', compact('salary'));
    }

    public function update(Request $request, SalaryStructure $salary)
    {
        $validated = $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'pension_employee' => 'nullable|numeric|min:0',
            'tax_paye' => 'nullable|numeric|min:0',
        ]);

        $salary->update($validated);

        return redirect()->route('admin.salary.index')->with('success', 'Salary structure updated successfully.');
    }
}
