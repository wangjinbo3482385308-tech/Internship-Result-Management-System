<?php
session_start();
require_once 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'Admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: assessor_dashboard.php");
            }
            exit();
        } else {
            $error = "Incorrect password!Please check for capitalization or extra spaces.";
        }
    } else {
        $error = "Cannot find this username '{$username}'!Please check the spelling.";
    }
}
?>
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Internship System</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="login-container">
        <h2>System Login</h2>
        <?php if (isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Please enter your username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Please enter your password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
