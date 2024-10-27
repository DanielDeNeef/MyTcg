<?php
    include '../includes/controlLogin.php'; 
    include_once('../includes/header.php');
    include '../includes/message.php'; 
    include '../includes/collectionService.php';
?>

<link rel="stylesheet" href="../styles/collection.css">

<?php include '../includes/navigation.php' ?>

<!-- Display toast message if available -->
<?php 
    if (isset($_SESSION['toast'])) {
        renderToast($_SESSION['toast']['type'], $_SESSION['toast']['message']);
        unset($_SESSION['toast']); 
    }
?>

<div id="content">
    <!-- display the add collection logic -->
    <?php include '../includes/collectionAdd.php'; ?>
    <!-- display the user cards -->
    <?php include '../includes/collectionCards.php'; ?>
</div>

<?php include_once('../includes/footer.php'); ?>