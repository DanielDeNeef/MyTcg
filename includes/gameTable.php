<?php include '../includes/gameService.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4">Manage Games, Sets, and Cards</h2>

    <!-- Display toast message if available -->
    <?php 
            if (isset($_SESSION['toast'])) {
                renderToast($_SESSION['toast']['type'], $_SESSION['toast']['message']);
                unset($_SESSION['toast']); 
            }
        ?>

    <!-- Create Game Button -->
    <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createGameModal">
        Create Game
    </button>

    <!-- Search Bar -->
    <form class="d-flex mb-3" method="GET" action="collectionMng.php">
        <input class="form-control me-2" type="search" name="search" placeholder="Search for games"
            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>

    <!-- Games Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Logo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($gamesResult->num_rows > 0): ?>
            <?php while ($row = $gamesResult->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['Name']) ?></td>
                <td><img src="<?= htmlspecialchars($row['Logo']) ?>" alt="Logo" width="50"></td>
                <td>
                    <!-- Update Buttons -->
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateGameModal"
                        data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['Name']) ?>"
                        data-logo="<?= htmlspecialchars($row['Logo']) ?>">
                        Update
                    </button>

                    <!-- Delete Buttons -->
                    <button class="btn btn-danger" onclick="deleteGame(<?= $row['id'] ?>)">Delete</button>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php else: ?>
            <tr>
                <td colspan="3">No games found</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Game Modals -->
<?php include 'gameModals.php'; ?>
<!-- modals javascript logic -->
<script src="../scripts/game.js"></script>