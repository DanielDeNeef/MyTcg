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
        header('Location: gameSets.php?game_id=' . $game_id);
        exit();
    }
    
    //Handle CRUD actions for Cards
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['create_card'])) {
            //Create Card Logic
            $card_name = trim($_POST['name']);
            $card_type = trim($_POST['type']);
            $card_image = trim($_POST['image']);
            $set_id = $_POST['set_id'];

            //Validate input
            if (!empty($card_name) && !empty($set_id)) {
                $sql = "INSERT INTO card (Name, Type, Image, GameSetID) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $card_name, $card_type, $card_image, $set_id);

                if ($stmt->execute()) {
                    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Card created successfully.'];
                } else {
                    $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Error creating card.'];
                }

                //Redirect back to the card management page
                header('Location: card.php?set_id=' . $set_id . '?game_id=' . $game_id);
                exit();
            } else {
                $_SESSION['toast'] = ['type' => 'danger', 'message' => 'Set ID and Card Name are required.'];
                header('Location: card.php?set_id=' . $set_id . '?game_id=' . $game_id);
                exit();
            }
        }

        if (isset($_POST['update_card'])) {
            //Validate input
            if (!empty($card_name) && !empty($card_id)) {
                $sql = "UPDATE card SET Name = ?, Type = ?, Image = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $card_name, $card_type, $card_image, $card_id);

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
