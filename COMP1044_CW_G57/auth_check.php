<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function checkRole($requiredRole) {
    if ($_SESSION['role'] !== $requiredRole) {
        die("warning：you can not access to this web.");
    }
}
?>
