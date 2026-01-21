<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bonuses table
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('type')->default('one-time'); // one-time, recurring, performance, sales
            $table->integer('month');
            $table->integer('year');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Loan Deductions table
        Schema::create('loan_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('loan_amount', 12, 2);
            $table->decimal('monthly_deduction', 12, 2);
            $table->decimal('remaining_balance', 12, 2);
            $table->date('start_date');
            $table->string('status')->default('active'); // active, completed
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Salary Advances table
        Schema::create('salary_advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->integer('deduction_month');
            $table->integer('deduction_year');
            $table->string('status')->default('pending'); // pending, deducted
            $table->text('reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_advances');
        Schema::dropIfExists('loan_deductions');
        Schema::dropIfExists('bonuses');
    }
};
