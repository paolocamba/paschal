<?php
session_start();
require_once 'connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $currentTime = date('Y-m-d H:i:s');

    // Validate passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['message'] = "Passwords do not match";
        $_SESSION['message_type'] = "danger";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    // Validate password strength
    if (
        strlen($password) < 8 ||
        !preg_match("/[A-Z]/", $password) ||
        !preg_match("/[a-z]/", $password) ||
        !preg_match("/[0-9]/", $password)
    ) {
        $_SESSION['message'] = "Password must be at least 8 characters long and contain uppercase, lowercase, and numbers";
        $_SESSION['message_type'] = "danger";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    // Verify token and update password
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE reset_token = ? AND reset_token_expiry > ?");
    $stmt->bind_param("ss", $token, $currentTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $userId = $row['user_id'];

        // Hash new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);

        if ($updateStmt->execute()) {
            header("Location: login.php?success=1");
        } else {
            header("Location: reset_password.php?token=" . urlencode($token) . "&error=update_failed");
        }
        $updateStmt->close();
    } else {
        header("Location: forgot-pass.php?error=invalid_token");
    }


    $stmt->close();
    $conn->close();
    exit();
}
?>