<?php
echo "<h2>ERP System Setup Test</h2>";

// Test 1: PHP Version
echo "<h3>1. PHP Version:</h3>";
echo "PHP " . phpversion() . " ";
echo version_compare(phpversion(), '7.4.0', '>=') ? "✓ OK" : "✗ Need PHP 7.4+";

// Test 2: Required Extensions
echo "<h3>2. Required Extensions:</h3>";
$extensions = ['pdo', 'pdo_mysql', 'mysqli', 'json', 'session'];
foreach($extensions as $ext) {
    echo $ext . ": " . (extension_loaded($ext) ? "✓" : "✗") . "<br>";
}

// Test 3: Database Connection
echo "<h3>3. Database Connection:</h3>";
try {
    require_once 'config/database.php';
    $pdo = getConnection();
    echo "✓ Connected to database successfully<br>";
    
    // Test tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll();
    echo "Tables found: " . count($tables) . "<br>";
    
} catch(Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
}

// Test 4: Directory Permissions
echo "<h3>4. Directory Permissions:</h3>";
$dirs = ['assets/uploads', 'logs'];
foreach($dirs as $dir) {
    echo $dir . ": " . (is_writable($dir) ? "✓ Writable" : "✗ Not writable") . "<br>";
}

echo "<hr>";
echo "<a href='dashboard' class='btn btn-primary'>Go to Dashboard</a> ";
echo "<a href='login' class='btn btn-secondary'>Go to Login</a>";
?>