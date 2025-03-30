<?php
require_once __DIR__.'/config.php';

try {
    $pdo = new PDO('sqlite:'.DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if users table exists
    $tableCheck = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->fetch();
    
    if ($tableCheck) {
        echo "Users table exists!\n";
        // Test simple users query
        $userCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo "Found $userCount users in database\n";
    } else {
        echo "Users table does not exist\n";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>