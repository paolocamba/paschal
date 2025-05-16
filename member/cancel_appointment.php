<?php
session_start();
include '../connection/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $appointmentId = $_POST['id'];
    $userId = $_SESSION['user_id'];
    
    // Verify the appointment belongs to the user
    $check_sql = "SELECT id FROM appointments WHERE id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $appointmentId, $userId);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Update the appointment status
        $update_sql = "UPDATE appointments SET status = 'Cancelled' WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $appointmentId);
        
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Appointment not found or not authorized']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>