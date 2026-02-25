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
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreignId('relief_officer_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            $table->string('relief_officer_status')->default('pending')->after('relief_officer_id'); // pending, accepted, rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropForeign(['relief_officer_id']);
            $table->dropColumn(['relief_officer_id', 'relief_officer_status']);
        });
    }
};
