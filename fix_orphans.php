<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Employee;

try {
    $orphanedUsers = User::whereNotNull('provider')->whereDoesntHave('employee')->get();
    
    $count = 0;
    foreach ($orphanedUsers as $u) {
        Employee::create([
            'user_id' => $u->id,
            'status' => 'active'
        ]);
        $count++;
    }
    
    echo "Successfully created missing employee profiles for $count SSO users.\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
