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
        .status.info { background-color: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
        .status.ok { background-color: #f0fdf4; border-color: #bbf7d0; color: #166534; }
        .status.error { background-color: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .step { margin-bottom: 1.5rem; }
        .step-title { font-weight: bold; margin-bottom: 0.5rem; display: flex; items-center; gap: 0.5rem; }
        code { background: #e5e7eb; padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.9em; }
    </style>
</head>
<body>
<div class='container'>
    <h1>🚀 System Diagnostic & Fixer v2</h1>";

// 1. Check Directory Structure
$basePath = dirname(__DIR__);
$vendorPath = $basePath . '/vendor';

echo "<div class='step'><div class='step-title'>1. Checking Critical Directories</div>";

// Check for nested vendor (common zip mistake)
if (is_dir($basePath . '/vendor/vendor')) {
    die("<div class='status error'><strong>FOLDER ERROR DETECTED:</strong><br>Found <code>vendor/vendor</code>. <br>You extracted the zip file inside the existing folder.<br>Please move the files out one level.</div>");
}

if (!file_exists($vendorPath . '/autoload.php')) {
    echo "<div class='status error'><strong>CRITICAL FAILURE:</strong><br>The file <code>vendor/autoload.php</code> was not found.</div>";
    
    // Check inside vendor
    if (is_dir($vendorPath)) {
        echo "<h3>Contents of 'vendor' folder:</h3><pre>";
        $vFiles = scandir($vendorPath);
        $count = 0;
        foreach ($vFiles as $file) {
            if ($file === '.' || $file === '..') continue;
            echo "$file\n";
            $count++;
            if ($count > 10) { echo "... (and more)\n"; break; }
        }
        if ($count === 0) echo "[EMPTY FOLDER]";
        echo "</pre>";
    } else {
         echo "<h3>'vendor' folder is missing entirely.</h3>";
    }

    echo "<h3>Current Files in Root Folder:</h3><pre>";
    $files = scandir($basePath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $label = is_dir($basePath . '/' . $file) ? '[DIR] ' : '      ';
        echo $label . $file . "\n";
    }
    echo "</pre>";
    
    echo "<div class='status info'><strong>How to Fix:</strong><br>
    The <code>vendor</code> folder exists but does not contain <code>autoload.php</code>.<br>
    This usually means the folder is empty or corrupted.<br>
    1. <strong>Delete</strong> the current <code>vendor</code> folder on the server.<br>
    2. Upload your <code>vendor.zip</code> again.<br>
    3. Extract it carefully.</div>";
    die("</div></body></html>");
}

echo "<div class='status ok'>Vendor directory and autoloader found.</div></div>";

// 2. Fix Permissions (Before Laravel Boots)
$storagePath = $basePath . '/storage';
$bootstrapPath = $basePath . '/bootstrap/cache';

echo "<div class='step'><div class='step-title'>2. Checking Permissions</div>";
$directories = [$storagePath, $bootstrapPath];
foreach ($directories as $dir) {
    if (!file_exists($dir)) { mkdir($dir, 0755, true); }
    try { chmod($dir, 0775); echo "<div style='color:green'>✔ Set 0775 on " . basename($dir) . "</div>"; } catch (Exception $e) {}
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
    echo "<div class='status error'><strong>BOOTSTRAP FAILED</strong><br>Message: " . $e->getMessage() . "</div>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    die("</div></body></html>");
}
echo "</div>";

// 4. Run Artisan Maintenance Commands
echo "<div class='step'><div class='step-title'>4. Running Maintenance Commands</div><pre>";
function run_cmd($kernel, $cmd, $params = []) {
    echo "> php artisan $cmd ... ";
    try { $kernel->call($cmd, $params); echo "DONE\n"; } 
    catch (Exception $e) { echo "FAILED: " . $e->getMessage() . "\n"; }
}

run_cmd($kernel, 'optimize:clear');
run_cmd($kernel, 'config:clear');

// Check/Generate Key
$envFile = $basePath . '/.env';
if (!file_exists($envFile) && file_exists($basePath . '/.env.example')) {
    copy($basePath . '/.env.example', $envFile);
}
if (file_exists($envFile) && !str_contains(file_get_contents($envFile), 'APP_KEY=base64')) {
    run_cmd($kernel, 'key:generate', ['--force' => true]);
}

run_cmd($kernel, 'migrate', ['--force' => true]);
run_cmd($kernel, 'storage:link');
run_cmd($kernel, 'view:clear');

echo "</pre><div class='status ok' style='text-align:center; font-weight:bold; margin-top:2rem;'>
        🎉 SYSTEM REPAIRED! <br>
        <a href='/public/' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>GO TO HOMEPAGE</a>
      </div>";
echo "</div></body></html>";
