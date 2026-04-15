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
        Schema::disableForeignKeyConstraints();

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'shift_id')) {
                $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'office_location_id')) {
                $table->foreignId('office_location_id')->nullable()->constrained('office_locations')->nullOnDelete();
            }
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
            $table->dropColumn('shift_id');
            $table->dropForeign(['office_location_id']);
            $table->dropColumn('office_location_id');
        });
    }
};
