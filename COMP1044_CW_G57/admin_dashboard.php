<?php
require_once 'auth_check.php';
checkRole('Admin');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome to the Admin</h1>
    <p><a href="admin_students.php">Students Management</a></p>
    <p><a href="logout.php">log out</a></p>
</body>
</html>
