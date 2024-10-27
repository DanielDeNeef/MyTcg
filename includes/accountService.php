<?php
    // Variables for storing errors and success messages
    $username_err = "";
    $password_err = "";
    $confirm_password_err = "";
    $update_success = "";

    /**
     * @description Checks if the username is empty or already exists in the database.
     * @param mysqli $conn The database connection object.
     * @param string $new_username The new username to validate.
     * @param int $current_user_id The ID of the currently logged-in user.
     * @return bool True if the username is valid; false otherwise.
     */
    function validateUsername($conn, $new_username, $current_user_id) {
        global $username_err;

        if (empty($new_username)) {
            $username_err = "Het e-mailadres mag niet leeg zijn";
            return false;
        } else {
            $sql = "SELECT id FROM user WHERE UserName = ? AND id != ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $new_username, $current_user_id);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $username_err = "Er bestaat reeds een gebruiker met dit e-mailadres.";
                    $stmt->close();
                    return false;
                }
                $stmt->close();
            }
        }
        return true;
    }

    /**
     * @description Checks if the password meets minimum length requirements and matches the confirmation password.
     * @param string $new_password The new password to validate.
     * @param string $confirm_password The password confirmation input.
     * @return bool True if the password is valid; false otherwise.
     */
    function validatePassword($new_password, $confirm_password) {
        global $password_err, $confirm_password_err;

        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $password_err = "Uw wachtwoord moet minimaal 6 karakters bevatten.";
                return false;
            } elseif ($new_password != $confirm_password) {
                $confirm_password_err = "Wachtwoorden komen niet overeen";
                return false;
            }
        }
        return true;
    }

    /**
     * @description Updates the username and if provided the password for the currently logged-in user.
     * @param mysqli $conn The database connection object.
     * @param string $new_username The new username to set.
     * @param string|null $new_password The new password to set, or null if not provided.
     * @param int $current_user_id The ID of the currently logged-in user.
     * @return void
     */
    function updateUser($conn, $new_username, $new_password, $current_user_id) {
        global $update_success;

        // Update username
        $sql = "UPDATE user SET UserName = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $new_username, $current_user_id);
            if ($stmt->execute()) {
                $update_success = "het updaten is gelukt.";
                $_SESSION['username'] = $new_username;
            }
            $stmt->close();
        }

        // Update password if provided
        if (!empty($new_password)) {
            $sql = "UPDATE user SET Password = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt->bind_param("si", $hashed_password, $current_user_id);
                if ($stmt->execute()) {
                    $update_success = "het updaten is gelukt.";
                }
                $stmt->close();
            }
        }
    }

    // Handle form submission for updating username or password
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_username = trim($_POST["username"]);
        $new_password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);
        $current_user_id = $_SESSION['id'];

        // Validate inputs
        $is_username_valid = validateUsername($conn, $new_username, $current_user_id);
        $is_password_valid = validatePassword($new_password, $confirm_password);

        // If there are no errors, update the user information
        if ($is_username_valid && $is_password_valid) {
            updateUser($conn, $new_username, $new_password, $current_user_id);
        }
    }
?>