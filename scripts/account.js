// Enable form fields for editing
function enableEditing() {
    document.getElementById('username').disabled = false;
    document.getElementById('password').disabled = false;
    document.getElementById('confirm_password').disabled = false;
    document.getElementById('editBtn').style.display = 'none';
    document.getElementById('saveBtn').style.display = 'block';
    document.getElementById('cancelBtn').style.display = 'block';
}

// Cancel editing and reset form fields
function cancelEditing() {
    document.getElementById('username').disabled = true;
    document.getElementById('password').disabled = true;
    document.getElementById('confirm_password').disabled = true;
    document.getElementById('editBtn').style.display = 'block';
    document.getElementById('saveBtn').style.display = 'none';
    document.getElementById('cancelBtn').style.display = 'none';
    document.getElementById('password').value = "";
    document.getElementById('confirm_password').value = "";
}