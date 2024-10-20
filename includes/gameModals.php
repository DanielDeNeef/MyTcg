<!-- Create Game Modal -->
<div class="modal fade" id="createGameModal" tabindex="-1" aria-labelledby="createGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="collectionMng.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGameModalLabel">Create Game</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="game_name" class="form-label">Game Name</label>
                        <input type="text" class="form-control" id="game_name" name="game_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="game_logo" class="form-label">Game Logo URL</label>
                        <input type="text" class="form-control" id="game_logo" name="game_logo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="create_game" class="btn btn-primary">Create Game</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Game Modal -->
<div class="modal fade" id="updateGameModal" tabindex="-1" aria-labelledby="updateGameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="collectionMng.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateGameModalLabel">Update Game</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="game_id" name="game_id">
                    <div class="mb-3">
                        <label for="update_game_name" class="form-label">Game Name</label>
                        <input type="text" class="form-control" id="update_game_name" name="update_game_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="update_game_logo" class="form-label">Game Logo URL</label>
                        <input type="text" class="form-control" id="update_game_logo" name="update_game_logo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_game" class="btn btn-primary">Update Game</button>
                </div>
            </form>
        </div>
    </div>
</div>
