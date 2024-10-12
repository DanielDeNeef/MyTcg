<?php
    // check if the user is already connected else redirect him to the login page
    if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
        header("location: pages/login.php");
        exit;
    }
?>