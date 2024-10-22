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
?>
