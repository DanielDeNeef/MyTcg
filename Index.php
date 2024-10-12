<?php 
    session_start();
    include 'includes/controlLogin.php';
    include 'includes/session_timeout.php';
    include 'includes/header.php';
?>

<link rel="stylesheet" href="styles/main.css">

<?php include 'includes/navigation.php' ?>

<div id="content" class="container-fluid px-4">

    <!-- Dashboard Section -->
    <section id="dashboard">
      <h1>Dashboard</h1>
      <p>Here is a summary of your card collection by game and set.</p>

      <!-- Magic: The Gathering Section -->
      <div class="game-section">
        <h2>Magic: The Gathering</h2>
        <div class="card-set">
          <div class="set-card"> ... </div>
        </div>
      </div>
    </section>

    <!-- Include the footer -->
    <?php
    include 'includes/footer.php';
    ?>
  </div>



