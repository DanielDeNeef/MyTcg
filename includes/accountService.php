<?php
    // Variables for storing errors and success messages
    $username_err = "";
    $password_err = "";
    $confirm_password_err = "";
    $update_success = "";

    // Handle form submission for updating username or password
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_username = trim($_POST["username"]);
        $new_password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        // Validate username
        if (empty($new_username)) {
            $username_err = "Het e-mailadres mag niet leeg zijn";
        } else {
            $sql = "SELECT id FROM user WHERE UserName = ? AND id != ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $param_username, $param_id);
                $param_username = $new_username;
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $username_err = "Er bestaat reeds een gebruiker met dit e-mailadres.";
                }
                $stmt->close();
            }
        }

        // Validate password
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $password_err = "Uw wachtwoord moet minimaal 6 karakters bevatten.";
            } elseif ($new_password != $confirm_password) {
                $confirm_password_err = "Wachtwoorden komen niet overeen";
            }
        }

        // If there are no errors, update the user information
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            // Update username
            $sql = "UPDATE user SET UserName = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $param_username, $param_id);
                $param_username = $new_username;
                if ($stmt->execute()) {
                    $update_success = "het updaten is gelukt.";
                }
                $stmt->close();
            }

            // Update password if provided
            if (!empty($new_password)) {
                $sql = "UPDATE user SET Password = ? WHERE id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt->bind_param("si", $hashed_password, $param_id);
                    if ($stmt->execute()) {
                        $update_success = "het updaten is gelukt.";
                    }
                    $stmt->close();
                }
            }
        }
    }
?>