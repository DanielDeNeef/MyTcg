<?php

    // Set session timeout duration 
    $session_timeout = 900;

    // Check if user is logged in
    if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
        // Check if the last activity time is set
        if (isset($_SESSION['last_activity'])) {
            // Calculate the session lifetime
            $session_lifetime = time() - $_SESSION['last_activity'];

            // If the session lifetime exceeds the timeout duration
            if ($session_lifetime > $session_timeout) {
                // Destroy the session
                session_unset();
                session_destroy();
                // Redirect to the login page
                header("location: pages/login.php?session_expired=1");
                exit;
            }
        }
        // Update last activity time
        $_SESSION['last_activity'] = time();
    } else {
        // If the user is not logged in, redirect them to the login page
        header("location: pages/login.php");
        exit;
    }
?>
