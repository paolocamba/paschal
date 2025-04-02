<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // First, get the appointment details
        $appointment_sql = "SELECT description, user_id, payable_amount FROM appointments WHERE id = ?";
        $stmt = $conn->prepare($appointment_sql);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
        
        // Update the appointment status
        $update_sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $status, $appointment_id);
        $update_stmt->execute();
        
        // Check if this is a loan payment appointment
        if ($appointment && ($appointment['description'] === 'Regular Loan Payment' || 
            $appointment['description'] === 'Collateral Loan Payment') && 
            $status === 'Approved') {
            
            // Get the next control number
            $sql_control = "SELECT MAX(CAST(control_number AS UNSIGNED)) as max_control FROM transactions";
            $result = $conn->query($sql_control);
            $row = $result->fetch_assoc();
            $next_control = str_pad((intval($row['max_control']) + 1), 5, '0', STR_PAD_LEFT);
            
            // Insert into transactions table
            $transaction_sql = "INSERT INTO transactions (user_id, amount, service_name, control_number) 
                              VALUES (?, ?, ?, ?)";
            $transaction_stmt = $conn->prepare($transaction_sql);
            $transaction_stmt->bind_param(
                "idss",
                $appointment['user_id'],
                $appointment['payable_amount'],
                $appointment['description'],
                $next_control
            );
            $transaction_stmt->execute();
        }
        
        // Commit the transaction
        $conn->commit();
        header("Location: appointment.php?success=1");
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        header("Location: appointment.php?error=1");
    }
    
    // Close the connection
    $conn->close();
} else {
    header("Location: appointment.php?error=1");
}
?>