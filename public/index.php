<?php
// Simple test file to verify PHP is working
echo "PHP is working!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . getcwd() . "<br>";
echo "Laravel exists: " . (file_exists('artisan') ? 'Yes' : 'No') . "<br>";

// Test database connection
try {
    $pdo = new PDO('sqlite:/var/www/html/database/database.sqlite');
    echo "Database connection: OK<br>";
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}

// Test Laravel bootstrap
try {
    require_once 'bootstrap/app.php';
    echo "Laravel bootstrap: OK<br>";
} catch (Exception $e) {
    echo "Laravel error: " . $e->getMessage() . "<br>";
}
?>