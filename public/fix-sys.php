<?php
/**
 * Emergency System Fixer
 * 
 * This script bypasses Laravel's routing system to clear caches and run migrations/seeds.
 * It is intended for cPanel/Shared Hosting environments where terminal access is unavailable
 * and route caching prevents accessing new routes.
 */

define('LARAVEL_START', microtime(true));

// Search for critical files in common locations
$pathsToCheck = [
    __DIR__ . '/../vendor/autoload.php',           // Standard: public/../vendor
    __DIR__ . '/vendor/autoload.php',              // Flat: everything in public_html
    __DIR__ . '/../../vendor/autoload.php',        // Nested: public/html/public/../vendor
    $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php', // cPanel: public_html/../vendor
    __DIR__ . '/aihrm/vendor/autoload.php',        // Subfolder install
];

$autoloadPath = null;
foreach ($pathsToCheck as $path) {
    if (file_exists($path)) {
        $autoloadPath = $path;
        break;
    }
}

if (!$autoloadPath) {
    echo "<pre>CRITICAL ERROR: Could not find vendor/autoload.php.\n";
    echo "Checked the following paths:\n";
    foreach ($pathsToCheck as $p) {
        echo "- " . realpath($p) . " ($p)\n";
    }
    echo "\nPlease ensure you uploaded the 'vendor' folder and this script is near it.</pre>";
    die();
}

require $autoloadPath;

// Find bootstrap/app.php relative to the autoloader
$appBootstrapPath = dirname($autoloadPath) . '/../bootstrap/app.php';

if (!file_exists($appBootstrapPath)) {
    die("Error: Found autoloader but could not find bootstrap/app.php at: $appBootstrapPath");
}

$app = require $appBootstrapPath;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>System Fixer</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; padding: 2rem; background: #f9f9f9; color: #333; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { font-size: 1.5rem; margin-bottom: 1rem; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
        pre { background: #1e1e1e; color: #a5f3fc; padding: 1.5rem; border-radius: 6px; overflow-x: auto; }
        .success { color: #4ade80; }
        .error { color: #f87171; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; margin-top: 1rem; }
        .warning { background: #fff7ed; color: #c2410c; padding: 1rem; border-radius: 4px; margin-top: 1.5rem; border: 1px solid #ffedd5; }
    </style>
</head>
<body>
<div class='container'>
    <h1>System Fixer Output</h1>
    <pre>";

function run_command($kernel, $command, $params = []) {
    echo "Running <strong>$command</strong>... ";
    try {
        $kernel->call($command, $params);
        echo "<span class='success'>Done</span>\n";
        echo htmlspecialchars($kernel->output()) . "\n";
    } catch (Exception $e) {
        echo "<span class='error'>Failed: " . $e->getMessage() . "</span>\n";
    }
    echo "---------------------------------------------------\n";
}

try {
    // 1. Force Clear Optimization Cache (Routes, Config, etc.)
    run_command($kernel, 'optimize:clear');

    // 2. Generate Key if missing
    $envFile = __DIR__ . '/../.env';
    if (file_exists($envFile) && !str_contains(file_get_contents($envFile), 'APP_KEY=base64')) {
        run_command($kernel, 'key:generate', ['--force' => true]);
    } else {
        echo "APP_KEY check: Exists. Skipping generation.\n---------------------------------------------------\n";
    }

    // 3. Migrate and Seed
    run_command($kernel, 'migrate', ['--force' => true]);
    run_command($kernel, 'db:seed', ['--force' => true]);

    // 4. Storage Link
    run_command($kernel, 'storage:link');

} catch (Exception $e) {
    echo "\nCRITICAL ERROR: " . $e->getMessage();
}

echo "</pre>
    <div class='warning'>
        <strong>SECURITY WARNING:</strong> Please delete this file (fix-sys.php) from your server immediately after use.
    </div>
    <a href='/' class='btn'>Go to Homepage</a>
</div>
</body>
</html>";
