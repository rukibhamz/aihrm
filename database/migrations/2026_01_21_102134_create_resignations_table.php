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
        Schema::create('resignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('resignation_date');
            $table->date('last_working_day');
            $table->text('reason');
            $table->string('status')->default('pending'); // pending, approved, rejected, withdrawn, completed
            $table->text('hr_comments')->nullable();
            $table->text('exit_interview_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resignations');
    }
};
