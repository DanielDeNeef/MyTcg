<?php
    // Handle Search functionality for game sets
    $setSearch = isset($_GET['set_search']) ? "%" . $_GET['set_search'] . "%" : "%";
    $sqlSets = "SELECT gs.*, g.Name AS game_name FROM GameSet gs JOIN Game g ON gs.Game = g.id WHERE gs.Name LIKE ?";
    $stmtSets = $conn->prepare($sqlSets);
    $stmtSets->bind_param("s", $setSearch);
    $stmtSets->execute();
    $setsResult = $stmtSets->get_result();

    // Handle CRUD actions for Games
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['create_set'])) {
            // Create Game Set Logic
            $set_name = trim($_POST['set_name']);
            $game_id = $_POST['set_game_id'];
            $set_logo = trim($_POST['set_logo']);
            $sql = "INSERT INTO GameSet (Name, Game, Logo) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $set_name, $game_id, $set_logo);
            if ($stmt->execute()) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set created successfully.'];
            } else {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error creating game set.'];
            }
            header('Location: gameService.php');
            exit();
        }

        if (isset($_POST['update_set'])) {
            // Update Game Set Logic
            $set_id = $_POST['set_id'];
            $set_name = trim($_POST['update_set_name']);
            $set_logo = trim($_POST['update_set_logo']);
            $sql = "UPDATE GameSet SET Name = ?, Logo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $set_name, $set_logo, $set_id);
            if ($stmt->execute()) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set updated successfully.'];
            } else {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error updating game set.'];
            }
            header('Location: gameService.php');
            exit();
        }
    }

    // Handle Game Set Deletion
    if (isset($_GET['set_id'])) {
        $set_id = $_GET['set_id'];
        $sql = "DELETE FROM GameSet WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $set_id);
        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set deleted successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error deleting game set.'];
        }
        header('Location: gameService.php');
        exit();
    }
?>
