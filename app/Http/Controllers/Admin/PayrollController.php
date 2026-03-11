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

            // Fetch tax brackets once for all employees
            $taxBrackets = \App\Models\TaxBracket::orderBy('min_salary')->get();

            // Determine total days in the payroll month
            $totalDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $periodStart = \Carbon\Carbon::create($request->year, $request->month, 1);
            $periodEnd = $periodStart->copy()->endOfMonth();

            // Get all active employees with salary structure and employee profile
            $users = User::role('Employee')->with(['salaryStructure', 'employee'])->whereHas('salaryStructure')->get();

            foreach ($users as $user) {
                $structure = $user->salaryStructure;
                $employee = $user->employee;

                // ── Step 1: Calculate Proration Fraction ──
                $daysWorked = $totalDaysInMonth;

                // 1a. Hire date proration (mid-month joiner)
                if ($employee && $employee->join_date) {
                    $joinDate = \Carbon\Carbon::parse($employee->join_date);
                    if ($joinDate->year == $request->year && $joinDate->month == $request->month) {
                        $daysWorked = $totalDaysInMonth - $joinDate->day + 1;
                    } elseif ($joinDate->greaterThan($periodEnd)) {
                        $daysWorked = 0; // Hasn't joined yet
                    }
                }

                // 1b. Unpaid leave proration
                $unpaidLeaveDays = \App\Models\LeaveRequest::where('user_id', $user->id)
                    ->where('status', 'approved')
                    ->whereHas('leaveType', fn($q) => $q->where('is_paid', false))
                    ->where(function ($q) use ($periodStart, $periodEnd) {
                        $q->whereBetween('start_date', [$periodStart, $periodEnd])
                          ->orWhereBetween('end_date', [$periodStart, $periodEnd]);
                    })
                    ->sum('days');

                $daysWorked = max(0, $daysWorked - $unpaidLeaveDays);
                $prorationFraction = $totalDaysInMonth > 0 ? $daysWorked / $totalDaysInMonth : 0;

                // ── Step 2: Calculate Prorated Earnings ──
                $basic = round($structure->base_salary * $prorationFraction, 2);
                $housingAllowance = round($structure->housing_allowance * $prorationFraction, 2);
                $transportAllowance = round($structure->transport_allowance * $prorationFraction, 2);
                $otherAllowances = round($structure->other_allowances * $prorationFraction, 2);

                $earningsBreakdown = [
                    'Basic Salary' => $basic,
                    'Housing Allowance' => $housingAllowance,
                    'Transport Allowance' => $transportAllowance,
                    'Other Allowances' => $otherAllowances,
                ];

                // Dynamic Earnings (Bonuses are not prorated - they are flat awards)
                $bonusAmount = \App\Models\Bonus::forPeriod($user->id, $request->month, $request->year);
                if ($bonusAmount > 0) {
                    $earningsBreakdown['Bonus'] = $bonusAmount;
                }
                
                // Get Overtime
                $overtimeHours = \App\Models\Attendance::where('user_id', $user->id)
                    ->whereMonth('date', $request->month)
                    ->whereYear('date', $request->year)
                    ->sum('overtime_hours');

                $overtimeEarnings = 0;
                if ($overtimeHours > 0) {
                   $policy = \App\Models\OvertimePolicy::where('is_active', true)->first();
                   $hourlyRate = ($structure->base_salary / $totalDaysInMonth) / ($policy ? $policy->standard_daily_hours : 8);
                   $overtimeEarnings = round($overtimeHours * $hourlyRate * ($policy ? $policy->weekday_multiplier : 1.5), 2);
                   $earningsBreakdown['Overtime'] = $overtimeEarnings;
                   $metaInfo['overtime_hours'] = $overtimeHours;
                }

                $gross = $basic + $housingAllowance + $transportAllowance + $otherAllowances + $bonusAmount + $overtimeEarnings;

                // ── Step 3: Calculate Deductions ──
                // Pension (prorated with salary)
                $pension = round($structure->pension_employee * $prorationFraction, 2);

                // Tax Reliefs
                $taxReliefs = $user->taxReliefs()->wherePivot('is_active', true)->get();
                $totalTaxRelief = 0;
                foreach ($taxReliefs as $relief) {
                    if ($relief->type === 'fixed_amount') {
                        $totalTaxRelief += $relief->amount;
                    } elseif ($relief->type === 'percentage_of_gross') {
                        $totalTaxRelief += ($gross * ($relief->amount / 100));
                    }
                }

                // PAYE Tax - Dynamic from Tax Brackets or fallback to fixed
                $taxableIncome = max(0, $gross - $pension - $totalTaxRelief); // Pension and Reliefs are tax-exempt
                $payeTax = $this->calculatePAYE($taxableIncome, $taxBrackets, $structure->tax_paye, $prorationFraction);

                $deductionsBreakdown = [
                    'Pension' => $pension,
                    'PAYE Tax' => $payeTax,
                ];
                $totalDeductions = $pension + $payeTax;

                $metaInfo = [
                    'proration_fraction' => round($prorationFraction, 4),
                    'days_worked' => $daysWorked,
                    'total_days_in_month' => $totalDaysInMonth,
                    'unpaid_leave_days' => $unpaidLeaveDays,
                ];

                // Dynamic Deductions (Loans & Advances are not prorated)
                $advance = \App\Models\SalaryAdvance::getPendingAdvance($user->id, $request->month, $request->year);
                if ($advance) {
                    $deductionsBreakdown['Salary Advance'] = $advance->amount;
                    $totalDeductions += $advance->amount;
                    $metaInfo['advance_id'] = $advance->id;
                }

                $loan = \App\Models\LoanDeduction::getActiveDeduction($user->id);
                if ($loan) {
                    $loanDeductionAmount = min($loan->monthly_deduction, $loan->remaining_balance);
                    $deductionsBreakdown['Loan Deduction'] = $loanDeductionAmount;
                    $totalDeductions += $loanDeductionAmount;
                    $metaInfo['loan_id'] = $loan->id;
                }

                // Penalties
                $penaltyAmount = \App\Models\Penalty::forPeriod($user->id, $request->month, $request->year);
                if ($penaltyAmount > 0) {
                    $deductionsBreakdown['Penalties'] = $penaltyAmount;
                    $totalDeductions += $penaltyAmount;
                }

                $net = $gross - $totalDeductions;
                if ($net < 0) $net = 0;

                $breakdown = [
                    'earnings' => $earningsBreakdown,
                    'deductions' => $deductionsBreakdown,
                    'meta' => $metaInfo
                ];

                Payslip::create([
                    'payroll_id' => $payroll->id,
                    'user_id' => $user->id,
                    'basic_salary' => $basic,
                    'total_allowances' => $gross - $basic,
                    'total_deductions' => $totalDeductions,
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

    /**
     * Calculate PAYE tax using progressive tax brackets.
     * Falls back to the fixed amount from SalaryStructure if no brackets are configured.
     */
    private function calculatePAYE(float $taxableIncome, $taxBrackets, float $fixedFallback, float $prorationFraction): float
    {
        if ($taxBrackets->isEmpty()) {
            return round($fixedFallback * $prorationFraction, 2);
        }

        $tax = 0;
        foreach ($taxBrackets as $bracket) {
            if ($taxableIncome <= $bracket->min_salary) {
                break;
            }

            $upperLimit = $bracket->max_salary ?? PHP_FLOAT_MAX;
            $taxableInBand = min($taxableIncome, $upperLimit) - $bracket->min_salary;

            if ($taxableInBand > 0) {
                $tax += ($taxableInBand * $bracket->rate_percentage / 100) + $bracket->fixed_amount_addition;
            }
        }

        return round($tax, 2);
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('payslips.user.employee.department');
        return view('admin.payroll.show', compact('payroll'));
    }

    public function updateStatus(Request $request, Payroll $payroll)
    {
        if ($request->action === 'mark_paid') {
            DB::beginTransaction();
            try {
                $payroll->update(['status' => 'paid', 'payment_date' => now()]);
                $payroll->payslips()->update(['status' => 'paid']);

                foreach ($payroll->payslips as $payslip) {
                    $breakdown = json_decode($payslip->breakdown, true);
                    if (isset($breakdown['meta'])) {
                        $meta = $breakdown['meta'];
                        
                        if (isset($meta['advance_id'])) {
                            $advance = \App\Models\SalaryAdvance::find($meta['advance_id']);
                            if ($advance) {
                                $advance->markAsDeducted();
                            }
                        }

                        if (isset($meta['loan_id'])) {
                            $loan = \App\Models\LoanDeduction::find($meta['loan_id']);
                            if ($loan) {
                                $loan->processDeduction();
                            }
                        }
                    }
                }

                DB::commit();
                return back()->with('success', 'Payroll marked as PAID and dynamic deductions processed.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Failed to process payroll payments: ' . $e->getMessage());
            }
        }
        
        return back();
    }

    public function destroy(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Cannot delete a payroll that has already been paid.');
        }

        DB::beginTransaction();
        try {
            $payroll->payslips()->delete();
            $payroll->delete();
            DB::commit();

            return redirect()->route('admin.payroll.index')->with('success', 'Payroll deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete payroll: ' . $e->getMessage());
        }
    }

    public function regenerate(Payroll $payroll)
    {
        if ($payroll->status === 'paid') {
            return back()->with('error', 'Cannot regenerate a payroll that has already been paid.');
        }

        // Delete old payslips, then re-run generation
        DB::beginTransaction();
        try {
            $month = $payroll->month;
            $year = $payroll->year;

            $payroll->payslips()->delete();
            $payroll->delete();

            DB::commit();

            // Simulate a new store request for the same period
            $request = new \Illuminate\Http\Request();
            $request->merge(['month' => $month, 'year' => $year]);

            return $this->store($request);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to regenerate payroll: ' . $e->getMessage());
        }
    }
}
