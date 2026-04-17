<?php
require_once 'auth_check.php';
checkRole('Admin');
require_once 'db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['student_name'];
    $prog = $_POST['programme'];

    $update = $conn->prepare("UPDATE students SET student_name = ?, programme = ? WHERE student_id = ?");
    $update->bind_param("sss", $name, $prog, $id);
    
    if ($update->execute()) {
        header("Location: admin_students.php");
        exit();
    } else {
        $error = "Update failed.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Student Details</h2>
        <form method="post" class="admin-form">
            <div class="form-group">
                <label>Student ID (Locked):</label>
                <input type="text" value="<?php echo $student['student_id']; ?>" disabled>
            </div>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="student_name" value="<?php echo $student['student_name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Programme:</label>
                <input type="text" name="programme" value="<?php echo $student['programme']; ?>" required>
            </div>
            <button type="submit" class="btn-submit">Update Information</button>
        </form>
    </div>
</body>
</html>
