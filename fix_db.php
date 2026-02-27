<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

try {
    DB::table('migrations')->where('migration', 'like', '%employment_status%')->delete();
    
    // Drop foreign key from employees table
    Schema::table('employees', function (Blueprint $table) {
        $table->dropForeign(['employment_status_id']);
    });
    echo "Dropped employees fk.\n";

    Schema::dropIfExists('leave_type_employment_status');
    echo "Dropped orphaned pivot table.\n";

    Schema::dropIfExists('employment_statuses');
    echo "Dropped orphaned main table.\n";

    Artisan::call('migrate', ['--force' => true]);
    echo "Migrations ran successfully:\n";
    echo Artisan::output();
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
