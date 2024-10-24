<?php
    include 'dbconnect.php'; 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cardId = $_POST['cardId'];
        $newAmount = $_POST['newAmount'];
        $userId = $_POST['currentUserId'];

        if (!empty($cardId) && !empty($newAmount) && !empty($userId)) {
            
            $query = "UPDATE Collection SET Amount = ? WHERE CardId = ? AND UserId = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iii', $newAmount, $cardId, $userId);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error";
            }

            $stmt->close();
        }
    }
?>

