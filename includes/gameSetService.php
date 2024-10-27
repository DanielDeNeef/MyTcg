<?php

    /**
     * @description get the game details based on the provided game ID.
     * @param mysqli $conn The database connection.
     * @param int $game_id The ID of the game.
     * @return array|bool The game details or false if not found.
     */
    function fetchGameDetails($conn, $game_id) {
        $gameSql = "SELECT * FROM Game WHERE id = ?";
        $stmt = $conn->prepare($gameSql);
        $stmt->bind_param("i", $game_id);
        $stmt->execute();
        $gameResult = $stmt->get_result();
        return $gameResult->fetch_assoc();
    }

    /**
     * @description Searches for game sets based on the provided search term.
     * @param mysqli $conn The database connection.
     * @param string $setSearch The search term for game sets.
     * @return mysqli_result The result set containing game sets.
     */
    function searchGameSets($conn, $setSearch) {
        $sqlSets = "SELECT gs.*, g.Name AS game_name FROM cardSet gs JOIN Game g ON gs.GameId = g.id WHERE gs.Name LIKE ?";
        $stmtSets = $conn->prepare($sqlSets);
        $stmtSets->bind_param("s", $setSearch);
        $stmtSets->execute();
        return $stmtSets->get_result();
    }

    /**
     * @description Validates input for creating or updating a game set.
     * @param string $set_name The name of the game set.
     * @param int $game_id The ID of the game.
     * @param string|null $set_logo The logo URL of the game set.
     * @return bool True if valid, false otherwise.
     */
    function validateGameSetInputs($set_name,$set_Code, $game_id, $set_logo = null) {
        return !empty($set_name) && !empty($game_id) && (is_null($set_logo) || filter_var($set_logo, FILTER_VALIDATE_URL));
    }

    /**
     * @description Creates a new game set in the database.
     * @param mysqli $conn The database connection.
     * @param string $set_name The name of the game set.
     * @param int $game_id The ID of the game.
     * @param string|null $set_logo The logo URL of the game set.
     * @return bool True on success, false on failure.
     */
    function createGameSet($conn, $set_name, $setCode ,$game_id, $set_logo) {
        $sql = "INSERT INTO cardSet (Name,Code, GameId, Logo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $set_name,$setCode, $game_id, $set_logo);
        return $stmt->execute();
    }

    /**
     * @description Updates an existing game set in the database.
     * @param mysqli $conn The database connection.
     * @param int $set_id The ID of the game set to update.
     * @param string $set_code The code of the game set.
     * @param string $set_name The new name of the game set.
     * @param string|null $set_logo The new logo URL of the game set.
     * @return bool True on success, false on failure.
     */
    function updateGameSet($conn, $set_id, $set_code, $set_name, $set_logo) {
        $sql = "UPDATE cardSet SET Name = ?, Logo = ?, Code = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $set_name, $set_logo, $set_code, $set_id);
        return $stmt->execute();
    }

    /**
     * @description Deletes a game set from the database.
     * @param mysqli $conn The database connection.
     * @param int $set_id The ID of the game set to delete.
     * @return bool True on success, false on failure.
     */
    function deleteGameSet($conn, $set_id) {
        $sql = "DELETE FROM cardSet WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $set_id);
        return $stmt->execute();
    }

    // Get the game_id from the URL
    if (isset($_GET['game_id'])) {
        $game_id = $_GET['game_id'];

        // get the game details
        $game = fetchGameDetails($conn, $game_id);

        // IF game not found
        if (!$game) {
            header('Location: collectionMng.php');
            exit();
        }
    } else {
        // If no game_id is provided, redirect to collectionMng.php
        header('Location: collectionMng.php');
        exit();
    }

    // Handle Search functionality for game sets
    $setSearch = isset($_GET['set_search']) ? "%" . $_GET['set_search'] . "%" : "%";
    $setsResult = searchGameSets($conn, $setSearch);

    // Handle CRUD actions for Game Sets
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $set_logo = trim($_POST['logo']);
        $game_id = $_POST['game_id'];
        $set_name = trim($_POST['name']);
        $set_code = trim($_POST['code']);


        if (!validateGameSetInputs($set_name,$set_code, $game_id, $set_logo)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Ongeldig logo-URL of ontbrekende vereiste velden.'];
            header('Location: gameSets.php?game_id=' . $game_id);
            exit();
        }

        if (isset($_POST['create_set'])) {

            if (createGameSet($conn, $set_name,$set_code, $game_id, $set_logo)) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set succesvol aangemaakt.'];
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Fout bij het aanmaken van game set.'];
            }

            // Redirect back to the game sets page for the specific game
            header('Location: gameSets.php?game_id=' . $game_id);
            exit();
        }

        if (isset($_POST['update_set'])) {

            $set_id = $_POST['id'];

            if (!empty($set_id)) {
                if (updateGameSet($conn, $set_id,$_POST['code'], $set_name, $set_logo)) {
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set succesvol bijgewerkt.'];
                } else {
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Fout bij het bijwerken van game set.'];
                }

                // Redirect back to the game sets page for the specific game
                header('Location: gameSets.php?game_id=' . $game_id);
                exit();
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Set ID en Set Naam zijn verplicht.'];
                header('Location: gameSets.php?game_id=' . $game_id);
                exit();
            }
        }
    }

    // Handle Game Set Deletion
    if (isset($_GET['set_id'])) {
        $set_id = $_GET['set_id'];
        $game_id = $_GET['game_id'];

        // Validate set_id
        if (!empty($set_id)) {
            if (deleteGameSet($conn, $set_id)) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Game set succesvol verwijderd.'];
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Fout bij het verwijderen van game set.'];
            }

            // Redirect back to the game sets page for the specific game
            header('Location: gameSets.php?game_id=' . $game_id);
            exit();
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Ongeldig Set ID.'];
            header('Location: gameSets.php?game_id=' . $game_id);
            exit();
        }
    }
?>
