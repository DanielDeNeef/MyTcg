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
            location.reload();
        })
        .catch(error => console.error('Error:', error));
});

// Function to delete user
function deleteUser(userId) {
    if (confirm('will je deze gebruiker verwijderen ?')) {
        const formData = new FormData();
        formData.append('id', userId);
        formData.append('deleteUser', true);

        fetch('user.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                location.reload();
            })
            .catch(error => console.error('Error:', error));
    }
}