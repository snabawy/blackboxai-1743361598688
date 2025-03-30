<?php
// Minimal database setup script
$dbFile = __DIR__.'/database/schoolsync.db';

try {
    // Create database directory if needed
    if (!file_exists(dirname($dbFile))) {
        mkdir(dirname($dbFile), 0755, true);
    }

    // Connect to SQLite database
    $pdo = new PDO('sqlite:'.$dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create complete users table
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL,
        first_name TEXT,
        last_name TEXT,
        reset_token TEXT,
        reset_expires TEXT
    )');

    echo "Database initialized successfully!\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "SQLite version: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
}
?>