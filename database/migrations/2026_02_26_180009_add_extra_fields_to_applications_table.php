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
        Schema::table('applications', function (Blueprint $table) {
            $table->string('current_city')->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('years_of_experience')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('motivation')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'current_city',
                'current_job_title',
                'expected_salary',
                'notice_period',
                'years_of_experience',
                'cover_letter',
                'motivation',
                'linkedin_url',
                'portfolio_url',
            ]);
        });
    }
};
