<?php
require_once 'auth_check.php';
checkRole('Admin');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>Welcome to the Admin Dashboard</h2>
            <a href="logout.php" class="btn btn-danger">Log Out</a>
        </header>
        
        <div class="action-bar" style="flex-direction: column; gap: 15px; max-width: 300px; margin-top: 20px;">
            <a href="admin_students.php" class="btn btn-primary">Students Management</a>
            <a href="admin_assessors.php" class="btn btn-primary">Assessor Management</a>
            <a href="assign_internship.php" class="btn btn-secondary">Assign Internship Tasks</a>
            <a href="admin_results.php" class="btn btn-primary">View All Results</a>
        </div>
    </div>
</body>
</html>
