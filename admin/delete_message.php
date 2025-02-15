<?php
session_start();
include '../connection/config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    die("Unauthorized access");
}

// Check if message_id is set
if (isset($_GET['message_id'])) {
    $message_id = intval($_GET['message_id']);

    // Prepare SQL to delete message
    $sql = "DELETE FROM messages WHERE message_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);

    if ($stmt->execute()) {
        header("Location: index.php?success=2");
    } else {
        header("Location: index.php?error=2");
    }
}
?>