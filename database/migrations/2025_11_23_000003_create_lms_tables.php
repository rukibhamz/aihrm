<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // LMS: Courses
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable(); // For MVP, simple URL
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // LMS: User Progress
        Schema::create('course_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Whistleblowing: Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->string('anonymous_id')->nullable(); // Random ID for tracking without login
            $table->enum('status', ['new', 'investigating', 'resolved', 'dismissed'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('course_completions');
        Schema::dropIfExists('courses');
    }
};
