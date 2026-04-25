<?php
require_once 'auth_check.php';
require_once 'db.php';
checkRole('Admin');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $un = trim($_POST['username']);
    $raw_pw = trim($_POST['password']);
    
    $pw = password_hash($raw_pw, PASSWORD_DEFAULT);
    $fn = trim($_POST['full_name']);
    $role = 'Assessor';

    $stmt = $conn->prepare("INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $un, $pw, $fn, $role);

    if ($stmt->execute()) {
        header("Location: admin_assessors.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Assessor</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Add New Assessor Account</h2>
        <form method="post" class="admin-form" id="assessorForm">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Account</button>
            <a href="admin_assessors.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
<script>
document.getElementById('assessorForm').addEventListener('submit', function(event) {
    const username = document.querySelector('input[name="username"]').value;
    const password = document.querySelector('input[name="password"]').value;

    const usernameRegex = /^[a-zA-Z0-9_]+$/;
    if (!usernameRegex.test(username)) {
        alert('warning:Username can only contain letters, numbers, and underscores');
        event.preventDefault(); 
        return;
    }

    if (password.length < 6) {
        alert('Warning: For safety, the password must be at least 6 characters long!');
        event.preventDefault(); 
        return;
    }
});
</script>
</body>
</html>
