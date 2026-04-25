<?php
require_once 'auth_check.php';
checkRole('Admin'); 
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $assessor_id = $_POST['assessor_id'];
    $company_name = $_POST['company_name'];

    $sql = "INSERT INTO internships (student_id, assessor_id, company_name) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $student_id, $assessor_id, $company_name);

    if ($stmt->execute()) {
        $success = "Assignment successful! The student can now be evaluated.";
    } else {
        $error = "Assignment Failure:" . $conn->error;
    }
}

$students_result = $conn->query("SELECT student_id, student_name FROM students");

$assessors_result = $conn->query("SELECT user_id, full_name FROM users WHERE role = 'Assessor'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Internship Tasks - Internship Management System</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Assign internship tasks</h2>
        <div style="margin-bottom: 20px;">
            <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
        <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form method="post" action="" class="admin-form">
            <div class="form-group">
                <label>Select a student:</label>
                <select name="student_id" required>
                    <option value="">-- Please select students --</option>
                    <?php while($s = $students_result->fetch_assoc()): ?>
                        <option value="<?php echo $s['student_id']; ?>">
                            <?php echo $s['student_id'] . " - " . $s['student_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Assignment assessor:</label>
                <select name="assessor_id" required>
                    <option value="">-- Please select the assessor --</option>
                    <?php while($a = $assessors_result->fetch_assoc()): ?>
                        <option value="<?php echo $a['user_id']; ?>">
                            <?php echo $a['full_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Internship Company Name:</label>
                <input type="text" name="company_name" placeholder="eg:Google Malaysia" required>
            </div>

            <button type="submit" class="btn-submit">Confirm allocation</button>
        </form>
    </div>
</body>
</html>
