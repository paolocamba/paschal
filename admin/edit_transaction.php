<?php
require_once '../connection/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $transaction_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT); // Change variable name but keep form field name for now
    $service_id = filter_input(INPUT_POST, 'service', FILTER_VALIDATE_INT);
    $payment_status = filter_input(INPUT_POST, 'payment_status', FILTER_SANITIZE_STRING);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
    $control_number = filter_input(INPUT_POST, 'control_number', FILTER_SANITIZE_STRING);

    // Validate required fields
    if ($transaction_id === false || $service_id === false || empty($payment_status) || $amount === false || empty($control_number)) {
        $_SESSION['error'] = "Invalid input. Please try again.";
        header("Location: transaction.php?success=0");
        exit();
    }

    // Get service name
    $service_query = "SELECT name FROM services WHERE id = ?";
    $stmt_service = $conn->prepare($service_query);
    $stmt_service->bind_param("i", $service_id);
    $stmt_service->execute();
    $service_result = $stmt_service->get_result();

    if ($service_result->num_rows === 0) {
        $_SESSION['error'] = "Invalid service selected.";
        header("Location: transaction.php?success=0");
        exit();
    }

    $service_row = $service_result->fetch_assoc();
    $service_name = $service_row['name'];

    // Updated query to use transaction_id instead of user_id
    $update_query = "UPDATE transactions SET 
                        service_name = ?, 
                        payment_status = ?, 
                        amount = ?,
                        control_number = ?
                     WHERE transaction_id = ?";  // Changed from user_id to transaction_id
    $stmt = $conn->prepare($update_query);

    $stmt->bind_param(
        "ssdsi", 
        $service_name, 
        $payment_status, 
        $amount,
        $control_number,
        $transaction_id    // Using transaction_id here
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "Transaction updated successfully.";
    } else {
        $_SESSION['error'] = "Error updating transaction: " . $stmt->error;
    }

    $stmt_service->close();
    $stmt->close();

    header("Location: transaction.php?success=1");
    exit();
} else {
    $_SESSION['error'] = "Invalid access method.";
    header("Location: transaction.php?success=0");
    exit();
}
?>