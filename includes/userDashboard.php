<?php
    $user_id = $_SESSION['id'];
    $gameSets = getUserGameSets($conn, $user_id);

    $config = require dirname(__DIR__) . '/config/app.php'; 
    $baseUrl = $config['url']["baseUrl"];
?>

<!-- import chart js lib -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Stylesheet for display card -->
<link rel="stylesheet" href="<?= $baseUrl ?>styles/userDashboard.css" />

<div class="container">
    <h2>Your Collection Progress by Game Set</h2>

    <div class="row">
        <?php foreach ($gameSets as $set): ?>
        <div class="col-md-4">
            <div class="card">

                <?php if($set['imagePath'] != null){ ?>
                <!-- Set Image -->
                <img src="<?= $set['imagePath'] ?>" alt="<?= htmlspecialchars($set['SetName']) ?>" class="set-image">
                <?php }else { ?>
                <h5 class="card-title"><?= htmlspecialchars($set['SetName']) ?></h5>
                <?php } ?>

                <div class="chart-container">
                    <!-- Center text inside chart-->
                    <div id="chart-text-<?= $set['SetCode'] ?>" class="chart-center-text">
                        <?= $set['userCollectedCards'] ?>/<?= $set['totalCards'] ?>
                    </div>
                    <!--doughnut chart -->
                    <canvas id="chart-<?= $set['SetCode'] ?>"></canvas>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script> 
    const gameSets = <?= json_encode($gameSets); ?>;
</script>
<script src= "scripts/userDashboard.js"></script>

