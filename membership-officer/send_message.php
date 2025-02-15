<?php
session_start();
include '../connection/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['recipient'];
    $category = $_POST['category'];
    $message = $_POST['membership_message'];
    
    // Prepare SQL statement
    $sql = "INSERT INTO messages (
                sender_id,
                receiver_id,
                category,
                membership_message,
                membership_date,
                is_replied,
                is_read,
                datesent
            ) VALUES (?, ?, ?, ?, NOW(), 0, 0, NOW())";
    
    // Prepare statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("iiss", $sender_id, $receiver_id, $category, $message);
    
    // Execute the query and redirect
    if ($stmt->execute()) {
        header("Location: inbox.php?success=1");
    } else {
        header("Location: inbox.php?error=1");
    }
    exit();
}
?>
