<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // General stats
        $total_employees = Employee::count();
        $pending_leaves = LeaveRequest::where('status', 'pending')->count();
        $pending_finance_count = \App\Models\FinancialRequest::whereIn('status', ['pending', 'approved_manager'])->count();
        $pending_finance_amount = \App\Models\FinancialRequest::whereIn('status', ['pending', 'approved_manager'])->sum('amount');

        // Recruitment stats
        $open_jobs = \App\Models\JobPosting::where('status', 'open')->count();
        $new_applications = \App\Models\Application::where('created_at', '>=', now()->subDays(7))->count();
        $avg_ai_score = \App\Models\Application::whereNotNull('ai_score')->avg('ai_score') ?? 0;

        // Generate chart data
        $employeeGrowthData = $this->getEmployeeGrowthData();
        $leaveTrendsData = $this->getLeaveTrendsData();
        $payrollCostData = $this->getPayrollCostData();

        return view('dashboard', compact(
            'total_employees',
            'pending_leaves',
            'pending_finance_count',
            'pending_finance_amount',
            'open_jobs',
            'new_applications',
            'avg_ai_score',
            'employeeGrowthData',
            'leaveTrendsData',
            'payrollCostData'
        ));
    }

    private function getEmployeeGrowthData()
    {
        $labels = [];
        $data = [];

        // Get last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            // Count employees created up to that month
            $count = Employee::whereDate('created_at', '<=', $date->endOfMonth())->count();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getLeaveTrendsData()
    {
        $departments = Department::withCount(['employees as leave_count' => function($query) {
            $query->select(DB::raw('count(distinct leave_requests.id)'))
                  ->join('leave_requests', 'employees.user_id', '=', 'leave_requests.user_id')
                  ->whereYear('leave_requests.created_at', Carbon::now()->year);
        }])->get();

        $labels = [];
        $data = [];

        foreach ($departments as $department) {
            $labels[] = $department->name;
            $data[] = $department->leave_count ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getPayrollCostData()
    {
        $labels = [];
        $data = [];

        // Get last 6 months of payroll data
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            // Sum net_salary from payslips for that month's payroll
            $total = \App\Models\Payslip::whereHas('payroll', function($query) use ($date) {
                $query->where('month', $date->month)
                      ->where('year', $date->year);
            })->sum('net_salary');
            
            $data[] = (float) $total;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
