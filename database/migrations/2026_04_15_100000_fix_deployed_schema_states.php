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
        // notes is now handled by the patched 2026_03_12_124535 migration

        // 2. Ensure users table has required columns and foreign keys
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'shift_id')) {
                    $table->foreignId('shift_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('users', 'office_location_id')) {
                    $table->foreignId('office_location_id')->nullable()->after('shift_id');
                }
            });

            // Re-apply foreign keys safely if they don't exist
            // Note: We use a try-catch because dropping/adding constraints can be finicky in mixed states
            try {
                Schema::table('users', function (Blueprint $table) {
                    // We check if the key already exists by trying to add it or just relying on the try-catch
                    // Laravel doesn't have a built-in hasForeignKey, so we just wrap in try-catch
                    $table->foreign('shift_id')->references('id')->on('shifts')->nullOnDelete();
                    $table->foreign('office_location_id')->references('id')->on('office_locations')->nullOnDelete();
                });
            } catch (\Exception $e) {
                // Already exists or tables missing; either way we proceed safely
            }
        }
        
        // 3. Ensure applications table has custom_answers (if missed by 2026_03_12_124535)
        if (Schema::hasTable('applications')) {
            Schema::table('applications', function (Blueprint $table) {
                if (!Schema::hasColumn('applications', 'custom_answers')) {
                    $table->json('custom_answers')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration for a state-fixer as it could break production data
    }
};
