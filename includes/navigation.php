<?php 
    $config = require dirname(__DIR__) . '/config/app.php'; 
    $baseUrl = $config['url']["baseUrl"];
?>

<link rel="stylesheet" href=<?php echo $baseUrl .'styles/navigation.css' ?> />
<nav id="sidebar">
    <!-- admin links -->
    <h2 class="text-center">Admin Dashboard</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href=<?php echo $baseUrl ?> >Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href=<?php echo $baseUrl .'pages/collectionMng.php' ?>>Collection</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href=<?php echo $baseUrl .'pages/user.php' ?>>user Management</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href=<?php echo $baseUrl .'pages/account.php' ?>>Account Management</a>
        </li>
    </ul>

    <!-- user links -->
    <h2 class="text-center">User Dashboard</h2>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href=<?php echo $baseUrl ?>>Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href=<?php echo $baseUrl .'pages/collection.php' ?>>Collection</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href=<?php echo $baseUrl .'pages/account.php' ?>>Account Management</a>
        </li>
    </ul>
    <button class="btn logout-btn mt-4" onclick="logout()">Logout</button>
</nav>