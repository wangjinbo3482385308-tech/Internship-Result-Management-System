<?php
require_once 'auth_check.php';
checkRole('Admin');
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sid = $_POST['student_id'];
    $name = $_POST['student_name'];
    $prog = $_POST['programme'];

    $stmt = $conn->prepare("INSERT INTO students (student_id, student_name, programme) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $sid, $name, $prog);

    if ($stmt->execute()) {
        $msg = "Student added successfully!";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Add New Student</h2>
        <div style="margin-bottom: 20px;">
            <a href="admin_students.php" class="btn btn-secondary">← Back to List</a>
        </div>
        <?php if(isset($msg)) echo "<p>$msg</p>"; ?>
        <form method="post" class="admin-form">
            <div class="form-group">
                <label>Student ID:</label>
                <input type="text" name="student_id" required>
            </div>
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="student_name" required>
            </div>
            <div class="form-group">
                <label>Programme:</label>
                <input type="text" name="programme" required>
            </div>
            <button type="submit" class="btn-submit">Save Student</button>
        </form>
    </div>
</body>
</html>
