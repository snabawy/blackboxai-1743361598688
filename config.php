<?php
// Database Configuration
define('DB_TYPE', in_array('mysql', PDO::getAvailableDrivers()) ? 'mysql' : 'sqlite');
define('DB_PATH', __DIR__.'/database/schoolsync.db');

if (DB_TYPE === 'mysql') {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'schoolai_schoolsync');
    define('DB_USER', 'schoolai_schoolsync');
    define('DB_PASS', '+laEs^?B4T*?');
    define('DB_PORT', '3306');
    define('DB_CHARSET', 'utf8mb4');
}

// Application Settings
define('BASE_URL', 'http://localhost:8000');
define('DEBUG_MODE', true);

// Session configuration
require_once __DIR__.'/includes/new_database.php';
ini_set('session.save_handler', 'user');
session_set_save_handler(
    ['NewDatabase', 'open'],
    ['NewDatabase', 'close'], 
    ['NewDatabase', 'read'],
    ['NewDatabase', 'write'],
    ['NewDatabase', 'destroy'],
    ['NewDatabase', 'gc']
);
register_shutdown_function('session_write_close');

// Verify database connection
try {
    if (DB_TYPE === 'mysql') {
        $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=".DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } else {
        if (!file_exists(dirname(DB_PATH))) {
            mkdir(dirname(DB_PATH), 0755, true);
        }
        $pdo = new PDO('sqlite:'.DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
