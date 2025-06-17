<?php
// Share Capital Deposit Script
include '../connection/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shareCapitalID = $_POST['ShareCapitalID'];
    $memberID = $_POST['MemberID'];
    $status = $_POST['Status'];
    
    $signature = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'unknown';

    try {
        // Start transaction
        $conn->begin_transaction();

        // 1. Update share capital status
        $update_sql = "UPDATE share_capital SET Status = ? WHERE ShareCapitalID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $status, $shareCapitalID);
        $update_stmt->execute();

        if ($status == 'Approved') {
            // 2. Get user information
            $user_sql = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
            $user_stmt = $conn->prepare($user_sql);
            $user_stmt->bind_param("s", $memberID);
            $user_stmt->execute();
            $user_result = $user_stmt->get_result();
            $user_data = $user_result->fetch_assoc();

            date_default_timezone_set('Asia/Manila');
            $current_date = date('Y-m-d');

            // 3. Insert into appointments
            $app_sql = "INSERT INTO appointments (first_name, last_name, email, appointmentdate, user_id, Status, description) 
                       VALUES (?, ?, ?, ?, ?, 'Approved', 'Share Capital Deposit')";
            $app_stmt = $conn->prepare($app_sql);
            $app_stmt->bind_param("sssss", 
                $user_data['first_name'],
                $user_data['last_name'],
                $user_data['email'],
                $current_date,
                $memberID
            );
            $app_stmt->execute();

            // 4. Get share capital amount
            $share_sql = "SELECT Amount FROM share_capital WHERE ShareCapitalID = ?";
            $share_stmt = $conn->prepare($share_sql);
            $share_stmt->bind_param("i", $shareCapitalID);
            $share_stmt->execute();
            $share_result = $share_stmt->get_result();
            $share_data = $share_result->fetch_assoc();
            $share_amount = $share_data['Amount'];

            // 5. Get current share capital from users table
            $user_share_sql = "SELECT share_capital FROM users WHERE user_id = ?";
            $user_share_stmt = $conn->prepare($user_share_sql);
            $user_share_stmt->bind_param("s", $memberID);
            $user_share_stmt->execute();
            $user_share_result = $user_share_stmt->get_result();
            $user_share_data = $user_share_result->fetch_assoc();

            // Calculate new share capital balance
            $current_share_capital = $user_share_data['share_capital'] ?? 0;
            $new_share_capital = $current_share_capital + $share_amount;

            // 6. Update users table with new share capital amount
            $update_user_share_sql = "UPDATE users SET share_capital = ? WHERE user_id = ?";
            $update_user_share_stmt = $conn->prepare($update_user_share_sql);
            $update_user_share_stmt->bind_param("ds", $new_share_capital, $memberID);
            $update_user_share_stmt->execute();

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
                         VALUES (?, 'Share Capital Deposit', ?, ?, 'Completed', ?)";
            $trans_stmt = $conn->prepare($trans_sql);
            $trans_stmt->bind_param("sdss", 
                $memberID,
                $share_amount,
                $control_number,
                $signature
            );
            $trans_stmt->execute();
        }

        // Commit transaction
        $conn->commit();

        // Redirect after success
        header("Location: sharecapital.php?success=2");
        exit();

    } catch (Exception $e) {
        // Rollback transaction if there’s an error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
