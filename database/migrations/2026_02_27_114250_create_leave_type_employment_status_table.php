<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_type_employment_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employment_status_id')->constrained('employment_statuses')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['leave_type_id', 'employment_status_id'], 'leave_type_emp_status_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_type_employment_status');
    }
};
