<!-- Create Set Modal -->
<div class="modal fade" id="createSetModal" tabindex="-1" aria-labelledby="createSetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="gameSets.php?game_id=<?= $game_id ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSetModalLabel">Create Game Set</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="setName" class="form-label">Set Name</label>
                        <input type="text" class="form-control" id="setName" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="code" class="form-label">Set Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>

                    <div class="mb-3">
                        <label for="setLogo" class="form-label">Set Logo URL</label>
                        <input type="text" class="form-control" id="setLogo" name="logo">
                    </div>
                    <!-- Hidden input to link the set to the current game -->
                    <input type="hidden" name="game_id" value="<?= $game_id ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="create_set" class="btn btn-primary">Create Set</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Set Modal -->
<div class="modal fade" id="updateSetModal" tabindex="-1" aria-labelledby="updateSetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="gameSets.php?game_id=<?= $game_id ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateSetModalLabel">Update Game Set</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="updateSetName" class="form-label">Set Name</label>
                        <input type="text" class="form-control" id="updateSetName" name="name" required>

                    </div>

                    <div class="mb-3">
                        <label for="updateSetCode" class="form-label">Set Code</label>
                        <input type="text" class="form-control" id="updateSetCode" name="code" required>
                    </div>

                    <div class="mb-3">
                        <label for="updateSetLogo" class="form-label">Set Logo URL</label>
                        <input type="text" class="form-control" id="updateSetLogo" name="logo">
                    </div>

                    <!-- Hidden input for set id and game id -->
                    <input type="hidden" name="id" id="updateSetId">
                    <input type="hidden" name="game_id" value="<?= $game_id ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_set" class="btn btn-primary">Update Set</button>
                </div>
            </form>
        </div>
    </div>
</div>