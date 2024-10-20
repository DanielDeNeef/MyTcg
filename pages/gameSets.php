<?php
    include '../includes/dbconnect.php';
    include '../includes/controlLogin.php';
    include '../includes/message.php';
    include_once('../includes/header.php');
?>

<?php include '../includes/navigation.php' ?>

<!-- include the games Set Table -->
<div id="content">
    <?php include '../includes/gameSetTable.php' ?>
</div>

<!-- Include the modals for creating and updating game sets -->
<?php include '../includes/gameSetModals.php'; ?>

<!-- Include the game.js script -->
<script src="../scripts/game.js"></script>

<?php include_once('../includes/footer.php'); ?>
