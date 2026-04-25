<?php
require_once 'auth_check.php';
require_once 'db.php';
checkRole('Admin');

if (!isset($_GET['id'])) {
    header("Location: admin_assessors.php");
    exit();
}
$user_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ? AND role = 'Assessor'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$assessor = $stmt->get_result()->fetch_assoc();

if (!$assessor) die("Assessors not found.");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $new_password = $_POST['password'];

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET username = ?, password = ?, full_name = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssi", $username, $hashed_password, $full_name, $user_id);
    } else {
        $update_sql = "UPDATE users SET username = ?, full_name = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $username, $full_name, $user_id);
    }

    if ($update_stmt->execute()) {
        header("Location: admin_assessors.php?msg=Assessor updated successfully");
        exit();
    } else {
        $error = "update fialure: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Assessor</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Assessor Account</h2>
        <p><a href="admin_assessors.php" class="btn btn-secondary">← Back</a></p>

        <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

        <form method="post" class="admin-form">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($assessor['username']); ?>" required>
            </div>
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($assessor['full_name']); ?>" required>
            </div>
            <div class="form-group">
                <label>New Password (leave blank to keep current):</label>
                <input type="password" name="password" placeholder="******">
            </div>
            <button type="submit" class="btn btn-primary">Update Assessor</button>
        </form>
    </div>
</body>
</html>
