<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enhance salary_structures table
        Schema::table('salary_structures', function (Blueprint $table) {
            $table->decimal('overtime_rate', 5, 2)->default(1.5)->after('tax_paye'); // e.g., 1.5 for 1.5x
            $table->decimal('hourly_rate', 12, 2)->nullable()->after('overtime_rate');
        });

        // Enhance payslips table
        Schema::table('payslips', function (Blueprint $table) {
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('basic_salary');
            $table->decimal('overtime_amount', 12, 2)->default(0)->after('overtime_hours');
            $table->decimal('bonus_amount', 12, 2)->default(0)->after('overtime_amount');
            $table->decimal('loan_deduction', 12, 2)->default(0)->after('total_deductions');
            $table->decimal('advance_deduction', 12, 2)->default(0)->after('loan_deduction');
            $table->integer('worked_days')->nullable()->after('advance_deduction');
            $table->integer('expected_days')->nullable()->after('worked_days');
        });
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn([
                'overtime_hours',
                'overtime_amount',
                'bonus_amount',
                'loan_deduction',
                'advance_deduction',
                'worked_days',
                'expected_days'
            ]);
        });

        Schema::table('salary_structures', function (Blueprint $table) {
            $table->dropColumn(['overtime_rate', 'hourly_rate']);
        });
    }
};
