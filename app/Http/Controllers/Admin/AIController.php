<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PerformanceReview;
use App\Services\AIService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display compliance check page
     */
    public function compliance()
    {
        return view('admin.ai.compliance');
    }

    /**
     * Run labor law compliance check
     */
    public function runComplianceCheck(Request $request)
    {
        // Get sample data for compliance check
        // In a real app, this would be more specific (e.g. by department)
        $employees = Employee::with(['department', 'designation'])->take(20)->get();
        
        $data = $employees->map(function ($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'salary' => $emp->salary, // Assuming this exists or is in payroll
                'hours_per_week' => 40, // Placeholder
                'leave_balance' => $emp->leaveBalances()->sum('balance'),
            ];
        })->toArray();

        try {
            $analysis = $this->aiService->checkCompliance(['employees' => $data]);
            return response()->json($analysis);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display performance analysis page
     */
    public function performance()
    {
        return view('admin.ai.performance');
    }

    /**
     * Analyze performance reviews for bias and patterns
     */
    public function analyzePerformance(Request $request)
    {
        $reviews = PerformanceReview::with('employee')->latest()->take(50)->get();
        
        $data = $reviews->map(function ($review) {
            return [
                'employee_id' => $review->employee_id,
                'employee_name' => $review->employee->first_name . ' ' . $review->employee->last_name,
                'reviewer_id' => $review->reviewer_id,
                'rating' => $review->rating,
                'comments' => $review->comments,
            ];
        })->toArray();

        try {
            $analysis = $this->aiService->analyzePerformance($data);
            return response()->json($analysis);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
