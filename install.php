<?php
// install.php - The Setup Wizard

if (file_exists(__DIR__ . '/laravel_core/storage/installed')) {
    die("Application is already installed.");
}

$step = $_GET['step'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install AI HR Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen font-sans">
    <div class="w-full max-w-md bg-white p-8 rounded shadow-md border border-gray-200">
        <h1 class="text-2xl font-bold mb-6 text-center">Installation Wizard</h1>
        
        <?php if ($step == 1): ?>
        <form method="POST" action="install.php?step=2">
            <h2 class="text-xl mb-4">Database Configuration</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Database Host</label>
                <input type="text" name="db_host" value="127.0.0.1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Database Name</label>
                <input type="text" name="db_name" value="aihrm" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Database User</label>
                <input type="text" name="db_user" value="root" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Database Password</label>
                <input type="password" name="db_pass" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Next</button>
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
                // Write to .env
                $envContent = file_get_contents(__DIR__ . '/laravel_core/.env.example');
                $envContent = str_replace('DB_HOST=127.0.0.1', "DB_HOST=$host", $envContent);
                $envContent = str_replace('DB_DATABASE=laravel', "DB_DATABASE=$name", $envContent);
                $envContent = str_replace('DB_USERNAME=root', "DB_USERNAME=$user", $envContent);
                $envContent = str_replace('DB_PASSWORD=', "DB_PASSWORD=$pass", $envContent);
                $envContent = str_replace('APP_URL=http://localhost', 'APP_URL=' . $_SERVER['HTTP_HOST'], $envContent);
                
                file_put_contents(__DIR__ . '/laravel_core/.env', $envContent);
                
                echo "<div class='text-green-600 mb-4'>Database connected and configured!</div>";
                echo "<a href='install.php?step=3' class='bg-black text-white font-bold py-2 px-4 rounded block text-center'>Run Migrations</a>";
            } catch (PDOException $e) {
                echo "<div class='text-red-600 mb-4'>Connection failed: " . $e->getMessage() . "</div>";
                echo "<a href='install.php' class='text-blue-500'>Try Again</a>";
            }
            ?>
        <?php elseif ($step == 3): ?>
            <?php
            // Run Migrations
            // We need to bootstrap Laravel to run artisan
            require __DIR__ . '/laravel_core/vendor/autoload.php';
            $app = require_once __DIR__ . '/laravel_core/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            
            echo "<h2 class='text-xl mb-4'>Running Migrations...</h2>";
            echo "<pre class='bg-gray-200 p-4 rounded text-xs mb-4'>";
            try {
                $kernel->call('migrate:fresh', ['--force' => true]);
                echo $kernel->output();
                echo "</pre>";
                echo "<a href='install.php?step=4' class='bg-black text-white font-bold py-2 px-4 rounded block text-center'>Create Admin</a>";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
                echo "</pre>";
            }
            ?>
        <?php elseif ($step == 4): ?>
             <form method="POST" action="install.php?step=5">
                <h2 class="text-xl mb-4">Create Admin Account</h2>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <button type="submit" class="bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Finish Installation</button>
            </form>
        <?php elseif ($step == 5): ?>
            <?php
            require __DIR__ . '/laravel_core/vendor/autoload.php';
            $app = require_once __DIR__ . '/laravel_core/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            
            // Create User
            // Note: In a real app we would use the User model, but here we might need to be careful about namespaces.
            // Assuming App\Models\User exists.
            
            try {
                $user = new \App\Models\User();
                $user->name = $_POST['name'];
                $user->email = $_POST['email'];
                $user->password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $user->save();
                
                // Mark installed
                file_put_contents(__DIR__ . '/laravel_core/storage/installed', date('Y-m-d H:i:s'));
                
                echo "<div class='text-green-600 mb-4 text-center'>Installation Complete!</div>";
                echo "<a href='laravel_core/public' class='bg-black text-white font-bold py-2 px-4 rounded block text-center'>Go to Dashboard</a>";
            } catch (Exception $e) {
                 echo "Error: " . $e->getMessage();
            }
            ?>
        <?php endif; ?>
    </div>
</body>
</html>
