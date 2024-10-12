<?php
    require_once '../includes/dbconnect.php';
    include_once("../includes/header.php");
?>

<style>
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #f8f9fa;
}

.login-container {
    width: 100%;
    max-width: 400px;
    padding: 2rem;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.register-link {
    text-align: center;
    margin-top: 1rem;
}
</style>


<div class="login-container">
    <h2 class="text-center">Login</h2>
    <form action="dashboard.html" method="post">

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- link to Registration page -->
    <div class="register-link">
        <p>Don't have an account? <a href="registration.php">Register here</a></p>
    </div>
</div>