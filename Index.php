<?php 
    include 'includes/dbconnect.php';
    include 'includes/controlLogin.php';
    include 'includes/header.php';
    include 'includes/dashboardServices.php';
?>

<link rel="stylesheet" href="styles/main.css">

<?php include 'includes/navigation.php'; ?>

<div id="content">
<?php 

    // display the admin dashboard else display the user dashboard
    if ($isAdmin) {
        include 'includes/adminDashboard.php';
    }
    
?>
</div>