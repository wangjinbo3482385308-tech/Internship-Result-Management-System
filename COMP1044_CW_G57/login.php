<?php
session_start(); 
require_once 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $sql = "SELECT user_id, username, password, role FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

      
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
        $error = "Invalid password or username！";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>login - Internship Grade Management System</title>
</head>
<body>
    <h2>username login</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        username: <input type="text" name="username" required><br><br>
        password: <input type="password" name="password" required><br><br>
        <button type="submit">login</button>
    </form>
</body>
</html>
