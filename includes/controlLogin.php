<?php
    session_start();
    $config = require dirname(__DIR__) . '/config/app.php'; 
    $baseUrl = $config['url']["baseUrl"];

    // check if the user is already connected else redirect him to the login page
    if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
        header("location: ". $baseUrl ."pages/login.php");
        exit;
    }

    // logic to verify if the current user is an admin or not 
    $isAdmin = false;
    if($_SESSION["type"] == "Admin"){
        $isAdmin = true;
    }

    include 'session_timeout.php';
?>