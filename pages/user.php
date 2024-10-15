<?php
    include '../includes/dbconnect.php';
    include '../includes/controlLogin.php';
    include '../includes/message.php';

    // Handle Update Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $type = $_POST['type'];
        $active = $_POST['active'];

        $stmt = $conn->prepare("UPDATE User SET Username = ?, Type = ?, Active = ? WHERE Id = ?");
        $stmt->bind_param("ssii", $username, $type, $active, $id);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'User updated successfully'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error updating user'];
        }

        exit;
    }

    // Handle Delete Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM User WHERE Id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'User deleted successfully'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error deleting user'];
        }

        exit;
    }

    // Handle Create Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createUser'])) {
        $username = $_POST['username'];
        $type = $_POST['type'];
        $active = $_POST['active'];

        // Prepared statement to insert a new user
        $stmt = $conn->prepare("INSERT INTO User (Username, Type, Active) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $username, $type, $active);

        if ($stmt->execute()) {
            $_SESSION['toast'] = ['type' => 'success', 'message' => 'User created successfully'];
        } else {
            $_SESSION['toast'] = ['type' => 'error', 'message' => 'Error creating user'];
        }
    }

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

<!-- Update User Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="updateUserForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-control" name="type" required>
                            <option value="Admin">Admin</option>
                            <option value="User">Standard</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <select class="form-control" name="active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Display toast message if available -->
<?php 
    if (isset($_SESSION['toast'])) {
        renderToast($_SESSION['toast']['type'], $_SESSION['toast']['message']);
        unset($_SESSION['toast']); 
    }
?>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createUserForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-control" name="type" required>
                            <option value="Admin">Admin</option>
                            <option value="User">Standard</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="active" class="form-label">Active</label>
                        <select class="form-control" name="active" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript to populate the modal with user data
document.querySelectorAll('[data-bs-target="#updateUserModal"]').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const username = button.getAttribute('data-username');
        const type = button.getAttribute('data-type');
        const active = button.getAttribute('data-active');

        document.querySelector('#updateUserModal input[name="id"]').value = id;
        document.querySelector('#updateUserModal input[name="username"]').value = username;
        document.querySelector('#updateUserModal select[name="type"]').value = type;
        document.querySelector('#updateUserModal select[name="active"]').value = active;
    });
});

// Handle update user form submission with AJAX
document.querySelector('#updateUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('updateUser', true);

    fetch('user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => console.error('Error:', error));
});

// Handle create user form submission
document.querySelector('#createUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('createUser', true);

    fetch('user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => console.error('Error:', error));
});

// Function to delete user
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        const formData = new FormData();
        formData.append('id', userId);
        formData.append('deleteUser', true);

        fetch('user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
    }
}
</script>

<?php include_once('../includes/footer.php'); ?>