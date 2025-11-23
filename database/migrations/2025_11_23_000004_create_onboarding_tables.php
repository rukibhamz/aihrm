<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Task Templates (Managed by Admin)
        Schema::create('onboarding_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('stage', ['onboarding', 'offboarding'])->default('onboarding');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade'); // Null = Global task
            $table->timestamps();
        });

        // Assigned Tasks (Tracked per Employee)
        Schema::create('employee_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('onboarding_task_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_tasks');
        Schema::dropIfExists('onboarding_tasks');
    }
};
