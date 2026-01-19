<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add indexes for performance optimization
        
        // Payroll indexes
        Schema::table('payrolls', function (Blueprint $table) {
            $table->index(['month', 'year']);
            $table->index('status');
            $table->index('created_by');
        });
        
        // Payslip indexes
        Schema::table('payslips', function (Blueprint $table) {
            $table->index('payroll_id');
            $table->index('user_id');
            $table->index('status');
            $table->index(['payroll_id', 'user_id']);
        });
        
        // Bonus indexes
        Schema::table('bonuses', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['month', 'year']);
            $table->index(['user_id', 'month', 'year']);
        });
        
        // Loan deduction indexes
        Schema::table('loan_deductions', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
        
        // Salary advance indexes
        Schema::table('salary_advances', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index(['deduction_month', 'deduction_year']);
            $table->index(['user_id', 'status']);
        });
        
        // Attendance indexes (if not already present)
        if (Schema::hasTable('attendances')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'user_id_date_index')) {
                    $table->index('user_id');
                    $table->index('date');
                    $table->index(['user_id', 'date']);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex(['month', 'year']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_by']);
        });
        
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropIndex(['payroll_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payroll_id', 'user_id']);
        });
        
        Schema::table('bonuses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['month', 'year']);
            $table->dropIndex(['user_id', 'month', 'year']);
        });
        
        Schema::table('loan_deductions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id', 'status']);
        });
        
        Schema::table('salary_advances', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['deduction_month', 'deduction_year']);
            $table->dropIndex(['user_id', 'status']);
        });
    }
};
