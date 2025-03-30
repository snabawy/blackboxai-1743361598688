<?php
$dbFile = __DIR__.'/database/schoolsync.db';

try {
    $pdo = new PDO('sqlite:'.$dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test simplest possible query
    $result = $pdo->query("SELECT 1")->fetch();
    echo "Database connection successful!\n";
    
    // Test table exists
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->fetch();
    echo $tables ? "Users table exists\n" : "Users table missing\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>