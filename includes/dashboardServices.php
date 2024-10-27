<?php

    function getTotalGames() {
        global $conn;
        $sql = "SELECT COUNT(*) as total FROM Game";
        $result = $conn->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        } else {
            return 0;
        }
    }

    function getTotalGameSets() {
        global $conn;
        $sql = "SELECT COUNT(*) as total FROM cardset";
        $result = $conn->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        } else {
            return 0;
        }
    }

    function getTotalCards() {
        global $conn;
        $sql = "SELECT COUNT(*) as total FROM Card";
        $result = $conn->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        } else {
            return 0;
        }
    }

    function getTotalUsers() {
        global $conn;
        $sql = "SELECT COUNT(*) as total FROM User";
        $result = $conn->query($sql);
        
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total'];
        } else {
            return 0;
        }
    }

    function getUserGameSets($conn, $user_id) {
        $query = "
            SELECT 
                gs.Name as SetName,
                gs.Code as SetCode,
                gs.Logo as imagePath,
                COUNT(DISTINCT c.id) as totalCards,
                COUNT(DISTINCT uc.CardId) as userCollectedCards
            FROM cardSet gs
            LEFT JOIN Card c ON c.gameSetId = gs.id
            LEFT JOIN Collection uc ON uc.CardId = c.id AND uc.UserId = ?
            GROUP BY gs.id
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $gameSets = [];
        while ($row = $result->fetch_assoc()) {
            $gameSets[] = [
                'SetName' => $row['SetName'],
                'SetCode' => $row['SetCode'],
                'totalCards' => $row['totalCards'],
                'imagePath' => $row['imagePath'],
                'userCollectedCards' => $row['userCollectedCards'],
            ];
        }
        $stmt->close();

        return $gameSets;
    }

?>
