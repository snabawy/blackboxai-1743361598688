<?php
// Verify required arguments
if ($argc < 3) {
    die("Usage: php simple_user_create.php <email> <password> <role> [firstName] [lastName]\n");
}

$email = $argv[1];
$password = $argv[2]; 
$role = $argv[3] ?? 'user';
$firstName = $argv[4] ?? 'User';
$lastName = $argv[5] ?? '';

$dbFile = __DIR__.'/database/schoolsync.db';

try {
    $pdo = new PDO('sqlite:'.$dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Minimal user creation with error handling
    // Check if user exists first
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo "User with email $email already exists\n";
        return;
    }

    $stmt = $pdo->prepare("INSERT INTO users (email, password, role, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
    $success = $stmt->execute([
        $email,
        password_hash($password, PASSWORD_DEFAULT),
        $role,
        $firstName,
        $lastName
    ]);
    
    if($success) {
        echo "User created successfully!\n";
        echo "Email: test@schoolsync.test\n";
        echo "Password: test123\n";
    } else {
        print_r($stmt->errorInfo());
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'no such table') !== false) {
        echo "Try running: php init_db.php first\n";
    }
}
?>