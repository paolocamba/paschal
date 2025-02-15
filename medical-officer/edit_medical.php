<?php
require_once '../connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = filter_input(INPUT_POST, 'transaction_id', FILTER_VALIDATE_INT); // Changed from user_id to transaction_id
    $payment_status = filter_input(INPUT_POST, 'payment_status', FILTER_SANITIZE_STRING);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $control_number = filter_input(INPUT_POST, 'control_number', FILTER_SANITIZE_STRING);

    if ($transaction_id === false || empty($payment_status) || $amount === false || empty($control_number)) {
        $_SESSION['error'] = "Invalid input. Please try again.";
        header("Location: medical.php?success=0");
        exit();
    }

    $update_query = "UPDATE transactions SET
                    payment_status = ?, 
                    amount = ?,
                    control_number = ?
                    WHERE transaction_id = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sdsi", 
        $payment_status, 
        $amount,
        $control_number,
        $transaction_id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Transaction updated successfully.";
        header("Location: medical.php?success=1");
    } else {
        $_SESSION['error'] = "Error updating transaction: " . $stmt->error;
        header("Location: medical.php?success=0");
    }

    $stmt->close();
    exit();
} else {
    $_SESSION['error'] = "Invalid access method.";
    header("Location: medical.php?success=0");
    exit();
}
?>