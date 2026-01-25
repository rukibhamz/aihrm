<?php
// Enable Error Reporting IMMEDIATELY
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>System Diagnostic & Fixer</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; padding: 2rem; background: #f3f4f6; color: #1f2937; line-height: 1.5; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h1 { font-size: 1.5rem; margin-bottom: 1.5rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem; color: #111827; }
        pre { background: #111827; color: #a5f3fc; padding: 1rem; border-radius: 8px; overflow-x: auto; font-size: 0.9rem; margin-top: 1rem; }
        .status { margin-bottom: 1rem; padding: 1rem; border-radius: 8px; border: 1px solid transparent; }
        .status.ok { background-color: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .status.error { background-color: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .step { margin-bottom: 1.5rem; }
        .step-title { font-weight: bold; margin-bottom: 0.5rem; display: flex; items-center; gap: 0.5rem; }
        code { background: #e5e7eb; padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.9em; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🚀 System Diagnostic & Fixer</h1>";

// 1. Check Directory Structure
$basePath = dirname(__DIR__);
$vendorPath = $basePath . '/vendor';
$storagePath = $basePath . '/storage';
$bootstrapPath = $basePath . '/bootstrap/cache';

echo "<div class='step'><div class='step-title'>1. Checking Critical Directories</div>";
if (!file_exists($vendorPath)) {
    die("<div class='status error'>CRITICAL FAILURE: 'vendor' directory not found at <code>$vendorPath</code>.<br>Please upload the vendor folder.</div>");
}
echo "<div class='status ok'>Vendor directory found.</div>";

// 2. Fix Permissions (Before Laravel Boots)
echo "<div class='step-title'>2. Checking Permissions</div>";
$directories = [$storagePath, $bootstrapPath];
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Try to set permissions
    try {
        chmod($dir, 0775);
        echo "<div style='color:green'>✔ Set 0775 on " . basename($dir) . "</div>";
    } catch (Exception $e) {
        echo "<div style='color:orange'>⚠ Could not chmod " . basename($dir) . " (Might be OK if owner is correct).</div>";
    }
    
    if (!is_writable($dir)) {
        echo "<div class='status error'>ERROR: " . basename($dir) . " is not writable! Usage may fail.</div>";
    }
}
echo "</div>";

// 3. Bootstrap Laravel (With Try-Catch)
echo "<div class='step'><div class='step-title'>3. Booting Application</div>";
try {
    require $vendorPath . '/autoload.php';
    $app = require $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "<div class='status ok'>Application booted successfully!</div>";
} catch (Throwable $e) {
    echo "<div class='status error'><strong>BOOTSTRAP FAILED</strong><br>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " on line " . $e->getLine();
    echo "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    die("</div></body></html>");
}
echo "</div>";

// 4. Run Artisan Maintenance Commands
echo "<div class='step'><div class='step-title'>4. Running Maintenance Commands</div><pre>";

function run_cmd($kernel, $cmd, $params = []) {
    echo "> php artisan $cmd ... ";
    try {
        $kernel->call($cmd, $params);
        echo "DONE\n";
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}

// Clear all caches first
run_cmd($kernel, 'optimize:clear');
run_cmd($kernel, 'config:clear');

// Check/Generate Key
$envFile = $basePath . '/.env';
if (!file_exists($envFile)) {
    if (file_exists($basePath . '/.env.example')) {
        copy($basePath . '/.env.example', $envFile);
        echo "Created .env from .env.example\n";
    } else {
        echo "WARNING: No .env or .env.example found!\n";
    }
}
if (file_exists($envFile) && !str_contains(file_get_contents($envFile), 'APP_KEY=base64')) {
    run_cmd($kernel, 'key:generate', ['--force' => true]);
}

run_cmd($kernel, 'migrate', ['--force' => true]);
run_cmd($kernel, 'storage:link');
run_cmd($kernel, 'view:clear');

echo "</pre></div>";
echo "<div class='status ok' style='text-align:center; font-weight:bold; margin-top:2rem;'>
        🎉 SYSTEM REPAIRED! <br>
        <a href='/public/' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>GO TO HOMEPAGE</a>
      </div>";

echo "</div></body></html>";
