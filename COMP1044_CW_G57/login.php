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
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login - Internship Grade Management System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 { color: #1e3a8a; margin-bottom: 1.5rem; font-size: 1.8rem; }

        .form-group { margin-bottom: 1.2rem; text-align: left; }
        label { display: block; margin-bottom: 0.5rem; color: #4b5563; font-weight: 600; }

        input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        button {
            width: 100%;
            padding: 0.8rem;
            background-color: #1e3a8a;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover { background-color: #1d4ed8; }

        .error-msg {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 0.7rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>System login</h2>
        
        <?php if(isset($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label>username</label>
                <input type="text" name="username" placeholder="Please enter your username" required>
            </div>
            <div class="form-group">
                <label>password</label>
                <input type="password" name="password" placeholder="Please enter your password" required>
            </div>
            <button type="submit">login</button>
        </form>
    </div>
</body>
</html>

