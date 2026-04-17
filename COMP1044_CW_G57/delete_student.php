<?php
require_once 'auth_check.php';
checkRole('Admin');
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
}

header("Location: admin_students.php");
exit();
