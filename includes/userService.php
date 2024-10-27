<?php 

    /**
     * @description Creates a new user with the given username, type, and active status.
     * @param mysqli $conn The database connection.
     * @param string $username The username of the new user.
     * @param string $type The type of the user (e.g., admin, guest).
     * @param int $active Status of the user (1 for active, 0 for inactive).
     * @return bool True if the user is created successfully, false otherwise.
     */
    function createUser($conn, $username, $type, $active) {
        $stmt = $conn->prepare("INSERT INTO User (Username, Type, Active) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $type, $active);
        return $stmt->execute();
    }

    /**
     * @description Updates an existing user's details.
     * @param mysqli $conn The database connection.
     * @param int $id The ID of the user to update.
     * @param string $username The updated username.
     * @param string $type The updated type of the user.
     * @param int $active The updated active status (1 for active, 0 for inactive).
     * @return bool True if the user is updated successfully, false otherwise.
     */
    function updateUser($conn, $id, $username, $type, $active) {
        $stmt = $conn->prepare("UPDATE User SET Username = ?, Type = ?, Active = ? WHERE Id = ?");
        $stmt->bind_param("ssii", $username, $type, $active, $id);
        return $stmt->execute();
    }

    /**
     * @description Deletes a user by ID.
     * @param mysqli $conn The database connection.
     * @param int $id The ID of the user to delete.
     * @return bool True if the user is deleted successfully, false otherwise.
     */
    function deleteUser($conn, $id) {
        $stmt = $conn->prepare("DELETE FROM User WHERE Id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * @description Sets a toast message and handles the response redirection.
     * @param string $type The type of message ('success' or 'error').
     * @param string $message The message content.
     */
    function setToastMessage($type, $message) {
        $_SESSION['toast'] = ['type' => $type, 'message' => $message];
    }

    // Handle Create Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createUser'])) {
        $username = $_POST['username'];
        $type = $_POST['type'];
        $active = $_POST['active'];

        // Default password
        $defaultPassword = "azerty123";
        $hashedPassword = password_hash($defaultPassword, PASSWORD_DEFAULT);

        // Prepare SQL query to insert user with the hashed default password
        $stmt = $conn->prepare("INSERT INTO User (Username, Type, Active, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $username, $type, $active, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Gebruiker succesvol aangemaakt met standaard wachtwoord.'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Fout bij het aanmaken van de gebruiker.'];
        }
    }

    // Handle Update Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $type = $_POST['type'];
        $active = $_POST['active'];
        
        if (updateUser($conn, $id, $username, $type, $active)) {
            setToastMessage('success', 'Gebruiker succesvol bijgewerkt');
        } else {
            setToastMessage('error', 'Fout bij het bijwerken van de gebruiker');
        }
        exit;
    }

    // Handle Delete Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
        $id = $_POST['id'];
        
        if (deleteUser($conn, $id)) {
            setToastMessage('success', 'Gebruiker succesvol verwijderd');
        } else {
            setToastMessage('error', 'Fout bij het verwijderen van de gebruiker');
        }
        exit;
    }

?>
