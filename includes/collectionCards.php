<?php
   
    $user_id = $_SESSION['id'];

    $query = "SELECT c.id, c.Amount, cr.Name as CardName, gs.Name as SetName, g.Name as GameName , c.cardId as cardID, cr.image as image
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

</script src="../scripts/collection.js" ></script>

<div class="container my-5">
    <h3>Your Collection </h3>

    <div class="row">
        <?php if ($result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            
        <div class="col-md-3" id="card-<?= $row['cardID'] ?>">
            <div class="card mb-4">

                <!-- Card Title as Modal Trigger -->
                <div class="card-body">
                    <h5 class="card-title" style="cursor: pointer;" onclick="openUpdateAmountModal(
                    <?= $row['cardID'] ?>, 
                    '<?= htmlspecialchars($row['CardName']) ?>', 
                    <?= htmlspecialchars($row['Amount']) ?>,
                    <?= $_SESSION['id'] ?>
                    )">
                        <?= htmlspecialchars($row['CardName']) ?>
                    </h5>
                </div>

                <!-- Card Image and Amount -->
                <?php
                    $imagePath = $row['image']; 
                    if ($imagePath != null) {
                ?>

                <img src="<?= $imagePath ?>" class="card-img-top" alt="<?= htmlspecialchars($row['CardName']) ?>">
                <?php 
                    } else { ?>
                        <div class="card-img-top d-flex justify-content-center align-items-center"
                            style="height: 200px; background-color: #e9ecef;">
                            <span>geen foto</span>
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
                <p>er zijn geen kaarten in uw verzameling</p>
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


