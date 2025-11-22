<?php
/**
 * AI HR Management System - Installer
 * This script downloads the application artifact and extracts it.
 */

// Configuration
$repoUrl = 'https://github.com/rukibhamz/aihrm/archive/refs/heads/main.zip'; // Placeholder
$zipFile = 'aihrm_install.zip';
$extractPath = __DIR__;

// 1. Check Requirements
$requirements = [
    'php' => '8.1.0',
    'extensions' => ['openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'zip']
];

echo "<h1>AI HR Management System Installer</h1>";
echo "Checking requirements...<br>";

$errors = [];
if (version_compare(PHP_VERSION, $requirements['php'], '<')) {
    $errors[] = "PHP version {$requirements['php']} or higher is required. Current: " . PHP_VERSION;
}

foreach ($requirements['extensions'] as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "Extension '{$ext}' is missing.";
    }
}

if (!empty($errors)) {
    echo "<div style='color:red'>Installation cannot proceed:<ul>";
    foreach ($errors as $error) {
        echo "<li>{$error}</li>";
    }
    echo "</ul></div>";
    exit;
}

echo "<span style='color:green'>Requirements met.</span><br>";

// 2. Download Application (Simulated for now as we are building it)
// In a real scenario, we would download the ZIP here.
// if (!file_exists($zipFile)) {
//     echo "Downloading application...<br>";
//     file_put_contents($zipFile, fopen($repoUrl, 'r'));
// }

// 3. Extract Application
// $zip = new ZipArchive;
// if ($zip->open($zipFile) === TRUE) {
//     $zip->extractTo($extractPath);
//     $zip->close();
//     echo "Extracted successfully.<br>";
// } else {
//     echo "Failed to extract.<br>";
//     exit;
// }

// 4. Redirect to Laravel Installer
echo "Redirecting to setup wizard...<br>";
echo "<script>window.location.href = 'install.php';</script>";
?>
