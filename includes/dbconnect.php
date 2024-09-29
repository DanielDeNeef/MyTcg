<?php
    session_start();
    $config = require dirname(__DIR__) . '/config/app.php';
    $servername = $config['database']["servername"];
    $username = $config['database']["username"];
    $password = $config['database']["password"];
    $dbname = $config['database']["dbname"];
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>