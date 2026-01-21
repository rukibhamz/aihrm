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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('clock_out');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->boolean('location_verified')->default(false)->after('longitude');
            $table->string('location_address')->nullable()->after('location_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'location_verified', 'location_address']);
        });
    }
};
