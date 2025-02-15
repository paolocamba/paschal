<?php
// Include database connection
include '../connection/config.php';

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $memberID = $_POST['MemberID'];
    $amount = $_POST['Amount'];
    $paymentMethod = $_POST['PaymentMethod'];
    $notes = $_POST['Notes'];
    $status = $_POST['Status'];
    
    // Get user_type from session
    $signature = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'unknown';

    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        // 1. Insert into savings table
        $query = "INSERT INTO savings (MemberID, Amount, PaymentMethod, Notes, Status) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sdsss", $memberID, $amount, $paymentMethod, $notes, $status);
        $savings_result = mysqli_stmt_execute($stmt);

        if (!$savings_result) {
            throw new Exception("Error inserting into savings table");
        }

        // 2. Get user details for appointments table
        $user_query = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "s", $memberID);
        mysqli_stmt_execute($stmt);
        $user_result = mysqli_stmt_get_result($stmt);
        $user_data = mysqli_fetch_assoc($user_result);

         // Insert into appointments table
         date_default_timezone_set('Asia/Manila');
         $current_date = date('Y-m-d');
         $appointment_status = 'Approved';
         $description = 'Savings Deposit';  // Added description
         
         $query = "INSERT INTO appointments (first_name, last_name, email, appointmentdate, user_id, status, description) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
         $stmt = mysqli_prepare($conn, $query);
         mysqli_stmt_bind_param($stmt, "sssssss", 
             $user_data['first_name'], 
             $user_data['last_name'], 
             $user_data['email'], 
             $current_date, 
             $memberID, 
             $appointment_status,
             $description
         );
        $appointment_result = mysqli_stmt_execute($stmt);

        if (!$appointment_result) {
            throw new Exception("Error inserting into appointments table");
        }

        // 3. Get next control number
        $control_query = "SELECT MAX(CAST(control_number AS UNSIGNED)) as max_number FROM transactions";
        $control_result = mysqli_query($conn, $control_query);
        $control_row = mysqli_fetch_assoc($control_result);
        $next_number = str_pad($control_row['max_number'] + 1, 5, '0', STR_PAD_LEFT);

        // Insert into transactions table with signature
        $service_name = 'Savings Deposit';
        $payment_status = 'Completed';

        $query = "INSERT INTO transactions (user_id, service_name, amount, control_number, payment_status, signature) 
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssdsss", 
            $memberID, 
            $service_name, 
            $amount, 
            $next_number, 
            $payment_status,
            $signature
        );
        $transaction_result = mysqli_stmt_execute($stmt);

        if (!$transaction_result) {
            throw new Exception("Error inserting into transactions table");
        }

        // If everything is successful, commit the transaction
        mysqli_commit($conn);
        
        // Redirect with success message
        header("Location: savings.php?success=1");
        exit();

    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        mysqli_rollback($conn);
        
        // Redirect with error message
        header("Location: savings.php?error=1");
        exit();
    }
}

// Close database connection
mysqli_close($conn);
?>