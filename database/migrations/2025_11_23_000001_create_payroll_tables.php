<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Salary Structure (One per employee)
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('base_salary', 12, 2);
            $table->decimal('housing_allowance', 12, 2)->default(0);
            $table->decimal('transport_allowance', 12, 2)->default(0);
            $table->decimal('other_allowances', 12, 2)->default(0);
            $table->decimal('pension_employee', 12, 2)->default(0); // % or fixed
            $table->decimal('tax_paye', 12, 2)->default(0); // Estimated
            $table->timestamps();
        });

        // Payroll Batch (e.g., "November 2025")
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('month'); // e.g., "11"
            $table->string('year'); // e.g., "2025"
            $table->string('status')->default('draft'); // draft, paid
            $table->date('payment_date')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Individual Payslips (Lines in the batch)
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->decimal('basic_salary', 12, 2);
            $table->decimal('total_allowances', 12, 2);
            $table->decimal('total_deductions', 12, 2);
            $table->decimal('net_salary', 12, 2);
            $table->json('breakdown'); // Store full JSON of line items for immutability
            $table->string('status')->default('pending'); // pending, paid
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('salary_structures');
    }
};
