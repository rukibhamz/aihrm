<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $request->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2024|max:2030',
        ]);

        // Check if payroll already exists for this period
        if (Payroll::where('month', $request->month)->where('year', $request->year)->exists()) {
            return back()->withErrors(['error' => 'Payroll for this month already exists.']);
        }

        DB::beginTransaction();

        try {
            // Create Payroll Batch
            $payroll = Payroll::create([
                'month' => $request->month,
                'year' => $request->year,
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            // Get all active employees with salary structure
            $users = User::role('Employee')->with('salaryStructure')->whereHas('salaryStructure')->get();

            foreach ($users as $user) {
                $structure = $user->salaryStructure;

                // Calculate Net
                $gross = $structure->gross_salary;
                $deductions = $structure->pension_employee + $structure->tax_paye;
                $net = $gross - $deductions;

                $breakdown = [
                    'earnings' => [
                        'Basic Salary' => $structure->base_salary,
                        'Housing Allowance' => $structure->housing_allowance,
                        'Transport Allowance' => $structure->transport_allowance,
                        'Other Allowances' => $structure->other_allowances,
                    ],
                    'deductions' => [
                        'Pension' => $structure->pension_employee,
                        'PAYE Tax' => $structure->tax_paye,
                    ]
                ];

                Payslip::create([
                    'payroll_id' => $payroll->id,
                    'user_id' => $user->id,
                    'basic_salary' => $structure->base_salary,
                    'total_allowances' => $gross - $structure->base_salary,
                    'total_deductions' => $deductions,
                    'net_salary' => $net,
                    'breakdown' => json_encode($breakdown),
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.payroll.show', $payroll)
                ->with('success', 'Payroll generated successfully for ' . date('F', mktime(0, 0, 0, $request->month, 10)) . ' ' . $request->year);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate payroll: ' . $e->getMessage());
        }
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('payslips.user.employee.department');
        return view('admin.payroll.show', compact('payroll'));
    }

    public function updateStatus(Request $request, Payroll $payroll)
    {
        if ($request->action === 'mark_paid') {
            $payroll->update(['status' => 'paid', 'payment_date' => now()]);
            $payroll->payslips()->update(['status' => 'paid']);
            return back()->with('success', 'Payroll marked as PAID.');
        }
        
        return back();
    }
}
