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
        Schema::create('approval_chain_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_chain_id')->constrained()->cascadeOnDelete();
            $table->integer('step_order');
            
            // role, specific_user, line_manager
            $table->string('approver_type'); 
            
            // The ID of the role or specific user (nullable for 'line_manager' since that's dynamic)
            $table->string('approver_id')->nullable(); 

            // Optional conditions
            $table->decimal('min_amount', 15, 2)->nullable();
            $table->decimal('max_amount', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_chain_steps');
    }
};
