<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "SUCCESS\n";
    echo \Illuminate\Support\Facades\Artisan::output();
} catch (\Throwable $e) {
    echo "\n--- ERROR CAUGHT ---\n";
    echo $e->getMessage();
    echo "\n--------------------\n";
}
