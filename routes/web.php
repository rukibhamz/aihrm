<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SSO Routes (must be outside auth middleware for unauthenticated access)
Route::get('auth/{provider}', [\App\Http\Controllers\Auth\SsoController::class, 'redirectToProvider'])
    ->where('provider', 'azure|google|zoho')
    ->name('sso.login');
Route::get('auth/{provider}/callback', [\App\Http\Controllers\Auth\SsoController::class, 'handleProviderCallback'])
    ->where('provider', 'azure|google|zoho')
    ->name('sso.callback');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['verified', 'force_password_change'])->name('dashboard');

    // Password Change Routes
    Route::get('change-password', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'show'])->name('password.change.show');
    Route::post('change-password', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'update'])->name('password.change.update');



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
        Route::resource('admin/jobs', \App\Http\Controllers\JobPostingController::class)->names('admin.jobs');
        
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

    // Leave Management (All Authenticated Users)
    Route::get('leaves/relief-requests', [\App\Http\Controllers\LeaveController::class, 'reliefRequests'])->name('leaves.relief-requests');
    Route::patch('leaves/{leaveRequest}/relief-status', [\App\Http\Controllers\LeaveController::class, 'updateReliefStatus'])->name('leaves.relief-status');
    Route::resource('leaves', \App\Http\Controllers\LeaveController::class);

    // Employee Routes
    Route::middleware(['auth'])->group(function () { 
         Route::get('my-payslips', [\App\Http\Controllers\Employee\PayslipController::class, 'index'])->name('my-payslips.index');
         Route::get('my-payslips/{payslip}/download', [\App\Http\Controllers\Employee\PayslipController::class, 'download'])->name('my-payslips.download');
         
         Route::get('my-bonuses', [\App\Http\Controllers\Employee\BonusController::class, 'index'])->name('my-bonuses.index');
         Route::resource('my-advances', \App\Http\Controllers\Employee\SalaryAdvanceController::class)->only(['index', 'store']);
         Route::resource('my-loans', \App\Http\Controllers\Employee\LoanController::class)->only(['index', 'store']);
    });

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
    Route::get('performance/team-goals', [\App\Http\Controllers\TeamGoalController::class, 'index'])->name('performance.team-goals.index');
    Route::put('performance/team-goals/{goal}/score', [\App\Http\Controllers\TeamGoalController::class, 'updateScore'])->name('performance.team-goals.score');
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

    Route::get('jobs', [\App\Http\Controllers\JobPostingController::class, 'publicIndex'])->name('jobs.index');
    Route::get('jobs/{job}', [\App\Http\Controllers\JobPostingController::class, 'show'])->name('jobs.show');
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

// System Installation Fixer (For cPanel/No-Terminal Support)
Route::get('/system/fix-install', [\App\Http\Controllers\InstallController::class, 'fix'])->name('system.fix');

// Two-Factor Authentication Challenge (during login)
Route::middleware('guest')->group(function () {
    Route::get('two-factor-challenge', [\App\Http\Controllers\Auth\TwoFactorChallengeController::class, 'show'])
        ->name('two-factor.challenge');
    Route::post('two-factor-challenge', [\App\Http\Controllers\Auth\TwoFactorChallengeController::class, 'store']);
});

require __DIR__.'/auth.php';
