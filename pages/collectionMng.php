<?php
include '../includes/dbconnect.php'; 
include '../includes/controlLogin.php'; 
include '../includes/message.php'; 
?>

<?php include_once('../includes/header.php'); ?>
<link rel="stylesheet" href="../styles/main.css">

<?php include '../includes/navigation.php' ?>

<div id="content">
    <!-- Games -->
     <?php include '../includes/gameTable.php' ?>
</div>

<?php include_once('../includes/footer.php'); ?>
