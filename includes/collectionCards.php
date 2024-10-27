<?php  //TODO move to sepparated services

    // I needed to sepparate the logic into two files else I would have some mix calls
    include 'updateCollection.php';
    include 'deleteCollection.php';
    
    $user_id = $_SESSION['id'];

    $query = "SELECT c.id, c.Amount, cr.Name as CardName, gs.Name as SetName, g.Name as GameName , c.cardId as cardID
            FROM Collection c
            JOIN Card cr ON c.CardId = cr.id
            JOIN cardSet gs ON cr.gameSetId = gs.id
            JOIN Game g ON gs.GameId = g.id
            WHERE c.UserId = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id); 
    $stmt->execute();
    $result = $stmt->get_result();

?>

<div class="container my-5">
    <h3>Your Collection </h3>

    <div class="row">
        <?php if ($result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            
        <div class="col-md-4" id="card-<?= $row['cardID'] ?>">
            <div class="card mb-4">

                <!-- Card Title as Modal Trigger -->
                <div class="card-body">
                    <h5 class="card-title" style="cursor: pointer;" onclick="openUpdateAmountModal(
                    <?= $row['cardID'] ?>, 
                    '<?= htmlspecialchars($row['CardName']) ?>', 
                    <?= htmlspecialchars($row['Amount']) ?>
                    )">
                        <?= htmlspecialchars($row['CardName']) ?>
                    </h5>
                </div>

                <!-- Card Image and Amount -->
                <?php
                    $imagePath = 'path_to_images/' . htmlspecialchars($row['CardName']) . '.jpg'; 
                    if (file_exists($imagePath)) {
                ?>

                <img src="<?= $imagePath ?>" class="card-img-top" alt="<?= htmlspecialchars($row['CardName']) ?>">
                <?php 
                    } else { ?>
                        <div class="card-img-top d-flex justify-content-center align-items-center"
                            style="height: 200px; background-color: #e9ecef;">
                            <span>No Image Available</span>
                        </div>
                <?php } ?>

                <!-- Display Amount -->
                <div class="card-body">
                    <p class="card-text">
                        <strong>Amount:</strong> 
                        <span id="card-amount-<?= $row['cardID'] ?>">
                            <?= htmlspecialchars($row['Amount']) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <?php } ?>
        <?php 
            } else { ?>
                <p>You currently have no cards in your collection.</p>
        <?php } ?>
    </div>
</div>

<!-- Modal to change amount or delete collection -->
<div class="modal fade" id="updateAmountModal" tabindex="-1" aria-labelledby="updateAmountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAmountModalLabel">Update Card Amount</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 id="modal-card-title"></h6>
                <div class="d-flex justify-content-between align-items-center">
                    <button class="btn btn-danger" id="modal-decrease-btn">-</button>
                    <span class="mx-3" id="modal-amount-display"><strong>1</strong></span>
                    <button class="btn btn-success" id="modal-increase-btn">+</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-amount-btn">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- JS script needs to stay on the page as I need to use some PHP functions -->
<script>
    let currentCardId = null;

function openUpdateAmountModal(cardId, cardName, currentAmount) {
    
    currentCardId = cardId;

    // Set the title and amount in the modal
    document.getElementById('modal-card-title').innerText = cardName;
    document.getElementById('modal-amount-display').innerText = currentAmount;

    // Show the modal
    var updateModal = new bootstrap.Modal(document.getElementById('updateAmountModal'), {});
    updateModal.show();

    // Add event listeners for increasing/decreasing the amount
    var currentAmountElement = document.getElementById('modal-amount-display');
    var amount = parseInt(currentAmount);

    document.getElementById('modal-increase-btn').onclick = function () {
        amount++;
        currentAmountElement.innerText = amount;
    };

    document.getElementById('modal-decrease-btn').onclick = function () {
        amount--;
        if (amount <= 0) {
            // Call the delete function if amount is 0 or less
            deleteCard(currentCardId);
        } else {
            currentAmountElement.innerText = amount;
        }
    };

    // Handle saving the changes
    document.getElementById('save-amount-btn').onclick = function () {
        saveAmountChanges(amount);
    };
}

function saveAmountChanges(newAmount) {
    if (currentCardId === null) return;

    // Send the updated amount via AJAX to update in the backend
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/updateCollection.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Update the card's amount on the main page
            document.getElementById('card-amount-' + currentCardId).innerText = newAmount;

            // Close the modal after saving
            var updateModal = bootstrap.Modal.getInstance(document.getElementById('updateAmountModal'));
            updateModal.hide();
        }
    };

    xhr.send("cardId=" + currentCardId + "&newAmount=" + newAmount + "&currentUserId=" + <?= $_SESSION['id'] ?>);
}

function deleteCard(cardId) {
    if (cardId === null) return;

    // Send a delete request via AJAX to the backend
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../includes/deleteCollection.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Remove the card from the frontend after deletion
            document.getElementById('card-' + cardId).remove();
            var updateModal = bootstrap.Modal.getInstance(document.getElementById('updateAmountModal'));
            updateModal.hide();
        }
    };

    xhr.send("cardId=" + cardId + '&currentUserId=' + <?= $_SESSION['id'] ?>);
}
</script>