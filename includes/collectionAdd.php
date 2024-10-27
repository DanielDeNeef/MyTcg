<?php include '../includes/collectionService.php'; ?> 

<div class="container my-5">
    <h2 class="mb-4">My Card Collection</h2>

    <!-- Button to toggle form visibility -->
    <button class="btn btn-primary mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#addCardForm"
        aria-expanded="false" aria-controls="addCardForm">
        Add a Card to Your Collection
    </button>

    <!-- Collapsible Form -->
    <div class="collapse" id="addCardForm">
        <div class="card card-body">
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="game" class="form-label">Select Game:</label>
                    <select class="form-select" name="game" id="game" onchange="fetchGameSets(this.value)" required>
                        <option value="" disabled selected>--Select Game--</option>
                        <?php while ($row = $gamesResult->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['Name'] ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">Please select a game.</div>
                </div>

                <div class="mb-3">
                    <label for="gameSet" class="form-label">Select Game Set:</label>
                    <select class="form-select" name="gameSet" id="gameSet" onchange="fetchCards(this.value)" required>
                        <option value="" disabled selected>--Select Game Set--</option>
                    </select>
                    <div class="invalid-feedback">Please select a game set.</div>
                </div>

                <div class="mb-3">
                    <label for="card" class="form-label">Select Card:</label>
                    <select class="form-select" name="card" id="card" required>
                        <option value="" disabled selected>--Select Card--</option>
                    </select>
                    <div class="invalid-feedback">Please select a card.</div>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount:</label>
                    <input type="number" class="form-control" name="amount" id="amount" value="1" required>
                    <div class="invalid-feedback">Please enter a valid amount.</div>
                </div>

                <button type="submit" class="btn btn-primary">Add to Collection</button>
            </form>

            <?= include '../includes/collectionImportForm.php'; ?>
        </div>
    </div>
</div>

<script src="../scripts/collection.js"></script>