<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('leave_requests', 'approved_at')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('leave_requests', 'approved_at')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->dropColumn('approved_at');
            });
        }
    }
};
