<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'key')) {
                $table->string('key')->unique();
            }
            if (!Schema::hasColumn('settings', 'value')) {
                $table->text('value')->nullable();
            }
            if (!Schema::hasColumn('settings', 'type')) {
                $table->string('type')->default('text');
            }
        });
    }

    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['key', 'value', 'type']);
        });
    }
};
