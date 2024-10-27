<?php include '../includes/gameSetService.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4"><?= htmlspecialchars($game['Name']) ?>: Game Sets</h2>

    <!-- Display toast message if available -->
    <?php 
        if (isset($_SESSION['toast'])) {
            renderToast($_SESSION['toast']['type'], $_SESSION['toast']['message']);
            unset($_SESSION['toast']); 
        }
    ?>

    <!-- Create Set Button -->
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createSetModal">
        Create Game Set
    </button>

    <!-- Search form -->
    <form method="GET" class="d-flex mb-3">
        <input type="hidden" name="game_id" value="<?= $game_id ?>">
        <input type="text" name="set_search" class="form-control" placeholder="Search game sets"
            value="<?= isset($_GET['set_search']) ? htmlspecialchars($_GET['set_search']) : '' ?>">
        <button type="submit" class="btn btn-outline-success">Search</button>
    </form>

    <!-- Game Sets Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Logo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($setsResult->num_rows > 0): ?>
            <?php while ($row = $setsResult->fetch_assoc()): ?>
            <tr>
                <td> 
                    <a href="card.php?set_id=<?= $row['id'] ?>&game_id=<?= $game_id ?>">
                        <?= htmlspecialchars($row['Name']) ?>
                    </a>
                </td>

                <td><img src="<?= htmlspecialchars($row['Logo']) ?>" alt="Logo" width="50"></td>
                <td>
                    <!-- Update Buttons -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateSetModal"
                        data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['Name']) ?>"
                        data-logo="<?= htmlspecialchars($row['Logo']) ?>" 
                        data-code="<?= htmlspecialchars($row['Code']) ?>"
                        >
                        Update
                    </button>

                    <!-- Delete Buttons -->
                    <button class="btn btn-danger"
                        onclick="deleteSet(<?= $row['id'] ?>, <?= $game_id ?>)">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="3">No game sets found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>