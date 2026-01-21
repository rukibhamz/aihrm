<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['verified', 'force_password_change'])->name('dashboard');

    // Password Change Routes
    Route::get('change-password', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'show'])->name('password.change.show');
    Route::post('change-password', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'update'])->name('password.change.update');

    // SSO Routes
    Route::get('auth/{provider}', [\App\Http\Controllers\Auth\SsoController::class, 'redirectToProvider'])->name('sso.login');
    Route::get('auth/{provider}/callback', [\App\Http\Controllers\Auth\SsoController::class, 'handleProviderCallback'])->name('sso.callback');



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
    Route::middleware('permission:approve leave request')->group(function () {
        Route::get('leaves/approvals', [\App\Http\Controllers\LeaveApprovalController::class, 'index'])->name('leaves.approvals');
        Route::patch('leaves/{leaveRequest}/approve', [\App\Http\Controllers\LeaveApprovalController::class, 'approve'])->name('leaves.approve');
        Route::patch('leaves/{leaveRequest}/reject', [\App\Http\Controllers\LeaveApprovalController::class, 'reject'])->name('leaves.reject');
    });

    // Job Postings & Recruitment (Admin View)
    Route::middleware('permission:view employees')->group(function () {
        Route::patch('employees/{employee}/reset-password', [\App\Http\Controllers\EmployeeController::class, 'resetPassword'])->name('employees.reset-password');
        Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
        Route::resource('leaves', \App\Http\Controllers\LeaveController::class);
        Route::resource('jobs', \App\Http\Controllers\JobPostingController::class, ['as' => 'admin']);
        
        // ATS / Applications
        Route::get('admin/recruitment/kanban', [\App\Http\Controllers\Admin\ApplicationController::class, 'kanban'])->name('admin.applications.kanban');
        Route::patch('admin/applications/{application}/status', [\App\Http\Controllers\Admin\ApplicationController::class, 'updateStatus'])->name('admin.applications.status');

        // Payroll & Salary
        Route::get('admin/salary', [\App\Http\Controllers\Admin\SalaryStructureController::class, 'index'])->name('admin.salary.index');
        Route::get('admin/salary/{user}/edit', [\App\Http\Controllers\Admin\SalaryStructureController::class, 'edit'])->name('admin.salary.edit');
        Route::put('admin/salary/{user}', [\App\Http\Controllers\Admin\SalaryStructureController::class, 'update'])->name('admin.salary.update');

        Route::get('admin/payroll', [\App\Http\Controllers\Admin\PayrollController::class, 'index'])->name('admin.payroll.index');
        Route::get('admin/payroll/create', [\App\Http\Controllers\Admin\PayrollController::class, 'create'])->name('admin.payroll.create');
        Route::post('admin/payroll', [\App\Http\Controllers\Admin\PayrollController::class, 'store'])->name('admin.payroll.store');
        Route::get('admin/payroll/{payroll}', [\App\Http\Controllers\Admin\PayrollController::class, 'show'])->name('admin.payroll.show');
        Route::patch('admin/payroll/{payroll}/status', [\App\Http\Controllers\Admin\PayrollController::class, 'updateStatus'])->name('admin.payroll.status');
    });

    // Employee Routes
    Route::middleware(['auth'])->group(function () { 
         Route::get('my-payslips', [\App\Http\Controllers\Employee\PayslipController::class, 'index'])->name('my-payslips.index');
         Route::get('my-payslips/{payslip}/download', [\App\Http\Controllers\Employee\PayslipController::class, 'download'])->name('my-payslips.download');
    });
    Route::get('chatbot', [\App\Http\Controllers\ChatbotController::class, 'index'])->name('chatbot.index');
    Route::post('chatbot/send', [\App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    // Admin Only Routes
    Route::middleware('role:Admin')->group(function () {
        Route::get('settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        Route::post('settings/test-email', [\App\Http\Controllers\SettingsController::class, 'testEmail'])->name('settings.test-email');
        
        Route::resource('admin/departments', \App\Http\Controllers\DepartmentController::class, ['as' => 'admin']);
        Route::resource('admin/designations', \App\Http\Controllers\DesignationController::class, ['as' => 'admin']);
        Route::resource('admin/grade-levels', \App\Http\Controllers\GradeLevelController::class, ['as' => 'admin']);
        
        // Enterprise Payroll - Bonuses, Loans, Advances
        Route::resource('admin/bonuses', \App\Http\Controllers\Admin\BonusController::class, ['as' => 'admin']);
        Route::resource('admin/loans', \App\Http\Controllers\Admin\LoanDeductionController::class, ['as' => 'admin']);
        Route::resource('admin/advances', \App\Http\Controllers\Admin\SalaryAdvanceController::class, ['as' => 'admin']);
        
        // Payroll Reports
        Route::get('admin/payroll-reports', [\App\Http\Controllers\Admin\PayrollReportController::class, 'index'])->name('admin.payroll-reports.index');
        Route::get('admin/payroll-reports/summary', [\App\Http\Controllers\Admin\PayrollReportController::class, 'summary'])->name('admin.payroll-reports.summary');
        Route::get('admin/payroll-reports/department', [\App\Http\Controllers\Admin\PayrollReportController::class, 'byDepartment'])->name('admin.payroll-reports.department');
        Route::get('admin/payroll-reports/tax-pension', [\App\Http\Controllers\Admin\PayrollReportController::class, 'taxPension'])->name('admin.payroll-reports.tax-pension');
        Route::get('admin/payroll-reports/ytd', [\App\Http\Controllers\Admin\PayrollReportController::class, 'ytd'])->name('admin.payroll-reports.ytd');
        Route::get('admin/payroll-reports/export', [\App\Http\Controllers\Admin\PayrollReportController::class, 'exportExcel'])->name('admin.payroll-reports.export');

        // Whistleblowing Admin
        Route::get('admin/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');

        // Custom Report Builder
        Route::get('admin/custom-reports', [\App\Http\Controllers\Admin\ReportBuilderController::class, 'index'])->name('admin.reports.builder');
        Route::post('admin/custom-reports/generate', [\App\Http\Controllers\Admin\ReportBuilderController::class, 'generate'])->name('admin.reports.generate');

        // Data Import/Export
        Route::get('admin/import', [\App\Http\Controllers\Admin\ImportController::class, 'index'])->name('admin.import.index');
        Route::post('admin/import', [\App\Http\Controllers\Admin\ImportController::class, 'store'])->name('admin.import.store');
        Route::get('admin/export', [\App\Http\Controllers\Admin\ExportController::class, 'export'])->name('admin.export');

        // Onboarding Admin
        Route::resource('admin/onboarding', \App\Http\Controllers\OnboardingController::class, ['as' => 'admin']);

        // LMS Admin
        Route::resource('admin/courses', \App\Http\Controllers\Admin\CourseController::class, ['as' => 'admin']);

        // Announcements Admin
        Route::resource('admin/announcements', \App\Http\Controllers\Admin\AnnouncementController::class, ['as' => 'admin']);
        Route::patch('admin/announcements/{announcement}/publish', [\App\Http\Controllers\Admin\AnnouncementController::class, 'publish'])->name('admin.announcements.publish');

        // Asset Management Admin
        Route::resource('admin/assets', \App\Http\Controllers\Admin\AssetController::class, ['as' => 'admin']);

        // Resignation & Offboarding Admin
        Route::resource('admin/resignations', \App\Http\Controllers\Admin\ResignationController::class, ['as' => 'admin'])->except(['destroy']);
        Route::get('admin/resignations/{resignation}/assets', [\App\Http\Controllers\Admin\ResignationController::class, 'assetsCheck'])->name('admin.resignations.assets');

        // Document Management Admin
        Route::resource('admin/documents', \App\Http\Controllers\Admin\DocumentController::class, ['as' => 'admin']);
        Route::get('admin/documents/{document}/download', [\App\Http\Controllers\Admin\DocumentController::class, 'download'])->name('admin.documents.download');

        // Audit Logs
        Route::get('admin/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit-logs.index');
        Route::get('admin/audit-logs/{audit}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('admin.audit-logs.show');

        // Leave Management
        Route::get('admin/leaves/calendar', [\App\Http\Controllers\Admin\LeaveController::class, 'calendar'])->name('admin.leaves.calendar');
        Route::post('admin/leaves/bulk-approve', [\App\Http\Controllers\Admin\LeaveController::class, 'bulkApprove'])->name('admin.leaves.bulk-approve');
        Route::post('admin/leaves/bulk-reject', [\App\Http\Controllers\Admin\LeaveController::class, 'bulkReject'])->name('admin.leaves.bulk-reject');
        Route::resource('admin/leaves', \App\Http\Controllers\Admin\LeaveController::class, ['as' => 'admin'])->only(['index', 'update']);
        // AI Tools
        Route::get('admin/ai/compliance', [\App\Http\Controllers\Admin\AIController::class, 'compliance'])->name('admin.ai.compliance');
        Route::post('admin/ai/compliance/run', [\App\Http\Controllers\Admin\AIController::class, 'runComplianceCheck'])->name('admin.ai.compliance.run');
        Route::get('admin/ai/performance', [\App\Http\Controllers\Admin\AIController::class, 'performance'])->name('admin.ai.performance');
        Route::post('admin/ai/performance/analyze', [\App\Http\Controllers\Admin\AIController::class, 'analyzePerformance'])->name('admin.ai.performance.analyze');

        Route::resource('admin/leave-types', \App\Http\Controllers\Admin\LeaveTypeController::class, ['as' => 'admin']);
        Route::resource('admin/leave-balances', \App\Http\Controllers\Admin\LeaveBalanceController::class, ['as' => 'admin'])->only(['index', 'update']);
    });
    
    // Attendance and other SS
    Route::get('attendance/qr', [\App\Http\Controllers\AttendanceController::class, 'qrView'])->name('attendance.qr');
    Route::post('attendance/verify-qr', [\App\Http\Controllers\AttendanceController::class, 'verifyQr'])->name('attendance.verifyQr');
    Route::resource('attendance', \App\Http\Controllers\AttendanceController::class);
    Route::post('attendance/clock-in', [\App\Http\Controllers\AttendanceController::class, 'clockIn'])->name('attendance.clockIn');
    Route::post('attendance/clock-out', [\App\Http\Controllers\AttendanceController::class, 'clockOut'])->name('attendance.clockOut');
    Route::resource('finance', \App\Http\Controllers\FinancialRequestController::class);
    Route::resource('reports', \App\Http\Controllers\ReportController::class)->only(['create', 'store']);
    Route::resource('lms', \App\Http\Controllers\CourseController::class);
    Route::get('lms/{course}/lesson/{lesson}', [\App\Http\Controllers\CourseController::class, 'showLesson'])->name('lms.lesson');
    Route::post('lms/{course}/complete', [\App\Http\Controllers\CourseController::class, 'complete'])->name('lms.complete');
    
    // Onboarding (Employee)
    Route::get('my-checklist', [\App\Http\Controllers\OnboardingController::class, 'myTasks'])->name('onboarding.index');
    Route::post('checklist/{task}/complete', [\App\Http\Controllers\OnboardingController::class, 'completeTask'])->name('onboarding.complete');

    // Performance Management
    Route::resource('performance/goals', \App\Http\Controllers\GoalController::class, ['as' => 'performance']);
    Route::resource('performance/reviews', \App\Http\Controllers\PerformanceReviewController::class, ['as' => 'performance']);

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/preferences', [\App\Http\Controllers\NotificationPreferenceController::class, 'index'])->name('notifications.preferences.index');
    Route::put('notifications/preferences', [\App\Http\Controllers\NotificationPreferenceController::class, 'update'])->name('notifications.preferences.update');
    Route::get('lms/{course}/certificate', [\App\Http\Controllers\CourseController::class, 'downloadCertificate'])->name('lms.certificate');
    Route::get('notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('notifications/poll', [\App\Http\Controllers\NotificationController::class, 'poll'])->name('notifications.poll');
    Route::get('notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    // Announcements (Employee View)
    Route::get('announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/unread-count', [\App\Http\Controllers\AnnouncementController::class, 'unreadCount'])->name('announcements.unreadCount');
    Route::get('announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('announcements/{announcement}/read', [\App\Http\Controllers\AnnouncementController::class, 'markAsRead'])->name('announcements.markAsRead');

    Route::get('admin/attendance/scanner', function() {
        return view('admin.attendance.scanner');
    })->name('admin.attendance.scanner');

}); // End of Auth Middleware Group

    Route::get('jobs', [\App\Http\Controllers\JobPostingController::class, 'index'])->name('jobs.index');
Route::get('jobs/{job}/apply', [\App\Http\Controllers\ApplicationController::class, 'create'])->name('applications.create');

// Employee Resignation
Route::middleware(['auth'])->group(function() {
    Route::get('resignations/create', [\App\Http\Controllers\ResignationController::class, 'create'])->name('resignations.create');
    Route::post('resignations', [\App\Http\Controllers\ResignationController::class, 'store'])->name('resignations.store');
    Route::get('resignations/status', [\App\Http\Controllers\ResignationController::class, 'show'])->name('resignations.show');

    // Documents (Employee Access)
    Route::get('documents', [\App\Http\Controllers\DocumentController::class, 'index'])->name('documents.index');
    Route::post('documents', [\App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents/{document}/download', [\App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
});
Route::post('jobs/{job}/apply', [\App\Http\Controllers\ApplicationController::class, 'store'])->name('applications.store');
Route::patch('applications/{application}/status', [\App\Http\Controllers\ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
Route::get('application/success', [\App\Http\Controllers\ApplicationController::class, 'success'])->name('applications.success');

// Health Check & Monitoring
Route::get('/health', [\App\Http\Controllers\HealthCheckController::class, 'index'])->name('health.check');
Route::get('/health/metrics', [\App\Http\Controllers\HealthCheckController::class, 'metrics'])->name('health.metrics');

// Two-Factor Authentication Challenge (during login)
Route::middleware('guest')->group(function () {
    Route::get('two-factor-challenge', [\App\Http\Controllers\Auth\TwoFactorChallengeController::class, 'show'])
        ->name('two-factor.challenge');
    Route::post('two-factor-challenge', [\App\Http\Controllers\Auth\TwoFactorChallengeController::class, 'store']);
});

// Audit Logs (Admin only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('admin.audit-logs.index');
    Route::get('audit-logs/{audit}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('admin.audit-logs.show');
});

require __DIR__.'/auth.php';
