<?php

    //Get the game_id from the URL
    if (isset($_GET['game_id'])) {
        $game_id = $_GET['game_id'];

        //Fetch the game details
        $gameSql = "SELECT * FROM Game WHERE id = ?";
        $stmt = $conn->prepare($gameSql);
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $gameResult = $stmt->get_result();
        $game = $gameResult->fetch_assoc();

        //IF game not found
        if (!$game) {
            header('Location: collectionMng.php');
            exit();
        }
    }else {
        //If no game_id is provided, redirect to collectionMng.php
        header('Location: collectionMng.php');
        exit();
    }

    //Handle Search functionality for game sets
    $setSearch = isset($_GET['set_search']) ? "%" . $_GET['set_search'] . "%" : "%";
    $sqlSets = "SELECT gs.*, g.Name AS game_name FROM cardSet gs JOIN Game g ON gs.GameId = g.id WHERE gs.Name LIKE ?";
    $stmtSets = $conn->prepare($sqlSets);
    $stmtSets->bind_param("s", $setSearch);
    $stmtSets->execute();
    $setsResult = $stmtSets->get_result();

    //Handle CRUD actions for Game Sets
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $set_logo = trim($_POST['logo']);
        $game_id = $_POST['game_id'];
        $set_name = trim($_POST['name']);  

        //Validation on the logo if it is a proper url
        if (!empty($set_logo) && !filter_var($set_logo, FILTER_VALIDATE_URL)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid Logo URL.'];
            header('Location: gameSets.php?game_id=' . $game_id);
            exit();
        }

        if (isset($_POST['create_set'])) {                      

            //Validate input
            if (!empty($set_name) && !empty($game_id)) {
                $sql = "INSERT INTO cardSet (Name, GameId, Logo) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sis", $set_name, $game_id, $set_logo);

                if ($stmt->execute()) {
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set created successfully.'];
                } else {
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error creating game set.'];
                }

                //Redirect back to the game sets page for the specific game
                header('Location: gameSets.php?game_id=' . $game_id);
                exit();
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Game ID and Set Name are required.'];
                header('Location: gameSets.php?game_id=' . $game_id);
                exit();
            }
        }

        if (isset($_POST['update_set'])) {
            //Update Game Set Logic
            $set_id = $_POST['id'];

            //Validate input
            if (!empty($set_name) && !empty($set_id)) {
                $sql = "UPDATE cardSet SET Name = ?, Logo = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $set_name, $set_logo, $set_id);

                if ($stmt->execute()) {
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set updated successfully.'];
                } else {
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error updating game set.'];
                }

                //Redirect back to the game sets page for the specific game
                header('Location: gameSets.php?game_id=' . $game_id);
                exit();
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Set ID and Set Name are required.'];
                header('Location: gameSets.php?game_id=' . $game_id);
                exit();
            }
        }
    }

    //Handle Game Set Deletion
    if (isset($_GET['set_id'])) {
        $set_id = $_GET['set_id'];
        $game_id = $_GET['game_id'];

        //Validate set_id
        if (!empty($set_id)) {
            $sql = "DELETE FROM cardSet WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $set_id);

            if ($stmt->execute()) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set deleted successfully.'];
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error deleting game set.'];
            }

            //Redirect back to the game sets page for the specific game
            header('Location: gameSets.php?game_id=' . $game_id);
            exit();
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Invalid Set ID.'];
            header('Location: gameSets.php?game_id=' . $game_id);
            exit();
        }
    }
?>
