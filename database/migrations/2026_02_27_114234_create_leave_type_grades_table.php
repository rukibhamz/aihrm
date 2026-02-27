<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_type_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grade_level_id')->constrained()->cascadeOnDelete();
            $table->integer('days_allowed');
            $table->timestamps();
            
            $table->unique(['leave_type_id', 'grade_level_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_type_grades');
    }
};
