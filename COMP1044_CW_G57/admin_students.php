<?php
require_once 'auth_check.php'; 
checkRole('Admin');

require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$sql = "SELECT student_id, student_name, programme FROM students"; 
$result = $conn->query($sql); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Internship Management System</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Admin - Student Archive Management</h2> 
        <a href="assign_internship.php" class="btn-link">Assign Internship</a>
        <a href="add_student.php" class="btn-add">+ Add Student</a>
        
        <table border="1" class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Programme</th>
                    <th>Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["student_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["student_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["programme"]) . "</td>";
                        echo "<td>
                                <a href='edit_student.php?id=" . $row["student_id"] . "'>Edit</a> | 
                                <a href='delete_student.php?id=" . $row["student_id"] . "' onclick='return confirm(\"Are you sure you want to delete this student?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No student records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
