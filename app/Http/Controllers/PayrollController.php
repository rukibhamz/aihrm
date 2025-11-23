<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with('creator')->latest()->paginate(10);
        return view('admin.payroll.index', compact('payrolls'));
    }

    public function create()
    {
        return view('admin.payroll.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030',
        ]);

        // Check if payroll already exists for this period
        if (Payroll::where('month', $validated['month'])->where('year', $validated['year'])->exists()) {
            return back()->withErrors(['error' => 'Payroll for this period already exists.']);
        }

        DB::transaction(function () use ($validated) {
            // 1. Create Payroll Batch
            $payroll = Payroll::create([
                'month' => $validated['month'],
                'year' => $validated['year'],
                'created_by' => Auth::id(),
                'status' => 'draft',
            ]);

            // 2. Calculate Payslips for all active employees with salary structure
            $structures = SalaryStructure::with('user')->get();

            foreach ($structures as $structure) {
                $gross = $structure->gross_salary;
                $deductions = $structure->pension_employee + $structure->tax_paye;
                $net = $structure->net_salary;

                Payslip::create([
                    'payroll_id' => $payroll->id,
                    'user_id' => $structure->user_id,
                    'basic_salary' => $structure->base_salary,
                    'total_allowances' => $gross - $structure->base_salary,
                    'total_deductions' => $deductions,
                    'net_salary' => $net,
                    'breakdown' => [
                        'housing' => $structure->housing_allowance,
                        'transport' => $structure->transport_allowance,
                        'other' => $structure->other_allowances,
                        'pension' => $structure->pension_employee,
                        'tax' => $structure->tax_paye,
                    ],
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('admin.payroll.index')->with('success', 'Payroll generated successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('payslips.user');
        return view('admin.payroll.show', compact('payroll'));
    }

    public function markPaid(Payroll $payroll)
    {
        $payroll->update(['status' => 'paid', 'payment_date' => now()]);
        $payroll->payslips()->update(['status' => 'paid']);
        
        return back()->with('success', 'Payroll marked as paid.');
    }
    
    public function myPayslips()
    {
        $payslips = Payslip::where('user_id', Auth::id())
            ->with('payroll')
            ->latest()
            ->paginate(12);
            
        return view('payslips.index', compact('payslips'));
    }
}
