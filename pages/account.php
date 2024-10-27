<?php
    include '../includes/dbconnect.php'; // Database connection
    include '../includes/controlLogin.php'; // Control login session
    include '../includes/message.php'; // Message handling (toast messages)
    include '../includes/accountService.php'; // Your account management functions
?>

<?php include_once('../includes/header.php'); ?>
<?php include '../includes/navigation.php' ?>

<div id="content">
    <div class="container mt-5">
        <h2 class="text-center">Account Settings</h2>
        <div class="card-body">

            <!-- Success Message -->
            <?php if (!empty($update_success)) {
                renderToast('success', $update_success);
            } ?>

            <!-- Error Messages -->
            <?php 
                if (!empty($username_err)) {
                    renderToast('error', $username_err);
                }
                if (!empty($password_err)) {
                    renderToast('error', $password_err);
                }
                if (!empty($confirm_password_err)) {
                    renderToast('error', $confirm_password_err);
                }
            ?>

            <!-- Form that will allow the user to change username or password -->
            <form method="post" action="account.php">
                <div class="mb-3">
                    <label for="username">Username</label>
                    <input class="form-control" id="username" type="email" name="username"
                        value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password">Password</label>
                    <input class="form-control" id="password" type="password" name="password" placeholder="********" disabled>
                </div>

                <div class="mb-3">
                    <label for="confirm_password">Confirm Password</label>
                    <input class="form-control" id="confirm_password" type="password" name="confirm_password" disabled>
                </div>

                <!-- Edit, Save, and Cancel Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" id="editBtn" onclick="enableEditing()">Edit</button>
                    <button type="submit" class="btn btn-success" id="saveBtn" style="display:none;">Save</button>
                    <button type="button" class="btn btn-secondary" id="cancelBtn" style="display:none;" onclick="cancelEditing()">Cancel</button>
                </div>
            </form>

        </div>
    </div>
</div>

<script src="../scripts/account.js"></script>

<?php include '../includes/footer.php'; ?>
