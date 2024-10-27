<?php

    include '../includes/dbconnect.php';

    if (isset($_GET['set_id']) && isset($_GET['set_code'])) {
        $set_id = intval($_GET['set_id']);
        $set_code = $_GET['set_code'];

        $allCards = [];
        $page = 1;
        $cardsPerPage = 100; 

        do {
            // Get cards from the MTG API with pagination
            $apiUrl = "https://api.magicthegathering.io/v1/cards?set=" . urlencode($set_code) . "&page=" . $page;
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);

            // Check for API response and if there are cards returned
            if (empty($data['cards'])) {
                break; 
            }

            $allCards = array_merge($allCards, $data['cards']);
            $page++;
        } while (count($data['cards']) === $cardsPerPage); 

        // Check if we retrieved any cards
        if (empty($allCards)) {
            echo json_encode(['success' => false, 'message' => 'No cards found in this set on MTG API.']);
            exit;
        }

        // Prepare SQL statement for checking existing cards
        $checkCardSql = "SELECT id FROM card WHERE CardNumber = ? AND GameSetID = ?";
        $checkStmt = $conn->prepare($checkCardSql);
        
        // Prepare SQL statement for inserting/updating cards
        $insertUpdateSql = "INSERT INTO card (Name, CardNumber, Image, GameSetID) VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE Name = VALUES(Name), Image = VALUES(Image)";
        $insertUpdateStmt = $conn->prepare($insertUpdateSql);

        foreach ($allCards as $card) {
            $card_name = $card['name'];
            $card_number = $card['number'] ?? '';
            $card_image = $card['imageUrl'] ?? '';

            // check if the card already exists
            $checkStmt->bind_param("si", $card_number, $set_id);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                //Do nothing 
            } else {
                // Card does not exist, prepare to insert it
                $insertUpdateStmt->bind_param("sssi", $card_name, $card_number, $card_image, $set_id);
                $insertUpdateStmt->execute();
            }
        }

        // Clean up prepared statements and connections
        $checkStmt->close();
        $insertUpdateStmt->close();
        $conn->close();

    } 

?>
