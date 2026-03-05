<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    Schema::dropIfExists('approval_logs');
    Schema::dropIfExists('approval_requests');
    Schema::dropIfExists('approval_chain_steps');
    Schema::dropIfExists('approval_chains');
    
    // Remove from migrations table
    DB::table('migrations')->where('migration', 'like', '%approval_chains%')->delete();
    DB::table('migrations')->where('migration', 'like', '%approval_chain_steps%')->delete();
    DB::table('migrations')->where('migration', 'like', '%approval_requests%')->delete();
    DB::table('migrations')->where('migration', 'like', '%approval_logs%')->delete();

    $kernel->call('migrate', ['--force' => true]);
    echo $kernel->output();
    echo "\nSUCCESS\n";
} catch (\Exception $e) {
    echo "ERROR:\n" . $e->getMessage() . "\n";
}
