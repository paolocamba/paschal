<?php
session_start();
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get loan ID from the form
    $loanID = $_POST['loanID'];
    $userID = $_SESSION['user_id']; // Get the logged-in user's ID

    // Update the loan status to 'Cancelled'
    $cancel_sql = "UPDATE loan_applications SET Status = 'Cancelled' WHERE LoanID = ? AND userID = ?";
    $cancel_stmt = $conn->prepare($cancel_sql);
    $cancel_stmt->bind_param("ii", $loanID, $userID);

    if ($cancel_stmt->execute()) {
        header("Location: home.php?success=3");
        exit();
    } else {
        $stmt->close();
        header("Location: home.php?error=3");
        exit();
    }
}
?>
