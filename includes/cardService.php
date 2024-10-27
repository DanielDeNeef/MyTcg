<?php

/**
 * @description Fetches the card set details based on the provided set ID.
 *
 * @param mysqli $conn The database connection.
 * @param int $set_id The ID of the card set.
 * @return array|bool The card set details or false on failure.
 */
function fetchCardSetDetails($conn, $set_id) {
    $setSql = "SELECT * FROM cardSet WHERE id = ?";
    $stmt = $conn->prepare($setSql);
    $stmt->bind_param("i", $set_id);
    $stmt->execute();
    $setResult = $stmt->get_result();
    return $setResult->fetch_assoc();
}

/**
 * @description Fetches all cards associated with a specific game set.
 *
 * @param mysqli $conn The database connection.
 * @param int $set_id The ID of the card set.
 * @return mysqli_result|bool The result set containing cards or false on failure.
 */
function fetchCardsInSet($conn, $set_id) {
    $sqlCards = "SELECT * FROM card WHERE GameSetID = ?";
    $stmtCards = $conn->prepare($sqlCards);
    $stmtCards->bind_param("i", $set_id);
    $stmtCards->execute();
    return $stmtCards->get_result();
}

/**
 * @description Validates card creation or update inputs.
 *
 * @param string $card_name The name of the card.
 * @param int $set_id The ID of the card set.
 * @param string $card_number The card number.
 * @return bool True if valid, false otherwise.
 */
function validateCardInputs($card_name, $set_id, $card_number) {
    return !empty($card_name) && !empty($set_id) && !empty($card_number);
}

/**
 * @description Updates an existing card in the database.
 *
 * @param mysqli $conn The database connection.
 * @param int $card_id The ID of the card to update.
 * @param string $card_name The new name of the card.
 * @param string $card_number The new card number.
 * @param string $card_image The new image of the card.
 * @return bool True on success, false on failure.
 */
function updateCard($conn, $card_id, $card_name, $card_number, $card_image) {
    // First, get the existing card details to compare card numbers
    $existingCardQuery = "SELECT CardNumber, GameSetID FROM card WHERE id = ?";
    $stmt = $conn->prepare($existingCardQuery);
    $stmt->bind_param("i", $card_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existingCard = $result->fetch_assoc();

    $stmt->close();

    $sql = "UPDATE card SET Name = ?, CardNumber = ?, Image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $card_name, $card_number, $card_image, $card_id);
    return $stmt->execute();
    exit;
}

/**
 * @description Checks for duplicate card numbers in the set.
 *
 * @param mysqli $conn The database connection.
 * @param string $card_number The card number to check.
 * @param int $set_id The ID of the card set.
 * @param int|null $exclude_card_id The card ID to exclude from the check (for updates).
 * @return bool True if duplicate exists, false otherwise.
 */
function checkDuplicateCardNumber($conn, $card_number, $set_id, $exclude_card_id) {
    $checkCardNumberSql = "SELECT id FROM card WHERE CardNumber = ? AND GameSetID = ?";
    $stmtCheck = $conn->prepare($checkCardNumberSql);
    $stmtCheck->bind_param("si", $card_number, $set_id);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    return $stmtCheck->num_rows > 0 && (!is_null($exclude_card_id) ? $stmtCheck->fetch() && $exclude_card_id != $stmtCheck->fetch() : true);
}

/**
 * @description Creates a new card in the database.
 *
 * @param mysqli $conn The database connection.
 * @param string $card_name The name of the card.
 * @param string $card_number The card number.
 * @param string $card_image The image of the card.
 * @param int $set_id The ID of the card set.
 * @return bool True on success, false on failure.
 */
function createCard($conn, $card_name, $card_number, $card_image, $set_id) {
    $sql = "INSERT INTO card (Name, CardNumber, Image, GameSetID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $card_name, $card_number, $card_image, $set_id);
    return $stmt->execute();
}

/**
 * @description Deletes a card from the database.
 *
 * @param mysqli $conn The database connection.
 * @param int $card_id The ID of the card to delete.
 * @return bool True on success, false on failure.
 */
function deleteCard($conn, $card_id) {
    $sql = "DELETE FROM card WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $card_id);
    return $stmt->execute();
}

// Handle GET requests for card set and cards
if (isset($_GET['set_id']) && isset($_GET['game_id'])) {
    $set_id = $_GET['set_id'];
    $game_id = $_GET['game_id'];

    // Get set details
    $set = fetchCardSetDetails($conn, $set_id);
    if (!$set) {
        exit('Set niet gevonden');
    }

    // Get all cards in this game set
    $cardsResult = fetchCardsInSet($conn, $set_id);
} else {
    // Redirect if set_id or game_id is missing
    exit();
}

// Handle create and update actions for cards
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_name = trim($_POST['name']);
    $card_number = trim($_POST['number']);
    $card_image = trim($_POST['image']);
    $set_id = $_POST['set_id'];
    $game_id = $_POST['game_id'];
    $card_id = isset($_POST['card_id']) ? $_POST['card_id'] : null;

    // Ensure required fields are filled
    if (!validateCardInputs($card_name, $set_id, $card_number)) {
        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Set ID, kaartnummer en kaartnaam zijn verplicht.'];
        header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
        exit();
    }

    // Update card
    if (isset($_POST['update_card']) && !empty($card_id)) {
        if (updateCard($conn, $card_id, $card_name, $card_number, $card_image)) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Kaart succesvol bijgewerkt.'];
        } else {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Fout bij het bijwerken van de kaart of kaartnummer bestaat al.'];
        }
    }else{
        // Check for duplicate card number within the set
        if (checkDuplicateCardNumber($conn, $card_number, $set_id, $card_id)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Kaartnummer bestaat al in deze set.'];
            header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
            exit();
        }

        // Create card
        if (isset($_POST['create_card'])) {
            if (createCard($conn, $card_name, $card_number, $card_image, $set_id)) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Kaart succesvol aangemaakt.'];
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Fout bij het aanmaken van de kaart.'];
            }
        }    
    }   

    // Redirect back to the card management page
    header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
    exit();
}

// Handle card deletion
if (isset($_GET['card_id'])) {
    $card_id = $_GET['card_id'];
    $set_id = $_GET['set_id'];
    $game_id = $_GET['game_id'];

    if (!empty($card_id)) {
        if (deleteCard($conn, $card_id)) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'Kaart succesvol verwijderd.'];
        } else {
            $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Fout bij het verwijderen van de kaart.'];
        }

        // Redirect back to the card management page
        header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
        exit();
    }
}
?>
