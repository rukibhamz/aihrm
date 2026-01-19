<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // AI Usage Tracking
        if (!Schema::hasTable('ai_requests')) {
            Schema::create('ai_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('feature', 50); // 'resume', 'chat', 'performance', 'compliance'
                $table->integer('tokens_used')->default(0);
                $table->decimal('cost', 10, 4)->default(0);
                $table->text('request_summary')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'feature']);
                $table->index('created_at');
            });
        }

        // Job Postings (for resume screening)
        if (!Schema::hasTable('job_postings')) {
            Schema::create('job_postings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->text('requirements');
                $table->string('department')->nullable();
                $table->string('location')->nullable();
                $table->enum('status', ['open', 'closed', 'draft'])->default('draft');
                $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Applications
        if (!Schema::hasTable('applications')) {
            Schema::create('applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
                $table->string('candidate_name');
                $table->string('candidate_email');
                $table->string('candidate_phone')->nullable();
                $table->string('resume_path');
                $table->integer('ai_score')->nullable(); // 0-100
                $table->enum('status', ['pending', 'screening', 'shortlisted', 'rejected', 'hired'])->default('pending');
                $table->timestamps();

                $table->index('ai_score');
                $table->index('status');
            });
        }

        // Resume Analysis Results
        if (!Schema::hasTable('resume_analyses')) {
            Schema::create('resume_analyses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('application_id')->constrained()->onDelete('cascade');
                $table->json('extracted_data'); // Skills, experience, education
                $table->integer('match_score')->default(0); // 0-100
                $table->text('ai_feedback')->nullable();
                $table->text('strengths')->nullable();
                $table->text('gaps')->nullable();
                $table->timestamps();
            });
        }

        // AI Chat Messages
        if (!Schema::hasTable('ai_chat_messages')) {
            Schema::create('ai_chat_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->text('message');
                $table->text('response');
                $table->integer('tokens_used')->default(0);
                $table->timestamps();

                $table->index('user_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
        Schema::dropIfExists('job_postings');
        Schema::dropIfExists('ai_chat_messages');
        Schema::dropIfExists('resume_analyses');
        Schema::dropIfExists('ai_requests');
    }
};
