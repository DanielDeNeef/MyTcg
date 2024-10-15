<?php
    include '../includes/dbconnect.php';
    include '../includes/controlLogin.php';
    include '../includes/message.php';

    // Handle Search
    $search = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
    $sql = "SELECT * FROM User WHERE Username LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<?php include_once('../includes/header.php'); ?>
<link rel="stylesheet" href="../styles/main.css">

<?php include '../includes/navigation.php' ?>

<div id="content">
    <div class="container mt-5">
        <h2 class="mb-4">Manage Users</h2>

        <!-- Display toast message if available -->
        <?php 
            if (isset($_SESSION['toast'])) {
                renderToast($_SESSION['toast']['type'], $_SESSION['toast']['message']);
                unset($_SESSION['toast']); 
            }
        ?>

        <!-- Create User Button -->
        <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#createUserModal">
            Create User
        </button>

        <!-- Search Bar -->
        <form class="d-flex mb-3" method="GET" action="user.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Search for users"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>

        <!-- Users Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Type</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Username']) ?></td>
                    <td><?= htmlspecialchars($row['Type']) ?></td>
                    <td><?= $row['Active'] ? 'Active' : 'Inactive' ?></td>
                    <td>
                        <!-- Update Buttons -->
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateUserModal"
                            data-id="<?= $row['Id'] ?>" data-username="<?= htmlspecialchars($row['Username']) ?>"
                            data-type="<?= htmlspecialchars($row['Type']) ?>" data-active="<?= $row['Active'] ?>">
                            Update
                        </button>

                        <!-- Delete Buttons -->
                        <button class="btn btn-danger" onclick="deleteUser(<?= $row['Id'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="4">No users found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- modals for the update and delete buttons -->
<?php include '../includes/userModals.php'; ?>
<!-- modals javascript logic -->
<script src="../scripts/user.js"></script>

<?php include_once('../includes/footer.php'); ?>