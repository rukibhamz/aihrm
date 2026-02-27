<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        if (!Schema::hasColumn('employees', 'employment_status_id')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('employment_status_id')->nullable()->constrained('employment_statuses')->nullOnDelete();
            });
        } else {
            Schema::table('employees', function (Blueprint $table) {
                // Attempt to add foreign key if column already exists
                // We wrap this in a try-catch in case the foreign key also somehow already exists
                try {
                    $table->foreign('employment_status_id')->references('id')->on('employment_statuses')->nullOnDelete();
                } catch (\Exception $e) {
                    // Ignore if FK already exists
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['employment_status_id']);
            $table->dropColumn('employment_status_id');
        });
        Schema::dropIfExists('employment_statuses');
    }
};
