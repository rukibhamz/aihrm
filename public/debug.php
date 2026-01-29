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
?>
