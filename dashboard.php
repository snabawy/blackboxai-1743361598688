<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/auth_functions.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

echo "Welcome to your dashboard!";
echo "<br>Logged in as: ".$_SESSION['email'];
echo "<br><a href='logout.php'>Logout</a>";
?>