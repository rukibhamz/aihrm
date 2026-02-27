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

    <h1>🚀 System Diagnostic & Fixer v2.1</h1>";

// 0. Emergency zip extractor (Before anything else)
$basePath = dirname(__DIR__);
// Check both root and public (current dir)
$vendorZip = file_exists($basePath . '/vendor.zip') ? $basePath . '/vendor.zip' : __DIR__ . '/vendor.zip';

if (isset($_GET['extract_vendor'])) {
    ini_set('memory_limit', '512M');
    set_time_limit(300);
    echo "<div class='step'><div class='step-title'>0. Extracting vendor.zip</div>";
    if (file_exists($vendorZip)) {
        $zip = new ZipArchive;
        if ($zip->open($vendorZip) === TRUE) {
            $zip->extractTo($basePath);
            $zip->close();
            echo "<div class='status ok'><strong>SUCCESS:</strong> vendor.zip extracted successfully to $basePath.</div>";
            echo "<script>setTimeout(function(){ window.location.href = 'fix-sys.php'; }, 3000);</script>";
            echo "<p>Reloading page in 3 seconds...</p>";
        } else {
            echo "<div class='status error'><strong>ERROR:</strong> Failed to open vendor.zip</div>";
        }
    } else {
        echo "<div class='status error'><strong>ERROR:</strong> vendor.zip not found in ".dirname($vendorZip)."</div>";
    }
    echo "</div>";
}

if (file_exists($vendorZip) && !isset($_GET['extract_vendor'])) {
    echo "<div class='status info' style='border-left: 5px solid #2563eb;'>
        <strong>📦 Recovery Archive Detect (vendor.zip)</strong><br>
        Found in: <code>".basename(dirname($vendorZip))."/vendor.zip</code><br>
        If you are experiencing 'Class not found' or 'autoload' errors, your vendor folder is likely corrupted.<br>
        <a href='?extract_vendor=1' style='display:inline-block;margin-top:10px;padding:10px 20px;background:#2563eb;color:white;text-decoration:none;border-radius:5px;font-weight:bold;'>📂 Extract vendor.zip & Overwrite Files</a>
    </div>";
}


// 1. Check Directory Structure
// $basePath already defined above
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

// 3. Booting Application
echo "<div class='step'><div class='step-title'>3. Booting Application</div>";
try {
    require $vendorPath . '/autoload.php';
    $app = require $basePath . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "<div class='status ok'>Application booted successfully!</div>";
} catch (Throwable $e) {
    echo "<div class='status error'><strong>BOOTSTRAP FAILED</strong><br>Message: " . $e->getMessage() . "</div>";
    
    // Check for vendor.zip again
    $vendorZipParams = '';
    // $vendorZip is defined at top of file
    if (file_exists($vendorZip)) {
        $foundPath = basename(dirname($vendorZip)) . '/vendor.zip';
        $vendorZipParams = "<div style='margin-top:10px; padding:10px; background:#fff; border:1px solid #ccc;'>
            <strong>POSSIBLE FIX DETECTED:</strong><br>
            Found <code>$foundPath</code>.<br>
            <a href='?extract_vendor=1' style='display:inline-block; margin-top:5px; padding:5px 10px; background:#dc2626; color:white; text-decoration:none; border-radius:3px;'>💥 Emergency Re-Extract (Overwrite)</a>
        </div>";
    } else {
         $vendorZipParams = "<div style='margin-top:10px; padding:10px; background:#fff; border:1px solid #ccc; color: #b91c1c;'>
            <strong>DEBUG - ARCHIVE NOT FOUND:</strong><br>
            Tried to find vendor.zip at: <br>
            1. " . $basePath . '/vendor.zip' . " (Root)<br>
            2. " . __DIR__ . '/vendor.zip' . " (Public/Current)<br>
            <br>Please upload <code>vendor.zip</code> to one of these locations.
        </div>";
    }

    echo $vendorZipParams;
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

// Installation Logic
if (isset($_GET['repair_schema'])) {
    echo "\n--- STARTING SCHEMA REPAIR ---\n";
    try {
        \Illuminate\Support\Facades\DB::table('migrations')->where('migration', 'like', '%employment_status%')->delete();
        \Illuminate\Support\Facades\Schema::table('employees', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->dropForeign(['employment_status_id']);
        });
        \Illuminate\Support\Facades\Schema::dropIfExists('leave_type_employment_status');
        \Illuminate\Support\Facades\Schema::dropIfExists('employment_statuses');
        echo "Orphaned schema dropped successfully.\n";
    } catch (\Throwable $e) {
        echo "Cleanup note: " . $e->getMessage() . "\n";
    }
    run_cmd($kernel, 'migrate', ['--force' => true]);
    echo "--- SCHEMA REPAIR COMPLETE ---\n";
} elseif (isset($_GET['fix_sso_employees'])) {
    echo "\n--- FIXING ORPHANED SSO EMPLOYEES ---\n";
    try {
        $orphans = \App\Models\User::whereNotNull('provider')->whereDoesntHave('employee')->get();
        $count = 0;
        foreach ($orphans as $u) {
            \App\Models\Employee::create([
                'user_id' => $u->id,
                'status' => 'active'
            ]);
            $count++;
        }
        echo "Successfully created Employee profiles for {$count} SSO users.\n";
    } catch (\Throwable $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} elseif (isset($_GET['install_sys'])) {
    echo "\n--- STARTING INSTALLATION ---\n";
    run_cmd($kernel, 'migrate:fresh', ['--force' => true]);
    run_cmd($kernel, 'db:seed', ['--force' => true]);
    run_cmd($kernel, 'storage:link');
    run_cmd($kernel, 'key:generate', ['--force' => true]);
    echo "--- INSTALLATION COMPLETE ---\n";
    echo "</pre><div class='status ok'>Installation Finished. You can now login.</div>";
} else {
    run_cmd($kernel, 'migrate', ['--force' => true]);
    // Force Run Permission Seeder (Critical for access)
    run_cmd($kernel, 'db:seed', ['--class' => 'RolePermissionSeeder', '--force' => true]);
    run_cmd($kernel, 'storage:link');
}

run_cmd($kernel, 'view:clear');

// Clear Spatie Permission Cache
try {
    app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    echo "<div>✔ Permission Cache Cleared.</div>";
} catch (Exception $e) { echo "<div>⚠ Could not clear permission cache: ".$e->getMessage()."</div>"; }

// 5. Force Reset Admin User
echo "<div class='step'><div class='step-title'>5. Resetting Admin Account</div>";
try {
    // Check DB Connection
    $dbConnection = DB::connection()->getName();
    $dbName = DB::connection()->getDatabaseName();
    echo "<div>Using Database Connection: <strong>$dbConnection</strong> ($dbName)</div>";

    // Use firstOrNew to avoid 'NOT NULL' constraint on insert
    $user = \App\Models\User::firstOrNew(['email' => 'admin@aihrm.com']);
    $user->name = 'System Admin';
    if (!$user->exists) {
        $user->password = \Illuminate\Support\Facades\Hash::make('password');
    }
    $user->save();
    
    // Assign Role if exists
    if (class_exists(\Spatie\Permission\Models\Role::class)) {
        try {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
            $user->assignRole($role);
            echo "<div>✔ Assigned 'Admin' role.</div>";
        } catch (Exception $ex) { echo "<div>⚠ Role assignment skipped (spatie not ready).</div>"; }
    }
    
    echo "<div class='status ok'>
        <strong>Admin Account Verified:</strong><br>
        Email: <code>admin@aihrm.com</code><br>
        Password: <code>password</code> (if new)
    </div>";
} catch (Exception $e) {
    echo "<div class='status error'>Failed to reset user: " . $e->getMessage() . "</div>";
}
echo "</div>";

// Final Cache Wipe to ensure new Gate logic applies
run_cmd($kernel, 'optimize:clear');

echo "</pre><div class='status ok' style='text-align:center; font-weight:bold; margin-top:2rem;'>
        🎉 SYSTEM REPAIRED! <br>
        <div style='font-size:0.9em; color:#666; margin-bottom:10px;'>Running on: $dbConnection</div>
        <a href='/public/' style='display:inline-block; margin-top:10px; padding:10px 20px; background:#166534; color:white; text-decoration:none; border-radius:5px;'>GO TO LOGIN</a>
        <br><br>
        <div style='border-top:1px solid #ccc; padding-top:10px; margin-top:10px;'>
             <strong>Database Schema Stuck? (1146 error)</strong><br>
             <a href='?repair_schema=1' style='color:#d97706; font-size:0.9em; font-weight:bold; display:inline-block; margin-bottom: 10px;'>🛠 Repair Orphaned Tables</a><br>

             <strong>SSO Users Missing From Employees List?</strong><br>
             <a href='?fix_sso_employees=1' style='color:#059669; font-size:0.9em; font-weight:bold; display:inline-block; margin-bottom: 10px;'>👥 Restore Missing SSO Employees</a><br>

             <strong>Fresh Install Needed?</strong><br>
             <a href='?install_sys=1' onclick=\"return confirm('WARNING: This will WIPE the database. Continue?')\" style='color:#dc2626; font-size:0.9em;'>⚠ Run Full Installation (Wipe DB)</a>
        </div>
      </div>";
echo "</div></body></html>";
