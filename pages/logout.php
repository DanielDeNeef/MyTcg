<?php
// Initialize the session
session_start();

$config = require dirname(__DIR__) . '/config/app.php'; 
$baseUrl = $config['url']["baseUrl"];

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("location: ". $baseUrl ."pages/login.php");
exit;
?>
