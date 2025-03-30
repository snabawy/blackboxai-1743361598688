<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/database.php';

$db = new Database();

// Create test admin user
$email = 'admin@schoolsync.test';
$password = password_hash('test123', PASSWORD_DEFAULT);

try {
    // Use direct parameter binding
    $stmt = $db->pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute([$email, $password]);
    echo "Test admin user created successfully!\n";
    echo "Email: $email\n";
    echo "Password: test123\n";
} catch (PDOException $e) {
    echo "Error creating test user: " . $e->getMessage() . "\n";
}
?>