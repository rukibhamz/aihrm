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
        Schema::table('goals', function (Blueprint $table) {
            $table->string('cycle_name')->nullable()->after('type');
            $table->integer('manager_score')->nullable()->after('progress');
            $table->text('manager_comment')->nullable()->after('manager_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn(['cycle_name', 'manager_score', 'manager_comment']);
        });
    }
};
