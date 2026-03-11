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
        Schema::create('overtime_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Standard Overtime');
            $table->decimal('standard_daily_hours', 4, 2)->default(8.0);
            $table->decimal('weekday_multiplier', 4, 2)->default(1.5);
            $table->decimal('weekend_multiplier', 4, 2)->default(2.0);
            $table->decimal('holiday_multiplier', 4, 2)->default(2.0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_policies');
    }
};
