<?php
session_start();

// Debugging: Log session and POST data
error_log("Session Data: " . print_r($_SESSION, true));
error_log("POST Data: " . print_r($_POST, true));

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("User not logged in. Redirecting to login page.");
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize the loan ID
    if (!isset($_POST['loanID']) || !is_numeric($_POST['loanID'])) {
        error_log("Invalid loan ID provided.");
        header("Location: home.php?error=invalid_loan_id");
        exit();
    }

    $loanID = intval($_POST['loanID']);
    $userID = intval($_SESSION['user_id']); // Ensure user ID is an integer

    // Debugging: Log loan ID and user ID
    error_log("Loan ID: $loanID, User ID: $userID");

    // Validate loan ID and user ID
    if ($loanID <= 0 || $userID <= 0) {
        error_log("Invalid loan ID or user ID.");
        header("Location: home.php?error=invalid_input");
        exit();
    }

    // Prepare the SQL statement to cancel the loan
    $cancel_sql = "UPDATE loanapplication SET Status = 'Cancelled' WHERE LoanID = ? AND userID = ?";
    $cancel_stmt = $conn->prepare($cancel_sql);

    if (!$cancel_stmt) {
        error_log("Prepare Statement Failed: " . $conn->error); // Log the error
        header("Location: home.php?error=database_error");
        exit();
    }

    // Bind parameters and execute the statement
    $cancel_stmt->bind_param("ii", $loanID, $userID);

    if ($cancel_stmt->execute()) {
        // Success: Redirect back to the page with a success message
        error_log("Loan cancellation successful for Loan ID: $loanID");
        $cancel_stmt->close();
        header("Location: home.php?success=3");
        exit();
    } else {
        // Error: Log the error and redirect back with an error message
        error_log("Cancel Loan Error: " . $cancel_stmt->error); // Log the error
        $cancel_stmt->close();
        header("Location: home.php?error=3");
        exit();
    }
} else {
    // If the request method is not POST, redirect back
    error_log("Invalid request method.");
    header("Location: home.php");
    exit();
}
?>