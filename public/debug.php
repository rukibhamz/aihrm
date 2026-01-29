<?php
echo "<h1>Server File Structure Debugger</h1>";
echo "<p>Current Script Path: " . __FILE__ . "</p>";
echo "<p>Current Working Dir: " . getcwd() . "</p>";

function listDir($dir) {
    echo "<h3>Listing: $dir</h3><ul>";
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $path = $dir . '/' . $file;
            $size = IS_DIR($path) ? '[DIR]' : round(filesize($path)/1024, 2) . ' KB';
            $color = ($file === 'vendor.zip') ? 'red' : 'black';
            $weight = ($file === 'vendor.zip') ? 'bold' : 'normal';
            echo "<li style='color:$color; font-weight:$weight'>$file ($size)</li>";
        }
    } else {
        echo "<li>[DIRECTORY NOT FOUND]</li>";
    }
    echo "</ul>";
}

// List Current Directory (should be public or public_html)
listDir(__DIR__);

// List Parent Directory (should be project root)
listDir(dirname(__DIR__));

echo "<h3>Database Configuration (.env)</h3>";
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    preg_match('/DB_HOST=(.*)/', $envContent, $dbHost);
    preg_match('/DB_DATABASE=(.*)/', $envContent, $dbName);
    preg_match('/DB_USERNAME=(.*)/', $envContent, $dbUser);
    
    echo "<ul>";
    echo "<li><strong>Host:</strong> " . ($dbHost[1] ?? 'Not Set') . "</li>";
    echo "<li><strong>Database:</strong> " . ($dbName[1] ?? 'Not Set') . "</li>";
    echo "<li><strong>Username:</strong> " . ($dbUser[1] ?? 'Not Set') . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color:red'><strong>.env file not found!</strong></p>";
}
?>
