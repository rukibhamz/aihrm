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
        // First check if table exists to avoid errors on potential re-runs if it was hidden
        if (!Schema::hasTable('applications')) {
            Schema::create('applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
                $table->string('candidate_name');
                $table->string('candidate_email');
                $table->string('candidate_phone')->nullable();
                $table->string('resume_path')->nullable(); // Path to PDF
                $table->integer('ai_score')->nullable(); // 0-100 match score
                $table->string('status')->default('applied'); // applied, screening, interview, offer, hired, rejected
                $table->text('notes')->nullable(); // Internal HR comments
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
