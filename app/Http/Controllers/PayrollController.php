<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\SalaryStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function downloadPayslip(Payslip $payslip)
    {
        // Check if user has permission to view this payslip
        if (Auth::id() !== $payslip->user_id && !Auth::user()->hasRole('Admin')) {
            abort(403);
        }

        $payslip->load(['user.employee.department', 'payroll']);
        
        $pdf = Pdf::loadView('payslips.pdf', compact('payslip'));
        
        $filename = "payslip-{$payslip->payroll->month}-{$payslip->payroll->year}.pdf";
        return $pdf->download($filename);
    }
    public function index()
    {
        $payrolls = Payroll::with(['creator:id,name'])
            ->select('id', 'month', 'year', 'status', 'payment_date', 'created_by', 'created_at')
            ->latest()
            ->paginate(10);
            
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
                // Calculate expected working days for the month
                $expectedDays = $this->getWorkingDaysInMonth($validated['month'], $validated['year']);
                
                // Get actual worked days from attendance
                $workedDays = $this->getWorkedDays($structure->user_id, $validated['month'], $validated['year']);
                
                // Calculate pro-rata salary based on attendance
                $attendanceRatio = $expectedDays > 0 ? ($workedDays / $expectedDays) : 1;
                $proRataBasic = $structure->base_salary * $attendanceRatio;
                $proRataAllowances = ($structure->housing_allowance + $structure->transport_allowance + $structure->other_allowances) * $attendanceRatio;
                
                // Calculate overtime
                $overtimeHours = $this->getOvertimeHours($structure->user_id, $validated['month'], $validated['year']);
                $hourlyRate = $structure->hourly_rate ?? ($structure->base_salary / (8 * $expectedDays));
                $overtimeAmount = $overtimeHours * $hourlyRate * $structure->overtime_rate;
                
                // Get bonuses for this period
                $bonusAmount = \App\Models\Bonus::forPeriod($structure->user_id, $validated['month'], $validated['year']);
                
                // Calculate gross
                $gross = $proRataBasic + $proRataAllowances + $overtimeAmount + $bonusAmount;
                
                // Calculate standard deductions (pro-rated)
                $pensionDeduction = $structure->pension_employee * $attendanceRatio;
                $taxDeduction = $structure->tax_paye * $attendanceRatio;
                
                // Get loan deduction
                $loanDeduction = 0;
                $activeLoan = \App\Models\LoanDeduction::getActiveDeduction($structure->user_id);
                if ($activeLoan) {
                    $loanDeduction = $activeLoan->processDeduction();
                }
                
                // Get salary advance deduction
                $advanceDeduction = 0;
                $pendingAdvance = \App\Models\SalaryAdvance::getPendingAdvance($structure->user_id, $validated['month'], $validated['year']);
                if ($pendingAdvance) {
                    $advanceDeduction = $pendingAdvance->amount;
                    $pendingAdvance->markAsDeducted();
                }
                
                // Total deductions
                $totalDeductions = $pensionDeduction + $taxDeduction + $loanDeduction + $advanceDeduction;
                
                // Net salary
                $net = $gross - $totalDeductions;

                Payslip::create([
                    'payroll_id' => $payroll->id,
                    'user_id' => $structure->user_id,
                    'basic_salary' => $proRataBasic,
                    'overtime_hours' => $overtimeHours,
                    'overtime_amount' => $overtimeAmount,
                    'bonus_amount' => $bonusAmount,
                    'total_allowances' => $proRataAllowances,
                    'total_deductions' => $totalDeductions,
                    'loan_deduction' => $loanDeduction,
                    'advance_deduction' => $advanceDeduction,
                    'net_salary' => $net,
                    'worked_days' => $workedDays,
                    'expected_days' => $expectedDays,
                    'breakdown' => [
                        'housing' => $structure->housing_allowance * $attendanceRatio,
                        'transport' => $structure->transport_allowance * $attendanceRatio,
                        'other' => $structure->other_allowances * $attendanceRatio,
                        'overtime' => $overtimeAmount,
                        'bonus' => $bonusAmount,
                        'pension' => $pensionDeduction,
                        'tax' => $taxDeduction,
                        'loan' => $loanDeduction,
                        'advance' => $advanceDeduction,
                    ],
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('admin.payroll.index')->with('success', 'Enterprise payroll generated successfully with attendance, overtime, bonuses, and deductions.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['payslips' => function ($query) {
            $query->with(['user:id,name,email'])
                ->select('id', 'payroll_id', 'user_id', 'basic_salary', 'total_allowances', 
                         'overtime_amount', 'bonus_amount', 'total_deductions', 'net_salary', 
                         'worked_days', 'expected_days', 'status');
        }]);
        
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
    
    /**
     * Calculate working days in a month
     */
    private function getWorkingDaysInMonth($month, $year)
    {
        $startDate = \Carbon\Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        return \App\Helpers\LeaveHelper::calculateWorkingDays($startDate, $endDate);
    }
    
    /**
     * Get actual worked days from attendance
     */
    private function getWorkedDays($userId, $month, $year)
    {
        $startDate = \Carbon\Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        return \App\Models\Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('clock_out')
            ->count();
    }
    
    /**
     * Calculate overtime hours for the month
     */
    private function getOvertimeHours($userId, $month, $year)
    {
        $startDate = \Carbon\Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $attendances = \App\Models\Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->whereNotNull('clock_out')
            ->get();
        
        $overtimeHours = 0;
        $standardHours = 8; // Standard work hours per day
        
        foreach ($attendances as $attendance) {
            $clockIn = \Carbon\Carbon::parse($attendance->clock_in);
            $clockOut = \Carbon\Carbon::parse($attendance->clock_out);
            $hoursWorked = $clockOut->diffInHours($clockIn);
            
            if ($hoursWorked > $standardHours) {
                $overtimeHours += ($hoursWorked - $standardHours);
            }
        }
        
        
        return $overtimeHours;
    }

    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Get all active employees with salary structures
        $employees = \App\Models\Employee::with(['user', 'salaryStructure'])
            ->where('status', 'active')
            ->whereHas('salaryStructure')
            ->get();

        $count = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($employees as $employee) {
                // Check if payroll already exists
                $exists = Payroll::where('user_id', $employee->user_id)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->exists();

                if ($exists) {
                    $errors[] = "{$employee->user->name} - Payroll already exists";
                    continue;
                }

                // Use the same logic as store() method
                $salary = $employee->salaryStructure;
                $basicSalary = $salary->basic_salary;
                $allowances = ($salary->housing_allowance ?? 0) + ($salary->transport_allowance ?? 0) + ($salary->other_allowances ?? 0);
                $grossSalary = $basicSalary + $allowances;

                // Calculate deductions
                $tax = $grossSalary * 0.10; // 10% tax
                $pension = $basicSalary * 0.08; // 8% pension
                $deductions = $tax + $pension;

                $netSalary = $grossSalary - $deductions;

                Payroll::create([
                    'user_id' => $employee->user_id,
                    'month' => $month,
                    'year' => $year,
                    'basic_salary' => $basicSalary,
                    'allowances' => $allowances,
                    'deductions' => $deductions,
                    'gross_salary' => $grossSalary,
                    'net_salary' => $netSalary,
                    'status' => 'pending',
                ]);

                $count++;
            }

            DB::commit();
            
            $message = "Successfully generated payroll for {$count} employees.";
            if (count($errors) > 0) {
                $message .= " Skipped: " . implode(', ', $errors);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Bulk payroll generation failed: ' . $e->getMessage()]);
        }
    }
}
