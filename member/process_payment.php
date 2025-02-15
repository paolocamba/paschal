<?php
session_start();
require_once '../connection/config.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['loan_id'])) {
    $_SESSION['error_message'] = "Invalid request or session expired.";
    header('Location: home.php');
    exit();
}

try {
    // Begin transaction
    $conn->begin_transaction();

    // Get user details
    $user_stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE user_id = ?");
    $user_stmt->bind_param("i", $_SESSION['user_id']);
    $user_stmt->execute();
    $user = $user_stmt->get_result()->fetch_assoc();

    // Get service ID
    $service_name = ($_POST['loan_type'] === 'Regular') ? 'Regular Loan Payment' : 'Collateral Loan Payment';
    $service_stmt = $conn->prepare("SELECT id FROM services WHERE name = ?");
    $service_stmt->bind_param("s", $service_name);
    $service_stmt->execute();
    $service = $service_stmt->get_result()->fetch_assoc();

    // Insert appointment
    $insert_stmt = $conn->prepare("
        INSERT INTO appointments (
            first_name, 
            last_name, 
            email, 
            ModeOfPayment,
            payable_amount, 
            payable_date, 
            description,
            user_id, 
            serviceID, 
            status, 
            appointmentdate
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', CURDATE())
    ");

    $insert_stmt->bind_param(
        "ssssdsssi",
        $user['first_name'],
        $user['last_name'],
        $user['email'],
        $_POST['ModeOfPayment'],
        $_POST['payable_amount'],
        $_POST['payable_date'],
        $service_name,
        $_SESSION['user_id'],
        $service['id']
    );

    if (!$insert_stmt->execute()) {
        throw new Exception("Failed to insert appointment: " . $insert_stmt->error);
    }

    // Commit transaction
    $conn->commit();

    header('Location: home.php?success=3');
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    $_SESSION['error_message'] = "An error occurred while processing your payment: " . $e->getMessage();
    header('Location: home.php');
    exit();

} finally {
    // Close all statements
    if (isset($user_stmt)) $user_stmt->close();
    if (isset($service_stmt)) $service_stmt->close();
    if (isset($insert_stmt)) $insert_stmt->close();
    $conn->close();
}
?>