<?php
require_once 'auth_check.php';
checkRole('Assessor'); 

require_once 'db.php';

$current_assessor_id = $_SESSION['user_id'];

$sql = "SELECT s.student_id, s.student_name, s.programme, i.company_name, i.internship_id 
        FROM internships i 
        JOIN students s ON i.student_id = s.student_id 
        WHERE i.assessor_id = '$current_assessor_id'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Evaluator Workbench - Internship Management System</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>welcome back, <?php echo $_SESSION['username']; ?> (Assessor)</h2>
            <a href="logout.php" class="btn-logout">log out</a>
        </header>

        <h3>The list of students I am responsible for evaluating</h3>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>student ID</th>
                    <th>student name</th>
                    <th>major</th>
                    <th>internship company</th>
                    <th>opearation</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['student_id']; ?></td>
                            <td><?php echo $row['student_name']; ?></td>
                            <td><?php echo $row['programme']; ?></td>
                            <td><?php echo $row['company_name']; ?></td>
                            <td>
                                <a href="evaluate_student.php?intern_id=<?php echo $row['internship_id']; ?>" class="btn-assess">Start rating</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">There are no students assigned to you currently.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
