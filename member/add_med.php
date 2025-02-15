<?php
session_start();
include '../connection/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

// Sanitize and validate inputs
$service = mysqli_real_escape_string($conn, $_POST['service']);
$appointmentDate = mysqli_real_escape_string($conn, $_POST['date']);

// Validate inputs
if (empty($service) || empty($appointmentDate)) {
    header("Location: services.php?error=Missing required fields");
    exit();
}

// Get the service ID based on the service name
$serviceQuery = "SELECT id FROM services WHERE name = '$service' AND type = 'Medical'";
$serviceResult = mysqli_query($conn, $serviceQuery);

if (!$serviceResult) {
    header("Location: services.php?error=Service not found");
    exit();
}

$serviceRow = mysqli_fetch_assoc($serviceResult);
$serviceID = $serviceRow['id'];

// Split full name into first and last name
$fullName = $_SESSION['full_name'];
$nameParts = explode(' ', $fullName, 2);
$firstName = $nameParts[0];
$lastName = isset($nameParts[1]) ? $nameParts[1] : '';

// Begin transaction to ensure data integrity
mysqli_begin_transaction($conn);

try {
    // Create description with service name
    $description = $service;

    // Insert into appointments table with description
    $appointmentQuery = "INSERT INTO appointments 
                         (user_id, first_name, last_name, email, serviceID, appointmentdate, status, description) 
                         VALUES 
                         (
                             '{$_SESSION['user_id']}', 
                             '$firstName', 
                             '$lastName', 
                             '{$_SESSION['email']}', 
                             '$serviceID', 
                             '$appointmentDate', 
                             'Pending',
                             '$description'
                         )";
    
    $appointmentResult = mysqli_query($conn, $appointmentQuery);

    // Get the last inserted appointment ID
    $appointmentId = mysqli_insert_id($conn);

    // Insert into transactions table
    $transactionQuery = "INSERT INTO transactions 
                         (user_id, service_name, payment_status) 
                         VALUES 
                         (
                             '{$_SESSION['user_id']}', 
                             '$service', 
                             'In Progress'
                         )";
    
    $transactionResult = mysqli_query($conn, $transactionQuery);

    // Commit the transaction if both queries succeed
    mysqli_commit($conn);

    // Redirect with success
    header("Location: services.php?success=1");
    exit();

} catch (Exception $e) {
    // Rollback the transaction in case of error
    mysqli_rollback($conn);

    // Redirect with error
    header("Location: services.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>