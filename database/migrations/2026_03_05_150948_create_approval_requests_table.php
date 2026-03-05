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
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic link to the actual request (e.g., LeaveRequest ID 5)
            $table->morphs('approvable'); 
            
            $table->foreignId('approval_chain_id')->constrained()->cascadeOnDelete();
            $table->integer('current_step_order')->default(1);
            
            // pending, approved, rejected
            $table->string('status')->default('pending');
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};
