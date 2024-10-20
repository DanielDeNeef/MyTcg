<!-- Create Card Modal -->
<div class="modal fade" id="createCardModal" tabindex="-1" aria-labelledby="createCardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="card.php?set_id=<?= $set_id ?>&game_id= <?= $game_id ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCardModalLabel">Create New Card</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Card Name -->
                    <div class="mb-3">
                        <label for="cardName" class="form-label">Card Name</label>
                        <input type="text" class="form-control" id="cardName" name="name" required>
                    </div>

                    <!-- Card Type -->
                    <div class="mb-3">
                        <label for="cardType" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="cardType" name="number" required>
                    </div>

                    <!-- Card Image URL -->
                    <div class="mb-3">
                        <label for="cardImage" class="form-label">Card Image URL</label>
                        <input type="text" class="form-control" id="cardImage" name="image">
                    </div>

                    <!-- Hidden input for GameSetID -->
                    <input type="hidden" name="set_id" value="<?= $set_id ?>">
                    <input type="hidden" name="game_id" value="<?= $game_id ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="create_card" class="btn btn-primary">Create Card</button>
                </div>
            </form>
        </div>
    </div>
</div>
