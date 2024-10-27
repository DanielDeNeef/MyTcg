<?php
    include 'dbconnect.php';

    /**
     * @description Fetches all available games from the database.
     * @param mysqli $conn The database connection.
     * @return mysqli_result|bool The result set containing games or false on failure.
     */
    function fetchGames($conn) {
        $gamesQuery = "SELECT id, Name FROM Game";
        return $conn->query($gamesQuery);
    }

    /**
     * @description Adds a card to the user's collection or updates the amount if the card already exists.
     * @param mysqli $conn The database connection.
     * @param int $userId The ID of the user.
     * @param int $cardId The ID of the card.
     * @param int $amount The amount of the card to add.
     * @return void
     */
    function addOrUpdateCard($conn, $userId, $cardId, $amount) {
        // Check if the card is already in the user's collection
        $checkCollection = "SELECT * FROM Collection WHERE userId = ? AND CardId = ?";
        $stmt = $conn->prepare($checkCollection);
        $stmt->bind_param("ii", $userId, $cardId);
        $stmt->execute();
        $collectionResult = $stmt->get_result();

        if ($collectionResult->num_rows > 0) {
            // Update the amount if the card is already in the collection
            $updateCollection = "UPDATE Collection SET Amount = Amount + ? WHERE UserId = ? AND CardId = ?";
            $stmt = $conn->prepare($updateCollection);
            $stmt->bind_param("iii", $amount, $userId, $cardId);
        } else {
            // Insert the card if it's not already in the collection
            $insertCollection = "INSERT INTO Collection (UserId, CardId, Amount) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertCollection);
            $stmt->bind_param("iii", $userId, $cardId, $amount);
        }
        $stmt->execute();
    }

    /**
     * @description get game sets associated with a specific game.
     * @param mysqli $conn The database connection.
     * @param int $gameId The ID of the selected game.
     * @return mysqli_result|bool The result set containing game sets or false on failure.
     */
    function fetchGameSets($conn, $gameId) {
        $gameSetsQuery = "SELECT id, Name FROM cardSet WHERE GameId = ?";
        $stmt = $conn->prepare($gameSetsQuery);
        $stmt->bind_param("i", $gameId);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * @description get cards available within a specific game set.
     * @param mysqli $conn The database connection.
     * @param int $gameSetId The ID of the selected game set.
     * @return mysqli_result|bool The result set containing cards or false on failure.
     */
    function fetchCards($conn, $gameSetId) {
        $cardsQuery = "SELECT id, Name FROM Card WHERE GameSetId = ?";
        $stmt = $conn->prepare($cardsQuery);
        $stmt->bind_param("i", $gameSetId);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * @description Updates the amount of a specific card in the user's collection.
     * @param mysqli $conn The database connection.
     * @param int $userId The ID of the user.
     * @param int $cardId The ID of the card.
     * @param int $newAmount The updated amount for the card.
     * @return string Returns "success" on successful update or "error" on failure.
     */
    function updateCardAmount($conn, $userId, $cardId, $newAmount) {
        $query = "UPDATE Collection SET Amount = ? WHERE CardId = ? AND UserId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iii', $newAmount, $cardId, $userId);
        
        if ($stmt->execute()) {
            $stmt->close();
            return "success";
        } else {
            $stmt->close();
            return "error";
        }
    }

    /**
     * @description Deletes a card from the user's collection.
     * @param mysqli $conn The database connection.
     * @param int $userId The ID of the user.
     * @param int $cardId The ID of the card.
     * @return string Returns "success" on successful deletion or "error" on failure.
     */
    function deleteCardFromCollection($conn, $userId, $cardId) {
        echo 'entering delete function ';
        $query = "DELETE FROM Collection WHERE UserId = ? AND CardId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $userId, $cardId);

        if ($stmt->execute()) {
            $stmt->close();
            return "success";
        } else {
            $stmt->close();
            return "error";
        }
    }
?>

<!-- REQUEST Handling -->

<?php
    $userId = $_SESSION['id'] ?? null;

    // Check for POST request and required POST variables
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cardId'], $_POST['currentUserId'], $_POST['newAmount'])) {
        $cardId = $_POST['cardId'];
        $newAmount = $_POST['newAmount'];
        $userId = $_POST['currentUserId'];
        
        if (!empty($cardId) && !empty($newAmount) && !empty($userId)) {
            echo updateCardAmount($conn, $userId, $cardId, $newAmount);
            exit;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteCardId'], $_POST['currentUserId'])) {
        $cardId = $_POST['deleteCardId'];
        $userId = $_POST['currentUserId'];
    
        if (!empty($cardId) && !empty($userId)) {
            echo deleteCardFromCollection($conn, $userId, $cardId);
            exit;
        }
    }
    

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['card'], $_POST['amount'])) {
        if (!$userId) {
            echo "User not logged in";
            exit;
        }

        // Handle form submission for adding a card
        $cardId = $_POST['card'];
        $amount = $_POST['amount'];
        addOrUpdateCard($conn, $userId, $cardId, $amount);

        $_SESSION['toast'] = ['type' => 'success', 'message' => 'kaart toegevoegd'];
        header('Location: ../pages/collection.php');
        exit;
    }

    if (isset($_GET['game'])) {
        $gameId = $_GET['game'];
        $gameSetsResult = fetchGameSets($conn, $gameId);

        // Build options for the game set dropdown
        $options = '<option value="" disabled selected>--Select Game Set--</option>';
        while ($row = $gameSetsResult->fetch_assoc()) {
            $options .= '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES) . '">' . htmlspecialchars($row['Name'], ENT_QUOTES) . '</option>';
        }
        echo $options;
        exit;
    }

    if (isset($_GET['gameSet'])) {
        $gameSetId = $_GET['gameSet'];
        $cardsResult = fetchCards($conn, $gameSetId);

        $options = '<option value="" disabled selected>--Select Card--</option>';
        while ($row = $cardsResult->fetch_assoc()) {
            $options .= '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES) . '">' . htmlspecialchars($row['Name'], ENT_QUOTES) . '</option>';
        }
        echo $options;
        exit;
    }

    // get games for dropdown (main page load)
    $gamesResult = fetchGames($conn);
?>
