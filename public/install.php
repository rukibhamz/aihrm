<?php
// install.php - The Setup Wizard
set_time_limit(0);
ini_set('memory_limit', '512M');

if (file_exists(__DIR__ . '/../storage/installed') && file_exists(__DIR__ . '/../.env')) {
    header("Content-Type: text/html; charset=UTF-8");
    echo "AIHRM is already installed. If you need to re-install, please remove the <code>storage/installed</code> file.";
    exit;
}

// Enable output flushing for migration feedback
if (ob_get_level() == 0) ob_start();
ob_implicit_flush(1);

$step = $_GET['step'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install AIHRM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .btn-primary {
            background: #000;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.15s ease;
            border: none;
            width: 100%;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #1a1a1a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e5e5;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }
        .input-field:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-neutral-50 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-4">
                <div class="w-10 h-10 bg-black rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">AI</span>
                </div>
                <span class="font-bold text-2xl tracking-tight">AIHRM</span>
            </div>
            <p class="text-neutral-600 text-sm">Installation Wizard</p>
        </div>

        <!-- Progress Steps -->
        <div class="flex justify-between mb-8">
            <div class="flex-1 text-center">
                <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-sm font-semibold <?= $step >= 1 ? 'bg-black text-white' : 'bg-neutral-200 text-neutral-500' ?>">1</div>
                <div class="text-xs mt-2 text-neutral-600">Database</div>
            </div>
            <div class="flex-1 border-t border-neutral-300 mt-4"></div>
            <div class="flex-1 text-center">
                <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-sm font-semibold <?= $step >= 3 ? 'bg-black text-white' : 'bg-neutral-200 text-neutral-500' ?>">2</div>
                <div class="text-xs mt-2 text-neutral-600">Setup</div>
            </div>
            <div class="flex-1 border-t border-neutral-300 mt-4"></div>
            <div class="flex-1 text-center">
                <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-sm font-semibold <?= $step >= 4 ? 'bg-black text-white' : 'bg-neutral-200 text-neutral-500' ?>">3</div>
                <div class="text-xs mt-2 text-neutral-600">Complete</div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
            <?php if ($step == 1): ?>
            <form method="POST" action="install.php?step=2" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Database Host</label>
                    <input type="text" name="db_host" value="127.0.0.1" class="input-field" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Database Name</label>
                    <input type="text" name="db_name" value="aihrm" class="input-field" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Database Username</label>
                    <input type="text" name="db_user" value="root" class="input-field" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Database Password</label>
                    <input type="password" name="db_pass" class="input-field" placeholder="Leave blank if none">
                </div>
                <button type="submit" class="btn-primary mt-6">Continue</button>
            </form>
            <?php elseif ($step == 2): ?>
                <?php
                // Process DB Config
                $host = $_POST['db_host'];
                $name = $_POST['db_name'];
                $user = $_POST['db_user'];
                $pass = $_POST['db_pass'];
                
                // Test Connection
                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Read .env.example
                    $envPath = __DIR__ . '/../.env.example';
                    if (!file_exists($envPath)) {
                        throw new Exception(".env.example not found in root directory.");
                    }
                    $envContent = file_get_contents($envPath);
                    
                    // Replace database configuration
                    $envContent = preg_replace('/^#?\s?DB_CONNECTION=.*/m', 'DB_CONNECTION=mysql', $envContent);
                    $envContent = preg_replace('/^#?\s?DB_HOST=.*/m', "DB_HOST=$host", $envContent);
                    $envContent = preg_replace('/^#?\s?DB_PORT=.*/m', 'DB_PORT=3306', $envContent);
                    $envContent = preg_replace('/^#?\s?DB_DATABASE=.*/m', "DB_DATABASE=$name", $envContent);
                    $envContent = preg_replace('/^#?\s?DB_USERNAME=.*/m', "DB_USERNAME=$user", $envContent);
                    $envContent = preg_replace('/^#?\s?DB_PASSWORD=.*/m', "DB_PASSWORD=$pass", $envContent);
                    
                    // Set APP_URL
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                    $appUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];
                    // Remove /public/install.php if present in host
                    $appUrl = preg_replace('/\/public\/install\.php.*/', '', $appUrl);
                    $envContent = preg_replace('/^#?\s?APP_URL=.*/m', "APP_URL=$appUrl", $envContent);
                    
                    // Generate APP_KEY if not set
                    if (!preg_match('/APP_KEY=base64:.+/', $envContent)) {
                        $key = 'base64:' . base64_encode(random_bytes(32));
                        $envContent = preg_replace('/^#?\s?APP_KEY=.*/m', "APP_KEY=$key", $envContent);
                    }
                    
                    // Write to .env
                    if (!is_writable(__DIR__ . '/../')) {
                        throw new Exception("Root directory is not writable. Please check permissions.");
                    }
                    file_put_contents(__DIR__ . '/../.env', $envContent);
                    
                    echo '<div class="text-center">';
                    echo '<svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    echo '<h2 class="text-xl font-bold mb-2">Connected & Configured!</h2>';
                    echo '<p class="text-neutral-600 text-sm mb-6">Environment file saved successfully</p>';
                    echo '<a href="install.php?step=3" class="btn-primary inline-block">Continue Setup</a>';
                    echo '</div>';
                } catch (Exception $e) {
                    echo '<div class="text-center">';
                    echo '<svg class="w-16 h-16 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                    echo '<h2 class="text-xl font-bold mb-2 text-red-600">Configuration Failed</h2>';
                    echo '<p class="text-neutral-600 text-sm mb-6">' . htmlspecialchars($e->getMessage()) . '</p>';
                    echo '<a href="install.php" class="btn-primary inline-block">Try Again</a>';
                    echo '</div>';
                }
                ?>
            <?php elseif ($step == 3): ?>
                <?php
                // Run Migrations with Seeders
                try {
                    $vendorPath = __DIR__ . '/../vendor/autoload.php';
                    if (!file_exists($vendorPath)) {
                        throw new Exception("Vendor directory missing. Please run <code>composer install</code>, upload the <code>vendor</code> folder, or use the <a href='fix-sys.php' class='text-blue-600 underline'>System Fixer</a> to extract a <code>vendor.zip</code> archive.");
                    }
                    require $vendorPath;

                    $appPath = __DIR__ . '/../bootstrap/app.php';
                    if (!file_exists($appPath)) {
                        throw new Exception("Bootstrap app file missing. Please check your installation files.");
                    }
                    $app = require_once $appPath;
                    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
                    
                    echo "<h2 class='text-xl font-bold mb-4'>Setting up Database...</h2>";
                    echo "<div class='bg-neutral-50 p-4 rounded-lg mb-4 max-h-64 overflow-y-auto text-xs font-mono' id='migration-output'>";
                    
                    // Flush buffer to show header
                    ob_flush();
                    flush();

                    // Run migrations with seeders
                    $kernel->call('migrate:fresh', ['--force' => true, '--seed' => true]);
                    $output = $kernel->output();
                    echo nl2br(htmlspecialchars($output));
                    
                    // Final flush
                    ob_end_flush();
                    flush();

                    echo "</div>";
                    echo "<div class='space-y-2 mb-6'>";
                    echo "<div class='flex items-center gap-2 text-sm text-green-600'><svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg> Database tables created</div>";
                    echo "<div class='flex items-center gap-2 text-sm text-green-600'><svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg> Sample data seeded</div>";
                    echo "<div class='flex items-center gap-2 text-sm text-green-600'><svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg> Roles and permissions configured</div>";
                    echo "</div>";
                    echo "<a href='install.php?step=4' class='btn-primary inline-block'>Finish Installation</a>";
                } catch (Exception $e) {
                    echo htmlspecialchars($e->getMessage());
                    echo "</div>";
                    echo "<div class='text-red-600 text-sm mt-4'>Installation failed. Please check your configuration.</div>";
                    echo "<a href='install.php' class='btn-primary inline-block mt-4'>Start Over</a>";
                }
                ?>
            <?php elseif ($step == 4): ?>
             <form method="POST" action="install.php?step=5">
                <h2 class="text-xl font-bold mb-4">Admin Account Setup</h2>
                <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-6 text-sm">
                    <strong>💡 Quick Setup:</strong><br>
                    Leave fields blank to use default credentials:<br>
                    Email: <code class="font-mono">admin@aihrm.com</code><br>
                    Password: <code class="font-mono">password</code>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Admin Name</label>
                        <input type="text" name="admin_name" placeholder="Leave blank for default"
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Admin Email</label>
                        <input type="email" name="admin_email" placeholder="Leave blank for admin@aihrm.com"
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Admin Password</label>
                        <input type="password" name="admin_password" placeholder="Leave blank for 'password'"
                            class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                </div>
                
                <button type="submit" class="btn-primary mt-6">Complete Installation</button>
            </form>
            <?php elseif ($step == 5): ?>
                <div class="text-center">
                    <?php
                    try {
                        require __DIR__ . '/../vendor/autoload.php';
                        $app = require_once __DIR__ . '/../bootstrap/app.php';
                        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
                        $app->make(Illuminate\Contracts\Http\Kernel::class)->handle(Illuminate\Http\Request::capture());

                        $name = !empty($_POST['admin_name']) ? $_POST['admin_name'] : 'System Admin';
                        $email = !empty($_POST['admin_email']) ? $_POST['admin_email'] : 'admin@aihrm.com';
                        $pass = !empty($_POST['admin_password']) ? $_POST['admin_password'] : 'password';

                        // Create/Update Admin User
                        $user = \App\Models\User::updateOrCreate(
                            ['email' => $email],
                            [
                                'name' => $name,
                                'password' => \Illuminate\Support\Facades\Hash::make($pass),
                                'email_verified_at' => now(),
                            ]
                        );

                        // Assign role
                        try {
                            $user->assignRole('Admin');
                        } catch (Exception $e) {
                            // If role exists but not assigned, or permissions need sync
                            echo "<p class='text-xs text-neutral-500 mb-2'>Role assignment notice: " . htmlspecialchars($e->getMessage()) . "</p>";
                        }

                        // Mark as installed
                        if (!is_dir(__DIR__ . '/../storage')) mkdir(__DIR__ . '/../storage', 0755, true);
                        file_put_contents(__DIR__ . '/../storage/installed', date('Y-m-d H:i:s'));

                        echo '<svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                        echo '<h2 class="text-2xl font-bold mb-2">Installation Complete!</h2>';
                        echo '<p class="text-neutral-600 mb-8">AIHRM has been successfully installed and configured.</p>';
                        echo '<p class="text-sm text-neutral-500 mb-8">Admin: <strong>' . htmlspecialchars($email) . '</strong></p>';
                        echo '<a href="index.php" class="btn-primary inline-block">Go to Login</a>';
                    } catch (Exception $e) {
                        echo '<svg class="w-16 h-16 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                        echo '<h2 class="text-xl font-bold mb-2 text-red-600">User Creation Failed</h2>';
                        echo '<p class="text-neutral-600 text-sm mb-6">' . htmlspecialchars($e->getMessage()) . '</p>';
                        echo '<a href="install.php?step=4" class="btn-primary inline-block">Try Again</a>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <p class="text-center text-xs text-neutral-500 mt-6">&copy; <?= date('Y') ?> AIHRM. All rights reserved.</p>
    </div>
</body>
</html>
