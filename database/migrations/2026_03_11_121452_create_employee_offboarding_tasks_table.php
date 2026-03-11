<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_offboarding_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resignation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('offboarding_task_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->text('comments')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_offboarding_tasks');
    }
};
