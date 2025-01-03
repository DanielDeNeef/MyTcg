<?php 
    $config = require dirname(__DIR__) . '/config/app.php'; 
    $baseUrl = $config['url']["baseUrl"];
?>

<!-- Stylesheet for navigation -->
<link rel="stylesheet" href="<?php echo $baseUrl .'styles/navigation.css' ?>" />

<!-- Mobile Navigation -->
<div id="mobile-nav" class="d-md-none">
    <h2>User Dashboard</h2>
    <button id="menu-toggle" class="btn btn-primary">Menu</button>
</div>

<!-- Sidebar Navigation -->
<nav id="sidebar">

    <h2 class="text-center">My TCG</h2>
    <?php if ($isAdmin) { ?>

    <!-- Admin links -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="<?=$baseUrl ?>">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?=$baseUrl ?>pages/collectionMng.php">Collection</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?=$baseUrl ?>pages/user.php">User Management</a>
        </li>
        <?php } else { ?>

        <!-- User links -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="<?=$baseUrl ?>">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=$baseUrl ?>pages/collection.php">Collection</a>
            </li>
        <?php } ?>
            <li class="nav-item">
                <a class="nav-link" href="<?=$baseUrl ?>pages/account.php ">Account Management</a>
            </li>
    </ul>
    <a href="<?= $baseUrl ?>/pages/logout.php">Logout</a>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Event listener for menu toggle
        document.getElementById("menu-toggle").addEventListener("click", function() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("active");
        });
    });
</script>