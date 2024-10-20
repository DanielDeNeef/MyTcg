<?php

// Handle Search functionality for games
$search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
$sql = "SELECT * FROM Game WHERE Name LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search);
$stmt->execute();
$gamesResult = $stmt->get_result();

//handle Create and update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_game'])) {
        // Create Game Logic
        $game_name = trim($_POST['game_name']);
        $game_logo = trim($_POST['game_logo']);
        $sql = "INSERT INTO Game (Name, Logo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $game_name, $game_logo);
        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game created successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error creating game.'];
        }
        header('Location: collectionMng.php');
    }

    if (isset($_POST['update_game'])) {
        // Update Game Logic
        $game_id = $_POST['game_id'];
        $game_name = trim($_POST['update_game_name']);
        $game_logo = trim($_POST['update_game_logo']);
        $sql = "UPDATE Game SET Name = ?, Logo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $game_name, $game_logo, $game_id);
        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game updated successfully.'];
        } else {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error updating game.'];
        }
        header('Location: collectionMng.php');
    }
}

// Handle Game Deletion in delete_game.php
if (isset($_GET['id'])) {
    $game_id = $_GET['id'];
    $sql = "DELETE FROM Game WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $game_id);
    if ($stmt->execute()) {
        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game deleted successfully.'];
    } else {
        $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error deleting game.'];
    }
    header('Location: collectionMng.php');
}

?>