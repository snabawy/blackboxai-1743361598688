<?php
require_once __DIR__.'/includes/auth_functions.php';
$auth = new Auth();
$auth->logout();
header('Location: login.php');
?>