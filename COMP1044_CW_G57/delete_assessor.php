<?php
require_once 'auth_check.php';
require_once 'db.php';
checkRole('Admin');

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $check_sql = "SELECT COUNT(*) as count FROM internships WHERE assessor_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $count = $check_stmt->get_result()->fetch_assoc()['count'];

    if ($count > 0) {
        die("Cannot delete: This assessor is currently responsible for $count students' internships. Please remove them from the internship assignments first.");
    }

    $delete_sql = "DELETE FROM users WHERE user_id = ? AND role = 'Assessor'";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);
    
    if ($delete_stmt->execute()) {
        header("Location: admin_assessors.php?msg=Deleted successfully");
    } else {
        echo "delete failure: " . $conn->error;
    }
} else {
    header("Location: admin_assessors.php");
}
exit();
