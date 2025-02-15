<?php
session_start();
include '../connection/config.php';

if (isset($_SESSION['user_id'])) {
    // Update is_logged_in status
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
    $updateStatus = "UPDATE users SET is_logged_in = 0 WHERE user_id = '$user_id'";
    mysqli_query($conn, $updateStatus);

    // Completely clear session
    session_unset();     // Unset all session variables
    session_destroy();   // Destroy the session
    
    // Unset session cookie for localhost
    if (ini_get("session.use_cookies")) {
        setcookie(session_name(), '', time() - 42000, '/');
    }
}

// Clear all $_SESSION data
$_SESSION = array();

// Redirect to login page
header("Location: ../login.php");
exit();
?>