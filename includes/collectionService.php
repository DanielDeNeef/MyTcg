<?php
include 'dbconnect.php';
//get games
$gamesQuery = "SELECT id, Name FROM Game";
$gamesResult = $conn->query($gamesQuery);

//Handle form submission for adding a card to collection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id']; 
    $cardId = $_POST['card'];
    $amount = $_POST['amount'];

    //Check if the card already exists in the user's collection
    $checkCollection = "SELECT * FROM Collection WHERE userId = $userId AND CardId = $cardId";
    $collectionResult = $conn->query($checkCollection);

    if ($collectionResult->num_rows > 0) {
        //Update the amount if the card is already in the collection
        $updateCollection = "UPDATE Collection SET Amount = Amount + $amount WHERE UserId = $userId AND CardId = $cardId";
        $conn->query($updateCollection);
    } else {
        //Insert the card if it's not already in the collection
        $insertCollection = "INSERT INTO Collection (UserId, CardId, Amount) VALUES ($userId, $cardId, $amount)";
        $conn->query($insertCollection);
    }

    $_SESSION['toast'] = ['type' => 'success', 'message' => 'Card added to the collection'];
}

if (isset($_GET['game'])) {
    $gameId = $_GET['game'];
    
    //get game sets for the selected game
    $gameSetsQuery = "SELECT id, Name FROM cardSet WHERE GameId = $gameId";
    $gameSetsResult = $conn->query($gameSetsQuery);

    //Begin building the options for the game set dropdown
    $options = '<option value="" disabled selected>--Select Game Set--</option>';

    //Generate valid <option> elements
    while ($row = $gameSetsResult->fetch_assoc()) {
        $options .= '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES) . '">' . htmlspecialchars($row['Name'],ENT_QUOTES) . '</option>';
    }

    echo $options;
}

if (isset($_GET['gameSet'])) {
    $gameSetId = $_GET['gameSet'];
    
    //get cards for the selected game set
    $cardsQuery = "SELECT id, Name FROM Card WHERE GameSetId = $gameSetId";
    $cardsResult = $conn->query($cardsQuery);

    echo '<option value="">--Select Card--</option>';
    while ($row = $cardsResult->fetch_assoc()) {
        echo '<option value="' . $row['id'] . '">' . $row['Name'] . '</option>';
    }
}
?>