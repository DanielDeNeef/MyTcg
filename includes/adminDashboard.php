<?php 

    $gameCount = getTotalGames();
    $gameSetCount = getTotalGameSets();
    $cardCount = getTotalCards();
    $userCount = getTotalUsers();

?>

<div class="container mt-5">
        <!-- Dashboard Section -->
        <section id="dashboard">
            <h1>Dashboard</h1>
            <p>Here is an overview of the system data.</p>

            <div class="row g-2">
                <!-- Game Count Card -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Games</h5>
                            <p class="card-text display-4"><?= $gameCount ?></p>
                        </div>
                    </div>
                </div>

                <!-- Game Set Count Card -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Game Sets</h5>
                            <p class="card-text display-4"><?= $gameSetCount ?></p>
                        </div>
                    </div>
                </div>

                <!-- Card Count Card -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Cards</h5>
                            <p class="card-text display-4"><?= $cardCount ?></p>
                        </div>
                    </div>
                </div>

                <!-- User Count Card -->
                <div class="col-12 col-sm-6 col-lg-6">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text display-4"><?= $userCount ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>