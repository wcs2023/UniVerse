<?php
// Debug script to check routing
session_start();
require "../app/core/init.php";

echo "<h2>Debug Information</h2>";
echo "<pre>";

// Check session
echo "<b>Session:</b>\n";
print_r($_SESSION);

// Check URL
echo "\n<b>URL Parameter:</b>\n";
echo "url = " . ($_GET['url'] ?? 'NOT SET') . "\n";

// Test controller file
$url = 'amentorships';
$filename = "../app/controllers/".ucfirst($url).".php";
echo "\n<b>Controller Check:</b>\n";
echo "URL: $url\n";
echo "Filename: $filename\n";
echo "File exists: " . (file_exists($filename) ? 'YES' : 'NO') . "\n";

if (file_exists($filename)) {
    echo "File is readable: " . (is_readable($filename) ? 'YES' : 'NO') . "\n";
    echo "File size: " . filesize($filename) . " bytes\n";
}

// Check if class can be loaded
if (file_exists($filename)) {
    require_once $filename;
    echo "Class exists: " . (class_exists('Amentorships') ? 'YES' : 'NO') . "\n";
    
    if (class_exists('Amentorships')) {
        echo "Class methods:\n";
        print_r(get_class_methods('Amentorships'));
    }
}

echo "\n<b>Directory Listing:</b>\n";
$controllers = scandir("../app/controllers/");
foreach ($controllers as $file) {
    if (strpos(strtolower($file), 'mentor') !== false) {
        echo "$file\n";
    }
}

echo "</pre>";
