<?php
// Start session securely
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../connection/config.php';

// Set JSON response header
header('Content-Type: application/json');

// Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    // Validate session and input
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("Session expired. Please log in again.");
    }
    
    if (!isset($_POST['loan_id']) || !isset($_POST['loan_type']) || !isset($_POST['ModeOfPayment']) || 
        !isset($_POST['payable_amount']) || !isset($_POST['payable_date'])) {
        throw new Exception("Missing required payment information.");
    }

    // Begin transaction
    $conn->begin_transaction();

    // Get user details
    $user_stmt = $conn->prepare("SELECT first_name, last_name, email FROM users WHERE user_id = ?");
    if (!$user_stmt) {
        throw new Exception("Database error: Unable to prepare user statement.");
    }
    $user_stmt->bind_param("i", $_SESSION['user_id']);
    if (!$user_stmt->execute()) {
        throw new Exception("Database error: Unable to fetch user details.");
    }
    $user = $user_stmt->get_result()->fetch_assoc();
    $user_stmt->close();

    if (!$user) {
        throw new Exception("User not found.");
    }

    // Get service ID
    $service_name = ($_POST['loan_type'] === 'Regular') ? 'Regular Loan Payment' : 'Collateral Loan Payment';
    $service_stmt = $conn->prepare("SELECT id FROM services WHERE name = ?");
    if (!$service_stmt) {
        throw new Exception("Database error: Unable to prepare service statement.");
    }
    $service_stmt->bind_param("s", $service_name);
    if (!$service_stmt->execute()) {
        throw new Exception("Database error: Unable to fetch service details.");
    }
    $service = $service_stmt->get_result()->fetch_assoc();
    $service_stmt->close();

    if (!$service) {
        throw new Exception("Service not found.");
    }

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
    
    if (!$insert_stmt) {
        throw new Exception("Database error: Unable to prepare appointment statement.");
    }

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
        throw new Exception("Database error: Unable to create appointment.");
    }
    
    $appointment_id = $conn->insert_id;
    $insert_stmt->close();

    // Commit transaction
    $conn->commit();

    // Generate receipt number
    $receipt_number = 'RCPT-' . str_pad($appointment_id, 6, '0', STR_PAD_LEFT);
    
    $response = [
        'success' => true,
        'message' => 'Payment appointment scheduled successfully! Please visit the office to complete your payment.',
        'receipt_number' => $receipt_number,
        'appointment_id' => $appointment_id
    ];

} catch (Exception $e) {
    // Rollback transaction on error
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->rollback();
    }
    
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];

} finally {
    // Return JSON response
    echo json_encode($response);
    
    // Close connection if it exists
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    exit();
}
?>