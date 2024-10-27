<?php

    /**
     * @description Searches for games based on a name filter.
     * @param mysqli $conn The database connection.
     * @param string $search The search term to filter games.
     * @return mysqli_result The result set of games matching the search.
     */
    function searchGames($conn, $search) {
        $searchTerm = "%" . $search . "%";
        $sql = "SELECT * FROM Game WHERE Name LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * @description Creates a new game with the given name and logo.
     * @param mysqli $conn The database connection.
     * @param string $gameName The name of the game.
     * @param string $gameLogo The URL of the game's logo.
     * @return bool True if the game was created successfully, false otherwise.
     */
    function createGame($conn, $gameName, $gameLogo) {
        $sql = "INSERT INTO Game (Name, Logo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $gameName, $gameLogo);
        return $stmt->execute();
    }

    /**
     * @description Updates an existing game's name and logo.
     * @param mysqli $conn The database connection.
     * @param int $gameId The ID of the game to update.
     * @param string $gameName The new name of the game.
     * @param string $gameLogo The new URL of the game's logo.
     * @return bool True if the game was updated successfully, false otherwise.
     */
    function updateGame($conn, $gameId, $gameName, $gameLogo) {
        $sql = "UPDATE Game SET Name = ?, Logo = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $gameName, $gameLogo, $gameId);
        return $stmt->execute();
    }

    /**
     * @description Deletes a game by its ID.
     * @param mysqli $conn The database connection.
     * @param int $gameId The ID of the game to delete.
     * @return bool True if the game was deleted successfully, false otherwise.
     */
    function deleteGame($conn, $gameId) {
        $sql = "DELETE FROM Game WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $gameId);
        return $stmt->execute();
    }

    /**
     * @description Displays a toast message to the user and redirects to a given page.
     * @param string $type The type of message (e.g., 'success', 'error', etc.).
     * @param string $message The message content.
     * @param string $redirectURL The URL to redirect to.
     */
    function setToastAndRedirect($type, $message, $redirectURL) {
        $_SESSION['toast'] = ['type' => $type, 'message' => $message];
        header("Location: $redirectURL");
        exit();
    }

    // Handling search
    $search = $_GET['search'] ?? "";
    $gamesResult = searchGames($conn, $search);

    // Handle create and update requests
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['create_game'])) {
            $gameName = trim($_POST['game_name']);
            $gameLogo = trim($_POST['game_logo']);
            if (createGame($conn, $gameName, $gameLogo)) {
                setToastAndRedirect('success', 'Spel succesvol aangemaakt.', 'collectionMng.php');
            } else {
                setToastAndRedirect('danger', 'Fout bij het aanmaken van het spel.', 'collectionMng.php');
            }
        }

        if (isset($_POST['update_game'])) {
            $gameId = $_POST['game_id'];
            $gameName = trim($_POST['update_game_name']);
            $gameLogo = trim($_POST['update_game_logo']);
            if (updateGame($conn, $gameId, $gameName, $gameLogo)) {
                setToastAndRedirect('success', 'Spel succesvol bijgewerkt.', 'collectionMng.php');
            } else {
                setToastAndRedirect('danger', 'Fout bij het bijwerken van het spel.', 'collectionMng.php');
            }
        }
    }

    // Handling deletion
    if (isset($_GET['id'])) {
        $gameId = $_GET['id'];
        if (deleteGame($conn, $gameId)) {
            setToastAndRedirect('success', 'Spel succesvol verwijderd.', 'collectionMng.php');
        } else {
            setToastAndRedirect('danger', 'Fout bij het verwijderen van het spel.', 'collectionMng.php');
        }
    }
?>
