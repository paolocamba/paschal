<?php
include '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id']) && isset($_POST['status'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    
    $conn->begin_transaction();
    
    try {
        // Fetch appointment details
        $appointment_sql = "SELECT description, user_id, payable_amount, SavingsID, ShareCapitalID FROM appointments WHERE id = ?";
        $stmt = $conn->prepare($appointment_sql);
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();

        if (!$appointment) {
            throw new Exception("Appointment not found.");
        }

        // Update appointment status
        $update_sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $status, $appointment_id);
        $update_stmt->execute();

        // Function to generate a unique control number
        function generateUniqueControlNumber($conn) {
            $sql_control = "SELECT MAX(CAST(control_number AS UNSIGNED)) as max_control FROM transactions";
            $result = $conn->query($sql_control);
            $row = $result->fetch_assoc();

            if ($row['max_control'] === null) {
                $row['max_control'] = 0;
            }

            do {
                $next_control = str_pad((intval($row['max_control']) + 1), 5, '0', STR_PAD_LEFT);
                $check_sql = "SELECT 1 FROM transactions WHERE control_number = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("s", $next_control);
                $check_stmt->execute();
                $check_stmt->store_result();
                $exists = $check_stmt->num_rows > 0;
                $row['max_control']++;
            } while ($exists);

            return $next_control;
        }

        // Insert into transactions if status is Approved
        if ($status === 'Approved') {
            $next_control = generateUniqueControlNumber($conn);
            $user_id = $appointment['user_id'];
            $amount = $appointment['payable_amount'];
            $description = $appointment['description'];
            $savingsID = $appointment['SavingsID'];
            $shareCapitalID = $appointment['ShareCapitalID'];

            if ($description === 'Regular Loan Payment' || $description === 'Collateral Loan Payment') {
                // Loan payments – no SavingsID or ShareCapitalID
                $transaction_sql = "INSERT INTO transactions (user_id, amount, service_name, control_number) 
                                    VALUES (?, ?, ?, ?)";
                $transaction_stmt = $conn->prepare($transaction_sql);
                $transaction_stmt->bind_param("idss", $user_id, $amount, $description, $next_control);

            } elseif (!empty($savingsID)) {
                // With SavingsID
                $transaction_sql = "INSERT INTO transactions (user_id, amount, service_name, control_number, payment_status, SavingsID) 
                                    VALUES (?, ?, ?, ?, 'In Progress', ?)";
                $transaction_stmt = $conn->prepare($transaction_sql);
                $transaction_stmt->bind_param("idssi", $user_id, $amount, $description, $next_control, $savingsID);

            } elseif (!empty($shareCapitalID)) {
                // With ShareCapitalID
                $transaction_sql = "INSERT INTO transactions (user_id, amount, service_name, control_number, payment_status, ShareCapitalID) 
                                    VALUES (?, ?, ?, ?, 'In Progress', ?)";
                $transaction_stmt = $conn->prepare($transaction_sql);
                $transaction_stmt->bind_param("idssi", $user_id, $amount, $description, $next_control, $shareCapitalID);

            } else {
                // No reference to savings or share capital
                $transaction_sql = "INSERT INTO transactions (user_id, amount, service_name, control_number, payment_status) 
                                    VALUES (?, ?, ?, ?, 'In Progress')";
                $transaction_stmt = $conn->prepare($transaction_sql);
                $transaction_stmt->bind_param("idss", $user_id, $amount, $description, $next_control);
            }

            $transaction_stmt->execute();
        }

        $conn->commit();
        header("Location: appointment.php?success=1");
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error processing appointment update: " . $e->getMessage());
        header("Location: appointment.php?error=1");
    }

    $conn->close();
} else {
    header("Location: appointment.php?error=1");
}
?>
