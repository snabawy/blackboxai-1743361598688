<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/database.php';

try {
    $db = new Database();
    
    // Create tables if they don't exist
    // SQLite compatible table creation
    $db->pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL,
        first_name TEXT,
        last_name TEXT,
        reset_token TEXT,
        reset_expires TEXT
    )");
    
    echo "Database setup completed successfully!\n";
} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}
?>