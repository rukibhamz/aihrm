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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('job_type')->nullable()->after('location'); // Full-time, Part-time, Contract, etc.
            $table->decimal('min_salary', 10, 2)->nullable()->after('job_type');
            $table->decimal('max_salary', 10, 2)->nullable()->after('min_salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['job_type', 'min_salary', 'max_salary']);
        });
    }
};
