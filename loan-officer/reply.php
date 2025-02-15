

<?php
session_start();
include '../connection/config.php';

// Check if user is logged in and is an medical officer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Loan Officer') {
    die("Unauthorized access");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
    $loan_reply = isset($_POST['loan_reply']) ? trim($_POST['loan_reply']) : '';
    
    if ($message_id <= 0 || empty($loan_reply)) {
        $_SESSION['error'] = "Invalid input. Please provide a reply.";
        header("Location: inbox.php");
        exit();
    }
    
    $sql = "UPDATE messages 
            SET loan_reply = ?,
                loan_reply_date = NOW(),
                is_replied = 1
            WHERE message_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $loan_reply, $message_id);
    
    if ($stmt->execute()) {
        header("Location: inbox.php?success=2");
    } else {
        header("Location: inbox.php?error=2");
    }
}
?>