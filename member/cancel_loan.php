<?php
header('Content-Type: application/json'); // Set response as JSON

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

include '../connection/config.php';

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate loan ID
    if (!isset($_POST['loanID']) || !is_numeric($_POST['loanID'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid loan ID']);
        exit();
    }

    $loanID = intval($_POST['loanID']);
    $userID = intval($_SESSION['user_id']);

    // Validate IDs
    if ($loanID <= 0 || $userID <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit();
    }

    // Cancel the loan
    $cancel_sql = "UPDATE loanapplication SET Status = 'Cancelled' WHERE LoanID = ? AND userID = ?";
    $cancel_stmt = $conn->prepare($cancel_sql);

    if (!$cancel_stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
        exit();
    }

    $cancel_stmt->bind_param("ii", $loanID, $userID);

    if ($cancel_stmt->execute()) {
        $cancel_stmt->close();
        echo json_encode(['success' => true, 'message' => 'Loan cancelled successfully']);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel loan']);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}
?>