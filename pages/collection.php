<?php
    include '../includes/controlLogin.php'; 
    include '../includes/message.php'; 
?>

<?php include_once('../includes/header.php'); ?>
<?php include '../includes/navigation.php' ?>

<link rel="stylesheet" href="../styles/main.css">

<!-- Display toast message if available -->
<?php 
    if (isset($_SESSION['toast'])) {
        renderToast($_SESSION['toast']['type'], $_SESSION['toast']['message']);
        unset($_SESSION['toast']); 
    }
?>

<div id="content">
    <?php include '../includes/collectionAdd.php'; ?>
</div>

<?php include_once('../includes/footer.php'); ?>
