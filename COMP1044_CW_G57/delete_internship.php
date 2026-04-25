<?php
require_once 'auth_check.php';
require_once 'db.php';
checkRole('Admin');

if (isset($_GET['id'])) {
    $internship_id = intval($_GET['id']);

    $delete_assessment = "DELETE FROM assessments WHERE internship_id = $internship_id";
    $conn->query($delete_assessment);

    $delete_internship = "DELETE FROM internships WHERE internship_id = ?";
    $stmt = $conn->prepare($delete_internship);
    $stmt->bind_param("i", $internship_id);
    
    if ($stmt->execute()) {

        header("Location: admin_results.php");
    } else {
        echo "Delete failed: " . $conn->error;
    }
} else {
    header("Location: admin_results.php");
}
exit();
?>
