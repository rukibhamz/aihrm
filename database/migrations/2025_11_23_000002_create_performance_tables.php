<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Goals / OKRs
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'cancelled'])->default('not_started');
            $table->integer('progress')->default(0); // 0-100
            $table->timestamps();
        });

        // Performance Reviews
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->string('period'); // e.g., "Q4 2025"
            $table->enum('type', ['self', 'manager', 'peer'])->default('manager');
            $table->enum('status', ['draft', 'submitted', 'completed'])->default('draft');
            $table->json('content')->nullable(); // Flexible form data
            $table->text('ai_summary')->nullable(); // AI generated summary
            $table->timestamps();
        });

        // Feedback (360 Degree)
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_review_id')->constrained()->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('performance_reviews');
        Schema::dropIfExists('goals');
    }
};
