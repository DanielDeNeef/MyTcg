<?php
    // check if the user is already connected else redirect him to the login page
    if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
        header("location: pages/login.php");
        exit;
    }

    // logic to verify if the current user is an admin or not 
    $isAdmin = false;
    if($_SESSION["type"] == "Admin"){
        $isAdmin = true;
    }
?>