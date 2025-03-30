<?php
require_once __DIR__.'/config.php';

try {
    $pdo = new PDO('sqlite:'.DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test 1: Simple query
    $stmt = $pdo->query("SELECT 1");
    echo "Basic query test: ".($stmt ? "PASSED" : "FAILED")."\n";

    // Test 2: Admin user query with named parameters
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = :role");
    $stmt->execute(['role' => 'admin']);
    echo "Named parameter test: ".count($stmt->fetchAll())." admin users found\n";

    // Test 3: Admin user query with positional parameters
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = ?");
    $stmt->execute(['admin']);
    echo "Positional parameter test: ".count($stmt->fetchAll())." admin users found\n";

} catch (PDOException $e) {
    echo "TEST FAILED: ".$e->getMessage()."\n";
}
?>