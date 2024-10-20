<?php
    //get all the cards from the set
    if (isset($_GET['set_id']) && isset($_GET['game_id'])) {
        $set_id = $_GET['set_id'];
        $game_id = $_GET['game_id'];

        //Get the set and game details
        $setSql = "SELECT * FROM cardSet WHERE id = ?";
        $stmt = $conn->prepare($setSql);
        $stmt->bind_param("i", $set_id);
        $stmt->execute();
        $setResult = $stmt->get_result();
        $set = $setResult->fetch_assoc();

        //Get the cards for this game set
        $sqlCards = "SELECT * FROM card WHERE GameSetID = ?";
        $stmtCards = $conn->prepare($sqlCards);
        $stmtCards->bind_param("i", $set_id);
        $stmtCards->execute();
        $cardsResult = $stmtCards->get_result();
    } else {
        
        //If no set_id or game_id is provided, redirect back
        // header('Location: gameSets.php?game_id=' . $game_id);
        exit();
    }
    
    //Handle create and update actions for Cards
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        

        if (isset($_POST['create_card'])) {
            //Create Card Logic
            $card_name = trim($_POST['name']);
            $card_number = trim($_POST['number']);
            $card_image = trim($_POST['image']);
            $set_id = $_POST['set_id'];
            $game_id = $_POST['game_id'];

            // Validate input
            if (!empty($card_name) && !empty($set_id) && !empty($card_number)) {
                // Check if the card number already exists within the set
                $checkCardNumberSql = "SELECT id FROM card WHERE CardNumber = ? AND GameSetID = ?";
                $stmtCheck = $conn->prepare($checkCardNumberSql);
                $stmtCheck->bind_param("si", $card_number, $set_id);
                $stmtCheck->execute();
                $stmtCheck->store_result();

                if ($stmtCheck->num_rows > 0) {
                    // If a card with the same number exists in the set
                    $_SESSION['toast'] = ['type' => 'error', 'message' => 'Card number already exists in this set.'];
                    header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
                    exit();
                } else {
                    // Proceed to insert the new card
                    $sql = "INSERT INTO card (Name, CardNumber, Image, GameSetID) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $card_name, $card_number, $card_image, $set_id);

                    if ($stmt->execute()) {
                        $_SESSION['toast'] = ['type' => 'success', 'message' => 'Card created successfully.'];
                    } else {
                        $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error creating card.'];
                    }

                    // Redirect back to the card management page
                    header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
                    exit();
                }
            } else {
                $_SESSION['toast'] = ['type' => 'error', 'message' => 'Set ID, card number, and card name are required.'];
                header('Location: card.php?set_id=' . $set_id . '&game_id=' . $game_id);
                exit();
            }
        }

        if (isset($_POST['update_card'])) {
            //Validate input
            if (!empty($card_name) && !empty($card_id)) {
                $sql = "UPDATE card SET Name = ?, CardNumber = ?, Image = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $card_name, $card_number, $card_image, $card_id);

                if ($stmt->execute()) {
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Card updated successfully.'];
                } else {
                    $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error updating card.'];
                }

                //Redirect back to the card management page
                header('Location: card.php?set_id=' . $set_id . '?game_id=' . $game_id);
                exit();
            } else {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Card ID and Card Name are required.'];
                header('Location: card.php?set_id=' . $set_id . '?game_id=' . $game_id);
                exit();
            }
        }
    }

    //Handle Card Deletion
    if (isset($_GET['card_id'])) {
        $card_id = $_GET['card_id'];
        $set_id = $_GET['set_id'];

        //Validate card_id
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
            header('Location: card.php?set_id=' . $set_id . '?game_id=' . $game_id);
            exit();
        }
    }
?>