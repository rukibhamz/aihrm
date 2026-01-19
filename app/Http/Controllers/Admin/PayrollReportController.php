<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Payslip;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PayrollReportController extends Controller
{
    /**
     * Show reports dashboard
     */
    public function index()
    {
        return view('admin.payroll.reports.index');
    }
    
    /**
     * Payroll summary report
     */
    public function summary(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030',
        ]);
        
        $cacheKey = "payroll_summary_{$validated['month']}_{$validated['year']}";
        
        $data = Cache::remember($cacheKey, 3600, function () use ($validated) {
            $payroll = Payroll::where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->with(['payslips' => function ($query) {
                    $query->select('id', 'payroll_id', 'user_id', 'basic_salary', 'total_allowances', 
                                   'overtime_amount', 'bonus_amount', 'total_deductions', 'net_salary');
                }])
                ->first();
            
            if (!$payroll) {
                return null;
            }
            
            return [
                'total_employees' => $payroll->payslips->count(),
                'total_basic' => $payroll->payslips->sum('basic_salary'),
                'total_allowances' => $payroll->payslips->sum('total_allowances'),
                'total_overtime' => $payroll->payslips->sum('overtime_amount'),
                'total_bonuses' => $payroll->payslips->sum('bonus_amount'),
                'total_deductions' => $payroll->payslips->sum('total_deductions'),
                'total_net' => $payroll->payslips->sum('net_salary'),
                'payroll' => $payroll,
            ];
        });
        
        return view('admin.payroll.reports.summary', compact('data', 'validated'));
    }
    
    /**
     * Department-wise payroll report
     */
    public function byDepartment(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030',
        ]);
        
        $cacheKey = "payroll_dept_{$validated['month']}_{$validated['year']}";
        
        $data = Cache::remember($cacheKey, 3600, function () use ($validated) {
            return DB::table('payslips')
                ->join('payrolls', 'payslips.payroll_id', '=', 'payrolls.id')
                ->join('users', 'payslips.user_id', '=', 'users.id')
                ->join('employees', 'users.id', '=', 'employees.user_id')
                ->join('departments', 'employees.department_id', '=', 'departments.id')
                ->where('payrolls.month', $validated['month'])
                ->where('payrolls.year', $validated['year'])
                ->select(
                    'departments.name as department',
                    DB::raw('COUNT(payslips.id) as employee_count'),
                    DB::raw('SUM(payslips.basic_salary) as total_basic'),
                    DB::raw('SUM(payslips.total_allowances) as total_allowances'),
                    DB::raw('SUM(payslips.overtime_amount) as total_overtime'),
                    DB::raw('SUM(payslips.bonus_amount) as total_bonuses'),
                    DB::raw('SUM(payslips.total_deductions) as total_deductions'),
                    DB::raw('SUM(payslips.net_salary) as total_net')
                )
                ->groupBy('departments.id', 'departments.name')
                ->get();
        });
        
        return view('admin.payroll.reports.department', compact('data', 'validated'));
    }
    
    /**
     * Tax and pension summary
     */
    public function taxPension(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030',
        ]);
        
        $cacheKey = "payroll_tax_pension_{$validated['month']}_{$validated['year']}";
        
        $data = Cache::remember($cacheKey, 3600, function () use ($validated) {
            $payroll = Payroll::where('month', $validated['month'])
                ->where('year', $validated['year'])
                ->with('payslips')
                ->first();
            
            if (!$payroll) {
                return null;
            }
            
            $totalTax = 0;
            $totalPension = 0;
            
            foreach ($payroll->payslips as $payslip) {
                $breakdown = $payslip->breakdown;
                $totalTax += $breakdown['tax'] ?? 0;
                $totalPension += $breakdown['pension'] ?? 0;
            }
            
            return [
                'total_tax' => $totalTax,
                'total_pension' => $totalPension,
                'employee_count' => $payroll->payslips->count(),
                'payroll' => $payroll,
            ];
        });
        
        return view('admin.payroll.reports.tax-pension', compact('data', 'validated'));
    }
    
    /**
     * Year-to-date report
     */
    public function ytd(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:2024|max:2030',
        ]);
        
        $cacheKey = "payroll_ytd_{$validated['year']}";
        
        $data = Cache::remember($cacheKey, 7200, function () use ($validated) {
            return Payroll::where('year', $validated['year'])
                ->with(['payslips' => function ($query) {
                    $query->select('payroll_id', 'basic_salary', 'total_allowances', 
                                   'overtime_amount', 'bonus_amount', 'total_deductions', 'net_salary');
                }])
                ->get()
                ->map(function ($payroll) {
                    return [
                        'month' => $payroll->month,
                        'employee_count' => $payroll->payslips->count(),
                        'total_basic' => $payroll->payslips->sum('basic_salary'),
                        'total_allowances' => $payroll->payslips->sum('total_allowances'),
                        'total_overtime' => $payroll->payslips->sum('overtime_amount'),
                        'total_bonuses' => $payroll->payslips->sum('bonus_amount'),
                        'total_deductions' => $payroll->payslips->sum('total_deductions'),
                        'total_net' => $payroll->payslips->sum('net_salary'),
                    ];
                });
        });
        
        return view('admin.payroll.reports.ytd', compact('data', 'validated'));
    }
    
    /**
     * Export to Excel
     */
    public function exportExcel(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030',
        ]);
        
        $payroll = Payroll::where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->with(['payslips.user.employee'])
            ->firstOrFail();
        
        $filename = "payroll_{$validated['year']}_{$validated['month']}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function () use ($payroll) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Employee Name', 'Email', 'Department', 'Basic Salary', 'Allowances', 
                'Overtime', 'Bonuses', 'Gross', 'Deductions', 'Net Salary'
            ]);
            
            // Data rows
            foreach ($payroll->payslips as $payslip) {
                $gross = $payslip->basic_salary + $payslip->total_allowances + 
                         $payslip->overtime_amount + $payslip->bonus_amount;
                
                fputcsv($file, [
                    $payslip->user->name,
                    $payslip->user->email,
                    $payslip->user->employee->department->name ?? 'N/A',
                    number_format($payslip->basic_salary, 2),
                    number_format($payslip->total_allowances, 2),
                    number_format($payslip->overtime_amount, 2),
                    number_format($payslip->bonus_amount, 2),
                    number_format($gross, 2),
                    number_format($payslip->total_deductions, 2),
                    number_format($payslip->net_salary, 2),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
