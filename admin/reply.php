<?php
session_start();
include '../connection/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    die("Unauthorized access");
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
    $admin_reply = isset($_POST['admin_reply']) ? trim($_POST['admin_reply']) : '';

    // Validate inputs
    if ($message_id <= 0 || empty($admin_reply)) {
        $_SESSION['error'] = "Invalid input. Please provide a reply.";
        header("Location: messages.php");
        exit();
    }

    // Prepare SQL to update message with admin reply
    $sql = "UPDATE messages 
            SET admin_reply = ?, 
                admin_reply_date = NOW(), 
                is_replied = 1 
            WHERE message_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $admin_reply, $message_id);

    if ($stmt->execute()) {
        header("Location: index.php?success=2");
    } else {
        header("Location: index.php?error=2");
    }
}
?>