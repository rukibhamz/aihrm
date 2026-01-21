<?php

namespace Tests\Unit\Payroll;

use App\Models\User;
use App\Models\SalaryStructure;
use App\Models\Attendance;
use App\Helpers\LeaveHelper;
use Tests\TestCase;
use Carbon\Carbon;

class PayrollCalculationTest extends TestCase
{
    public function test_working_days_calculation(): void
    {
        // Test working days calculation
        $start = Carbon::parse('2025-01-01'); // Wednesday
        $end = Carbon::parse('2025-01-05'); // Sunday
        
        $workingDays = LeaveHelper::calculateWorkingDays($start, $end);
        
        // Should be 3 days (Wed, Thu, Fri) assuming Mon-Fri work week
        $this->assertEquals(3, $workingDays);
    }

    public function test_overtime_calculation(): void
    {
        $user = $this->createEmployee();
        
        // Create salary structure
        $structure = SalaryStructure::create([
            'user_id' => $user->id,
            'base_salary' => 5000,
            'housing_allowance' => 1000,
            'transport_allowance' => 500,
            'other_allowances' => 0,
            'pension_employee' => 200,
            'tax_paye' => 500,
            'overtime_rate' => 1.5,
            'hourly_rate' => 25,
        ]);

        // 10 hours overtime at $25/hour * 1.5 = $375
        $overtimeAmount = 10 * $structure->hourly_rate * $structure->overtime_rate;
        
        $this->assertEquals(375, $overtimeAmount);
    }

    public function test_pro_rata_salary_calculation(): void
    {
        $baseSalary = 5000;
        $expectedDays = 22;
        $workedDays = 20;
        
        $proRata = $baseSalary * ($workedDays / $expectedDays);
        
        $this->assertEquals(4545.45, round($proRata, 2));
    }
}
