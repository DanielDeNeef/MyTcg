<?php
    include 'dbconnect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cardId = $_POST['cardId'];
        $userId = $_POST['currentUserId'];

        if (!empty($cardId) && !empty($userId)) {
            $query = "DELETE FROM Collection WHERE CardId = ? AND UserId = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $cardId, $userId);

            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "error";
            }

            $stmt->close();
        }
    }
?>
