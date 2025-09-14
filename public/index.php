<?php
// Simple test file to verify PHP is working
echo "PHP is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . getcwd() . "<br>";

// Check if we're in the right directory
echo "Parent directory: " . dirname(getcwd()) . "<br>";
echo "Laravel artisan exists: " . (file_exists('../artisan') ? 'Yes' : 'No') . "<br>";
echo "Laravel bootstrap exists: " . (file_exists('../bootstrap/app.php') ? 'Yes' : 'No') . "<br>";

// List files in parent directory
echo "Files in parent directory:<br>";
$files = scandir('..');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "- " . $file . "<br>";
    }
}

// Test database connection
try {
    $pdo = new PDO('sqlite:/app/database/database.sqlite');
    echo "Database connection: OK<br>";
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}

// Test Laravel bootstrap
try {
    require_once '../bootstrap/app.php';
    echo "Laravel bootstrap: OK<br>";
} catch (Exception $e) {
    echo "Laravel error: " . $e->getMessage() . "<br>";
}
?>