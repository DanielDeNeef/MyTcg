<?php
    //Fetch all cards from the set if set_id and game_id are provided
    if (isset($_GET['set_id']) && isset($_GET['game_id'])) {
        $set_id = $_GET['set_id'];
        $game_id = $_GET['game_id'];

        //Get set details
        $setSql = "SELECT * FROM cardSet WHERE id = ?";
        $stmt = $conn->prepare($setSql);
        $stmt->bind_param("i", $set_id);
        $stmt->execute();
        $setResult = $stmt->get_result();
        $set = $setResult->fetch_assoc();

        //Fetch all cards in this game set
        $sqlCards = "SELECT * FROM card WHERE GameSetID = ?";
        $stmtCards = $conn->prepare($sqlCards);
        $stmtCards->bind_param("i", $set_id);
        $stmtCards->execute();
        $cardsResult = $stmtCards->get_result();
    } else {
        //Redirect if set_id or game_id is missing
        exit();
    }

    //Handle create and update actions for cards
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $card_name = trim($_POST['name']);
        $card_number = trim($_POST['number']);
        $card_image = trim($_POST['image']);
        $set_id = $_POST['set_id'];
        $game_id = $_POST['game_id'];
        $card_id = isset($_POST['card_id']) ? $_POST['card_id'] : null;

        //Ensure required fields are filled
        if (empty($card_name) || empty($set_id) || empty($card_number)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Set ID, card number, and card name are required.'];
            header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
            exit();
        }

        //Check for duplicate card number within the set
        $checkCardNumberSql = "SELECT id FROM card WHERE CardNumber = ? AND GameSetID = ?";
        $stmtCheck = $conn->prepare($checkCardNumberSql);
        $stmtCheck->bind_param("si", $card_number, $set_id);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        $stmtCheck->bind_result($existing_card_id);
        $stmtCheck->fetch();

        //Prevent duplication: for creation or when the existing card with the same number is different
        if ($stmtCheck->num_rows > 0 && (isset($_POST['create_card']) || $existing_card_id != $card_id)) {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Card number already exists in this set.'];
            header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
            exit();
        }

        //Create card
        if (isset($_POST['create_card'])) {
            $sql = "INSERT INTO card (Name, CardNumber, Image, GameSetID) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $card_name, $card_number, $card_image, $set_id);

            if ($stmt->execute()) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Card created successfully.'];
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error creating card.'];
            }
        }

        //Update card
        if (isset($_POST['update_card']) && !empty($card_id)) {
            $sql = "UPDATE card SET Name = ?, CardNumber = ?, Image = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $card_name, $card_number, $card_image, $card_id);

            if ($stmt->execute()) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Card updated successfully.'];
            } else {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error updating card.'];
            }
        }

        //Redirect back to the card management page
        header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
        exit();
    }

    //Handle card deletion
    if (isset($_GET['card_id'])) {
        $card_id = $_GET['card_id'];
        $set_id = $_GET['set_id'];

        if (!empty($card_id)) {
            $sql = "DELETE FROM card WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $card_id);

            if ($stmt->execute()) {
                $_SESSION['toast'] = ['type' => 'success', 'message' => 'Card deleted successfully.'];
            } else {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error deleting card.'];
            }

            //Redirect back to the card management page
            header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
            exit();
        }
    }
?>
