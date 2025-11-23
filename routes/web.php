<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $stats = [
        'total_employees' => \App\Models\Employee::count(),
        'pending_leaves' => \App\Models\LeaveRequest::where('status', 'pending')->count(),
        'pending_finance_count' => \App\Models\FinancialRequest::whereIn('status', ['pending', 'approved_manager'])->count(),
        'pending_finance_amount' => \App\Models\FinancialRequest::whereIn('status', ['pending', 'approved_manager'])->sum('amount'),
    ];
    
    return view('dashboard', $stats);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Financial Requests
    Route::middleware('permission:view financial requests')->group(function () {
        Route::get('finance', [\App\Http\Controllers\FinancialRequestController::class, 'index'])->name('finance.index');
        Route::get('finance/{financialRequest}', [\App\Http\Controllers\FinancialRequestController::class, 'show'])->name('finance.show');
    });
    Route::middleware('permission:create financial request')->group(function () {
        Route::get('finance/create', [\App\Http\Controllers\FinancialRequestController::class, 'create'])->name('finance.create');
        Route::post('finance', [\App\Http\Controllers\FinancialRequestController::class, 'store'])->name('finance.store');
    });

    // Employees
    Route::middleware('permission:view employees')->group(function () {
        Route::get('employees', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
        Route::get('employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'show'])->name('employees.show');
    });
    Route::middleware('permission:create employees')->group(function () {
        Route::get('employees/create', [\App\Http\Controllers\EmployeeController::class, 'create'])->name('employees.create');
        Route::post('employees', [\App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    });

    // Leaves
    Route::middleware('permission:view leaves')->group(function () {
        Route::get('leaves', [\App\Http\Controllers\LeaveController::class, 'index'])->name('leaves.index');
    });
    Route::middleware('permission:create leave request')->group(function () {
        Route::get('leaves/create', [\App\Http\Controllers\LeaveController::class, 'create'])->name('leaves.create');
        Route::post('leaves', [\App\Http\Controllers\LeaveController::class, 'store'])->name('leaves.store');
    });

    // Leave Approvals
    Route::middleware('permission:approve leave')->group(function () {
        Route::get('leaves/approvals', [\App\Http\Controllers\LeaveApprovalController::class, 'index'])->name('leaves.approvals');
        Route::patch('leaves/{leaveRequest}/approve', [\App\Http\Controllers\LeaveApprovalController::class, 'approve'])->name('leaves.approve');
        Route::patch('leaves/{leaveRequest}/reject', [\App\Http\Controllers\LeaveApprovalController::class, 'reject'])->name('leaves.reject');
    });

    // Financial Approvals
    Route::middleware('permission:approve financial request')->group(function () {
        Route::get('finance/approvals', [\App\Http\Controllers\FinancialApprovalController::class, 'index'])->name('finance.approvals');
        Route::patch('finance/{financialRequest}/approve-manager', [\App\Http\Controllers\FinancialApprovalController::class, 'approveManager'])->name('finance.approve.manager');
        Route::patch('finance/{financialRequest}/approve-finance', [\App\Http\Controllers\FinancialApprovalController::class, 'approveFinance'])->name('finance.approve.finance');
        Route::patch('finance/{financialRequest}/reject', [\App\Http\Controllers\FinancialApprovalController::class, 'reject'])->name('finance.reject');
    });
    Route::middleware('permission:mark as paid')->group(function () {
        Route::patch('finance/{financialRequest}/mark-paid', [\App\Http\Controllers\FinancialApprovalController::class, 'markPaid'])->name('finance.mark-paid');
    });

    // Job Postings & Recruitment (Admin View)
    Route::middleware('permission:view employees')->group(function () {
        Route::resource('jobs', \App\Http\Controllers\JobPostingController::class);
    });

    // AI Chatbot
    Route::get('chatbot', [\App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('chatbot/send', [\App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    // Admin Only Routes
    Route::middleware('role:Admin')->group(function () {
        Route::get('settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        
        Route::resource('admin/departments', \App\Http\Controllers\DepartmentController::class, ['as' => 'admin']);
        Route::resource('admin/designations', \App\Http\Controllers\DesignationController::class, ['as' => 'admin']);
        
        // Payroll
        Route::resource('admin/salary', \App\Http\Controllers\SalaryStructureController::class, ['as' => 'admin']);
        Route::resource('admin/payroll', \App\Http\Controllers\PayrollController::class, ['as' => 'admin']);
        Route::post('admin/payroll/{payroll}/pay', [\App\Http\Controllers\PayrollController::class, 'markPaid'])->name('admin.payroll.markPaid');

        // Whistleblowing Admin
        Route::get('admin/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');

        // Onboarding Admin
        Route::resource('admin/onboarding', \App\Http\Controllers\OnboardingController::class, ['as' => 'admin']);
    });
    
    // Employee Self-Service
    Route::get('my-payslips', [\App\Http\Controllers\PayrollController::class, 'myPayslips'])->name('payslips.index');
    
    // Onboarding (Employee)
    Route::get('my-checklist', [\App\Http\Controllers\OnboardingController::class, 'myTasks'])->name('onboarding.index');
    Route::post('checklist/{task}/complete', [\App\Http\Controllers\OnboardingController::class, 'completeTask'])->name('onboarding.complete');

    // Performance Management
    Route::resource('performance/goals', \App\Http\Controllers\GoalController::class, ['as' => 'performance']);
    Route::resource('performance/reviews', \App\Http\Controllers\PerformanceReviewController::class, ['as' => 'performance']);

    // LMS
    Route::get('learning', [\App\Http\Controllers\CourseController::class, 'index'])->name('lms.index');
    Route::get('learning/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('lms.show');
    Route::post('learning/{course}/complete', [\App\Http\Controllers\CourseController::class, 'complete'])->name('lms.complete');
});

// Public Routes
Route::get('report-misconduct', [\App\Http\Controllers\ReportController::class, 'create'])->name('reports.create');
Route::post('report-misconduct', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');

Route::get('jobs', [\App\Http\Controllers\JobPostingController::class, 'index'])->name('jobs.index');
Route::get('jobs/{job}/apply', [\App\Http\Controllers\ApplicationController::class, 'create'])->name('applications.create');
Route::post('jobs/{job}/apply', [\App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.store');
Route::get('application/success', [\App\Http\Controllers\ApplicationController::class, 'success'])->name('applications.success');

require __DIR__.'/auth.php';
