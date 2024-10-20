<?php
    //Include necessary files and initialize DB connection
    include '../includes/dbconnect.php';
    include '../includes/controlLogin.php';
    include '../includes/message.php';
    include '../includes/cardService.php';    
?>

<?php include_once('../includes/header.php'); ?>
<?php include '../includes/navigation.php' ?>
<div id="content">
    <div class="container mt-5">
        <h2 class="mb-4"><?= htmlspecialchars($set['Name']) ?>: Cards Management</h2>

        <!-- Button to Create Card -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createCardModal">
            Create Card
        </button>

        <!-- Cards Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cardsResult->num_rows > 0): ?>
                <?php while ($row = $cardsResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Name']) ?></td>
                    <td><?= htmlspecialchars($row['Type']) ?></td>
                    <td><img src="<?= htmlspecialchars($row['Image']) ?>" alt="Image" width="50"></td>
                    <td>
                        <!-- Update Button -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateCardModal"
                            data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['Name']) ?>"
                            data-type="<?= htmlspecialchars($row['Type']) ?>"
                            data-image="<?= htmlspecialchars($row['Image']) ?>">
                            Update
                        </button>

                        <!-- Delete Button -->
                        <button class="btn btn-danger" onclick="deleteCard(<?= $row['id'] ?>, <?= $set_id ?>)">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4">No cards found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Include Modals for Creating and Updating Cards -->
<?php include '../includes/cardModals.php'; ?>

<!-- Include the card.js script -->
<script src="../scripts/card.js"></script>

<?php include_once('../includes/footer.php'); ?>
