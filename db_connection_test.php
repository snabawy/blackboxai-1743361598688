<?php
require_once __DIR__.'/config.php';

try {
    $pdo = new PDO('sqlite:'.DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test simple query
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = ?");
    $stmt->execute(['admin']);
    $admins = $stmt->fetchAll();
    
    echo count($admins)." admin users found\n";
    foreach ($admins as $admin) {
        echo "ID: {$admin['id']}, Email: {$admin['email']}\n";
    }
} catch (PDOException $e) {
    echo "Error: ".$e->getMessage()."\n";
}
?>