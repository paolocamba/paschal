<?php
include '../connection/config.php';

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $savingsID = $_POST['SavingsID'];
    $memberID = $_POST['MemberID'];
    $status = $_POST['Status'];

    // Get user_type from session
    $signature = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'unknown';

    try {
        // Start transaction
        $conn->begin_transaction();

        // 1. Update savings status
        $update_sql = "UPDATE savings SET Status = ? WHERE SavingsID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $status, $savingsID);
        $update_stmt->execute();

        if ($status == 'Approved') {
            // 2. Get user information
            $user_sql = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bind_param("s", $memberID);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user_data = $user_result->fetch_assoc();

            // Get current date in PH/Manila timezone
            date_default_timezone_set('Asia/Manila');
            $current_date = date('Y-m-d');

            // 3. Insert into appointments
            $app_sql = "INSERT INTO appointments (first_name, last_name, email, appointmentdate, user_id, Status, description) 
                        VALUES (?, ?, ?, ?, ?, 'Approved', 'Savings Deposit')";
            $app_stmt = $conn->prepare($app_sql);
            $app_stmt->bind_param("sssss", 
                $user_data['first_name'],
                $user_data['last_name'],
                $user_data['email'],
                $current_date,
                $memberID
            );
            $app_stmt->execute();

            // 4. Fetch savings amount
            $savings_sql = "SELECT Amount FROM savings WHERE SavingsID = ?";
            $savings_stmt = $conn->prepare($savings_sql);
            $savings_stmt->bind_param("i", $savingsID);
            $savings_stmt->execute();
            $savings_result = $savings_stmt->get_result();
            $savings_data = $savings_result->fetch_assoc();
            $savings_amount = $savings_data['Amount'];

            // 5. Get current savings from users table
            $user_savings_sql = "SELECT savings FROM users WHERE user_id = ?";
            $user_savings_stmt = $conn->prepare($user_savings_sql);
            $user_savings_stmt->bind_param("s", $memberID);
            $user_savings_stmt->execute();
            $user_savings_result = $user_savings_stmt->get_result();
            $user_savings_data = $user_savings_result->fetch_assoc();

            // Calculate new savings balance
            $current_savings = $user_savings_data['savings'] ?? 0;
            $new_savings = $current_savings + $savings_amount;

            // 6. Update users table with new savings amount
            $update_user_savings_sql = "UPDATE users SET savings = ? WHERE user_id = ?";
            $update_user_savings_stmt = $conn->prepare($update_user_savings_sql);
            $update_user_savings_stmt->bind_param("ds", $new_savings, $memberID);
            $update_user_savings_stmt->execute();

            // 7. Get next control number for transactions
            $control_sql = "SELECT MAX(CASE 
                WHEN control_number REGEXP '^[0-9]+$' 
                THEN CAST(control_number AS UNSIGNED) 
                ELSE CAST(SUBSTRING(control_number, 3) AS UNSIGNED) 
            END) as max_num FROM transactions WHERE control_number != ''";
            $control_result = $conn->query($control_sql);
            $control_row = $control_result->fetch_assoc();
            $next_num = ($control_row['max_num'] + 1);
            $control_number = '00' . str_pad($next_num, 3, '0', STR_PAD_LEFT);

            // 8. Insert into transactions table
            $trans_sql = "INSERT INTO transactions (user_id, service_name, amount, control_number, payment_status, signature) 
                          VALUES (?, 'Savings Deposit', ?, ?, 'Completed', ?)";
            $trans_stmt = $conn->prepare($trans_sql);
            $trans_stmt->bind_param("sdss", 
                $memberID,
                $savings_amount,
                $control_number,
                $signature
            );
            $trans_stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        // Redirect after success
        header("Location: savings.php?success=2");
        exit();

    } catch (Exception $e) {
        // Rollback transaction if there’s an error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
