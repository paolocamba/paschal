<?php
session_start();
include '../connection/config.php';

// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reject'])) {
    $user_id = $_POST['user_id'];
    
    try {
        // Update membership status to 'Rejected'
        $stmt = $conn->prepare("UPDATE users SET membership_status = 'Rejected' WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        
        // Also update the member_applications table
        $stmt2 = $conn->prepare("UPDATE member_applications SET status = 'Rejected' WHERE user_id = ?");
        $stmt2->bind_param("s", $user_id);
        $stmt2->execute();
        
        // Redirect back with success message
        header("Location: member.php?rejected=1");
        exit();
        
    } catch (Exception $e) {
        // Handle error
        header("Location: member.php?error=1");
        exit();
    }
} else {
    header("Location: member.php");
    exit();
}
?>
