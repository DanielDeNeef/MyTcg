<?php 
    
     // Handle Create Request
     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createUser'])) {
        $username = $_POST['username'];
        $type = $_POST['type'];
        $active = $_POST['active'];

        $stmt = $conn->prepare("INSERT INTO User (Username, Type, Active) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $type, $active);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'User created successfully'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error creating user'];
        }
    }
    
    // Handle Update Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $type = $_POST['type'];
        $active = $_POST['active'];

        $stmt = $conn->prepare("UPDATE User SET Username = ?, Type = ?, Active = ? WHERE Id = ?");
        $stmt->bind_param("ssii", $username, $type, $active, $id);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'User updated successfully'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error updating user'];
        }

        exit;
    }

    // Handle Delete Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM User WHERE Id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'User deleted successfully'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error deleting user'];
        }

        exit;
    }
?>