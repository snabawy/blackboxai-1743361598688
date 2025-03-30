<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/auth_functions.php';
require_once __DIR__.'/includes/security_functions.php';

$auth = new Auth();
$security = new Security();

// Redirect based on authentication status
if ($auth->isLoggedIn()) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: /admin/dashboard.php');
            break;
        case 'teacher':
            header('Location: /teacher/dashboard.php');
            break;
        case 'student':
            header('Location: /student/dashboard.php');
            break;
    }
} else {
    header('Location: /login.php');
}
exit;
?>