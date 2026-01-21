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
            $table->enum('type', ['text', 'metric'])->default('text')->after('description');
            $table->decimal('target_value', 10, 2)->nullable()->after('type');
            $table->decimal('current_value', 10, 2)->default(0)->after('target_value');
            $table->string('unit')->nullable()->after('current_value'); // %, currency, number
            $table->integer('weight')->default(1)->after('unit'); // relative importance
        });
    }

    public function down(): void
    {
        Schema::table('goals', function (Blueprint $table) {
            $table->dropColumn(['type', 'target_value', 'current_value', 'unit', 'weight']);
        });
    }
};
