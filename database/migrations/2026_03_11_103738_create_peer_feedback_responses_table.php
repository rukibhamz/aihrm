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
        Schema::create('peer_feedback_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('peer_feedback_requests')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->integer('collaboration_rating')->nullable()->comment('1-5 scale');
            $table->integer('communication_rating')->nullable()->comment('1-5 scale');
            $table->text('additional_comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peer_feedback_responses');
    }
};
