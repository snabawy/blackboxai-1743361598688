<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/new_database.php';
require_once __DIR__.'/includes/auth_functions.php';

$db = new NewDatabase();

// Check for admin users
$admins = $db->query("SELECT * FROM users WHERE role = :role", ['role' => 'admin'])->fetchAll();

if (empty($admins)) {
    echo "No admin users found in database.\n";
} else {
    echo "Admin users found:\n";
    foreach ($admins as $admin) {
        echo "ID: {$admin['id']}, Email: {$admin['email']}\n";
    }
}

// Check if superadmin exists
$superadmin = $db->query("SELECT * FROM users WHERE role = :role", ['role' => 'superadmin'])->fetch();

if ($superadmin) {
    echo "\nSuperadmin found:\n";
    echo "ID: {$superadmin['id']}, Email: {$superadmin['email']}\n";
} else {
    echo "\nNo superadmin user found.\n";
}
?>