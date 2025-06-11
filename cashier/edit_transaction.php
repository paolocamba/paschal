<?php
require_once '../connection/config.php';
session_start();
$logged_in_user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $transaction_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
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

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update transaction
        $update_query = "UPDATE transactions SET 
                        service_name = ?, 
                        payment_status = ?, 
                        amount = ?,
                        control_number = ? 
                     WHERE transaction_id = ?";
        $stmt = $conn->prepare($update_query);

        $stmt->bind_param(
            "ssdsi", 
            $service_name, 
            $payment_status, 
            $amount,
            $control_number,
            $transaction_id
        );

        if (!$stmt->execute()) {
            throw new Exception("Error updating transaction: " . $stmt->error);
        }

        // Check if this is a loan payment
        if ($service_name === 'Regular Loan Payment' || $service_name === 'Collateral Loan Payment') {
            // Get user_id from the transaction
            $user_query = "SELECT user_id FROM transactions WHERE transaction_id = ?";
            $stmt_user = $conn->prepare($user_query);
            $stmt_user->bind_param("i", $transaction_id);
            $stmt_user->execute();
            $user_result = $stmt_user->get_result();

            if ($user_result->num_rows === 0) {
                throw new Exception("Transaction not found");
            }

            $user_row = $user_result->fetch_assoc();
            $user_id = $user_row['user_id'];

            // Get the latest active loan for this user
            $loan_query = "SELECT ch.LoanID, ch.PayableDate, ch.loanable_amount, ch.InterestRate, 
                        ch.PayableAmount, ch.LoanTerm, ch.ModeOfPayment, ch.TotalPayable
                        FROM credit_history ch
                        WHERE ch.MemberID = ? AND ch.Status = 'Active'
                        ORDER BY ch.LoanID DESC
                        LIMIT 1";
            $stmt_loan = $conn->prepare($loan_query);
            $stmt_loan->bind_param("i", $user_id);
            $stmt_loan->execute();
            $loan_result = $stmt_loan->get_result();

            if ($loan_result->num_rows > 0) {
                $loan_row = $loan_result->fetch_assoc();
                $loan_id = $loan_row['LoanID'];
                $payable_date = $loan_row['PayableDate'];
                $loanable_amount = $loan_row['loanable_amount'];
                $interest_rate = $loan_row['InterestRate'];
                $payable_amount = $loan_row['PayableAmount'];
                $loan_term = $loan_row['LoanTerm'];
                $mode_of_payment = $loan_row['ModeOfPayment'];
                $total_payable = $loan_row['TotalPayable'];

                // Calculate if payment is late
                $current_date = date('Y-m-d');
                $is_late = 0;
                $days_late = 0;
                $payment_status_loan = 'Completed';

                if (!empty($payable_date)) {
                    $payable_date_obj = new DateTime($payable_date);
                    $current_date_obj = new DateTime($current_date);

                    if ($current_date_obj > $payable_date_obj) {
                        $is_late = 1;
                        $interval = $payable_date_obj->diff($current_date_obj);
                        $days_late = $interval->days;
                        $payment_status_loan = 'Late';
                    }
                }

                if ($payment_status !== 'Completed') {
                    $payment_status_loan = $payment_status;
                }

                // Check if payment record already exists
                $check_payment_query = "SELECT payment_id FROM loan_payments WHERE transaction_id = ?";
                $stmt_check = $conn->prepare($check_payment_query);
                $stmt_check->bind_param("i", $transaction_id);
                $stmt_check->execute();
                $check_result = $stmt_check->get_result();

                if ($check_result->num_rows > 0) {
                    // Update existing payment record
                    $payment_query = "UPDATE loan_payments SET
                        amount_paid = ?,
                        payment_status = ?,
                        is_late = ?,
                        days_late = ?
                        WHERE transaction_id = ?";

                    $stmt_payment = $conn->prepare($payment_query);
                    $stmt_payment->bind_param("dsiii", 
                        $amount, 
                        $payment_status_loan,
                        $is_late,
                        $days_late,
                        $transaction_id
                    );
                } else {
                    // Insert new payment record
                    $payment_query = "INSERT INTO loan_payments (
                        transaction_id, LoanID, MemberID, amount_paid, receipt_number,
                        payment_status, is_late, days_late, recorded_by, payable_date
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $recorded_by = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : $user_id;

                    $stmt_payment = $conn->prepare($payment_query);
                    $stmt_payment->bind_param("iiidssiiis", 
                        $transaction_id, $loan_id, $user_id, $amount, 
                        $control_number, $payment_status_loan, $is_late, $days_late, $recorded_by,
                        $payable_date
                    );
                }

                if (!$stmt_payment->execute()) {
                    throw new Exception("Error recording loan payment: " . $stmt_payment->error);
                }

                // Calculate total paid amount
                $total_paid_query = "SELECT COALESCE(SUM(amount_paid), 0) as total_paid 
                                    FROM loan_payments 
                                    WHERE LoanID = ? AND payment_status IN ('Completed', 'Late')";
                $stmt_total = $conn->prepare($total_paid_query);
                $stmt_total->bind_param("i", $loan_id);
                $stmt_total->execute();
                $total_paid = $stmt_total->get_result()->fetch_assoc()['total_paid'];

                // Calculate balance based on TotalPayable - TotalPayment
                $balance = $total_payable - $total_paid;

                // Calculate next payment date and amount based on Mode of Payment
                $next_payable_date = null;
                $next_payable_amount = null;

                if ($balance > 0) {
                    switch (strtolower($mode_of_payment)) {
                        case 'weekly':
                            $next_payable_date = date('Y-m-d', strtotime($payable_date . ' +7 days'));
                            $next_payable_amount = $payable_amount;
                            break;
                        case 'bi-monthly':
                            $next_payable_date = date('Y-m-d', strtotime($payable_date . ' +15 days'));
                            $next_payable_amount = $payable_amount;
                            break;
                        case 'monthly':
                            $next_payable_date = date('Y-m-d', strtotime($payable_date . ' +1 month'));
                            $next_payable_amount = $payable_amount;
                            break;
                        case 'quarterly':
                            $next_payable_date = date('Y-m-d', strtotime($payable_date . ' +3 months'));
                            $next_payable_amount = $payable_amount;
                            break;
                        case 'semi-annual':
                            $next_payable_date = date('Y-m-d', strtotime($payable_date . ' +6 months'));
                            $next_payable_amount = $payable_amount;
                            break;
                        default:
                            $next_payable_date = date('Y-m-d', strtotime($payable_date . ' +1 month'));
                            $next_payable_amount = $payable_amount;
                            break;
                    }
                }

                // Update credit_history with new payment details and balance
                $update_credit_query = "UPDATE credit_history SET
                    PayableAmount = ?,
                    PayableDate = ?,
                    NextPayableAmount = ?,
                    NextPayableDate = ?,
                    TotalPayment = ?,
                    Balance = ?
                    WHERE LoanID = ?";

                $stmt_update_credit = $conn->prepare($update_credit_query);
                $stmt_update_credit->bind_param("dssdssi", 
                    $next_payable_amount,
                    $next_payable_date,
                    $next_payable_amount,
                    $next_payable_date,
                    $total_paid,
                    $balance,
                    $loan_id
                );

                if (!$stmt_update_credit->execute()) {
                    throw new Exception("Error updating credit history: " . $stmt_update_credit->error);
                }

                // If payment is completed and loan is fully paid, update status
                if ($payment_status === 'Completed' && $balance <= 0) {
                    $update_loan = "UPDATE credit_history SET Status = 'Completed' 
                                    WHERE LoanID = ?";
                    $stmt_update = $conn->prepare($update_loan);
                    $stmt_update->bind_param("i", $loan_id);

                    if (!$stmt_update->execute()) {
                        throw new Exception("Error updating loan status: " . $stmt_update->error);
                    }
                }
            }
        }

// Check if the payment is for Membership Payment
if ($service_name === 'Membership Payment') {
    error_log("Entered Membership Payment section");
    
    // Get user_id from the transaction (note: your table uses varchar(255))
    $user_query = "SELECT user_id FROM transactions WHERE transaction_id = ?";
    $stmt_user = $conn->prepare($user_query);
    $stmt_user->bind_param("i", $transaction_id);
    
    if (!$stmt_user->execute()) {
        error_log("User query error: " . $stmt_user->error);
        throw new Exception("Transaction query failed");
    }
    
    $user_result = $stmt_user->get_result();
    
    if ($user_result->num_rows === 0) {
        error_log("No user found for transaction ID: $transaction_id");
        throw new Exception("Transaction not found");
    }

    $user_row = $user_result->fetch_assoc();
    $user_id = $user_row['user_id'];
    error_log("Found user_id: $user_id");

    // Verify member application exists (table name is member_applications with an 's')
    $check_member_query = "SELECT id FROM member_applications WHERE user_id = ?";
    $stmt_check = $conn->prepare($check_member_query);
    $stmt_check->bind_param("s", $user_id); // Changed to "s" for varchar
    
    if (!$stmt_check->execute()) {
        error_log("Member check error: " . $stmt_check->error);
        throw new Exception("Member verification failed");
    }
    
    if ($stmt_check->get_result()->num_rows === 0) {
        error_log("No member_applications record for user_id: $user_id");
        throw new Exception("User not found in member applications");
    }

    // Set statuses based on your enum values
    $new_status = ($payment_status === 'Completed') ? 'Completed' : 'In Progress';
    $payment_status_value = ($payment_status === 'Completed') ? 'Completed' : 'In Progress';
    
    error_log("Attempting to update with status: $new_status, payment: $payment_status_value");

    // Update member_applications (note the table name change)
    $update_member_query = "UPDATE member_applications SET 
                          status = ?, 
                          payment_status = ?
                          WHERE user_id = ?";
    $stmt_member = $conn->prepare($update_member_query);
    $stmt_member->bind_param("sss", $new_status, $payment_status_value, $user_id); // All strings

    if (!$stmt_member->execute()) {
        error_log("Update error: " . $stmt_member->error);
        throw new Exception("Update failed: " . $stmt_member->error);
    }
    
    $affected = $conn->affected_rows;
    error_log("Update affected rows: $affected");

    if ($payment_status === 'Completed') {
        error_log("Processing completed payment...");
        
        // Generate certificate number
        $certificate_no = null;
        $attempts = 0;
        
        while ($attempts < 10) {
            $temp_cert = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            $check_query = "SELECT user_id FROM users WHERE certificate_no = ?";
            $stmt_check = $conn->prepare($check_query);
            $stmt_check->bind_param("s", $temp_cert);
            $stmt_check->execute();
            
            if ($stmt_check->get_result()->num_rows === 0) {
                $certificate_no = $temp_cert;
                break;
            }
            $attempts++;
        }
        
        if (!$certificate_no) {
            error_log("Failed to generate unique certificate after 10 attempts");
            throw new Exception("Certificate generation failed");
        }
        
        error_log("Generated certificate: $certificate_no");

        // Get savings and share_capital from appointments table
        $get_appointment_query = "SELECT savings, share_capital FROM appointments 
                                WHERE user_id = ? AND description = 'Membership Payment' 
                                ORDER BY id DESC LIMIT 1";
        $stmt_appointment = $conn->prepare($get_appointment_query);
        $stmt_appointment->bind_param("s", $user_id);
        
        if (!$stmt_appointment->execute()) {
            error_log("Appointment query error: " . $stmt_appointment->error);
            throw new Exception("Failed to fetch appointment details");
        }
        
        $appointment_result = $stmt_appointment->get_result();
        
        if ($appointment_result->num_rows === 0) {
            error_log("No appointment found for user_id: $user_id with Membership Payment");
            throw new Exception("Membership appointment details not found");
        }
        
        $appointment_row = $appointment_result->fetch_assoc();
        $savings = $appointment_row['savings'];
        $share_capital = $appointment_row['share_capital'];
        
        error_log("Fetched savings: $savings, share_capital: $share_capital");

        // Update users table with certificate number, membership status, savings and share_capital
        $update_user_query = "UPDATE users SET 
                            certificate_no = ?, 
                            membership_status = 'Active',
                            savings = ?,
                            share_capital = ?
                            WHERE user_id = ?";
        $stmt_user_update = $conn->prepare($update_user_query);
        $stmt_user_update->bind_param("sdds", 
            $certificate_no, 
            $savings,
            $share_capital,
            $user_id
        );

        if (!$stmt_user_update->execute()) {
            error_log("User update error: " . $stmt_user_update->error);
            throw new Exception("User update failed");
        }
        
        error_log("Successfully updated user with certificate, active status, savings and share capital");
    }
    
    error_log("Membership Payment section completed successfully");
}

try {
    if ($service_name === 'Savings Deposit' || $service_name === 'Savings Withdrawal') {
        echo "Matched service_name: $service_name<br>";

        // Map to correct savings type
        $savings_type = ($service_name === 'Savings Deposit') ? 'Deposit' : 'Withdrawal';
        echo "Mapped savings type: $savings_type<br>";

        // Get user_id, SavingsID, and amount from transactions
        $user_query = "SELECT user_id, SavingsID, amount FROM transactions WHERE transaction_id = ?";
        $stmt_user = $conn->prepare($user_query);
        if (!$stmt_user) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt_user->bind_param("i", $transaction_id);

        if (!$stmt_user->execute()) {
            throw new Exception("Failed to fetch user and SavingsID for savings update: " . $stmt_user->error);
        }

        $user_result = $stmt_user->get_result();
        if ($user_result->num_rows === 0) {
            throw new Exception("User or SavingsID not found for savings update");
        }

        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['user_id'];
        $savings_id = $user_row['SavingsID'];
        $amount = $user_row['amount'];

        echo "Fetched user_id: $user_id, savings_id: $savings_id, amount: $amount<br>";

        if (strtolower($payment_status) === 'completed') {
            // Update savings table status
            $update_savings_query = "UPDATE savings 
                                     SET Status = 'Approved' 
                                     WHERE SavingsID = ? AND MemberID = ? AND Type = ? AND Status != 'Approved'";
            $stmt_savings = $conn->prepare($update_savings_query);
            if (!$stmt_savings) {
                throw new Exception("Prepare update savings failed: " . $conn->error);
            }

            $stmt_savings->bind_param("iss", $savings_id, $user_id, $savings_type);

            if (!$stmt_savings->execute()) {
                throw new Exception("Failed to update savings status: " . $stmt_savings->error);
            }

            echo "Rows affected by savings update: " . $stmt_savings->affected_rows . "<br>";

            // Update user savings balance
            $update_user_savings_sql = ($savings_type === 'Deposit') ?
                "UPDATE users SET savings = savings + ? WHERE user_id = ?" :
                "UPDATE users SET savings = savings - ? WHERE user_id = ?";

            $stmt_user_savings = $conn->prepare($update_user_savings_sql);
            if (!$stmt_user_savings) {
                throw new Exception("Prepare update user savings failed: " . $conn->error);
            }

            $stmt_user_savings->bind_param("di", $amount, $user_id);

            if (!$stmt_user_savings->execute()) {
                throw new Exception("Failed to update user's savings balance: " . $stmt_user_savings->error);
            }

            echo "User savings updated successfully.<br>";

            // Get logged-in user ID from session
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Logged-in user ID not found in session");
            }
            $logged_in_user_id = $_SESSION['user_id'];

            // Fetch first and last name of logged-in user
            $fetch_names_sql = "SELECT first_name, last_name FROM users WHERE user_id = ?";
            $stmt_names = $conn->prepare($fetch_names_sql);
            if (!$stmt_names) {
                throw new Exception("Prepare fetch first and last name failed: " . $conn->error);
            }
            $stmt_names->bind_param("i", $logged_in_user_id);
            $stmt_names->execute();
            $result_names = $stmt_names->get_result();

            if ($result_names->num_rows > 0) {
                $row_names = $result_names->fetch_assoc();
                $first_name = $row_names['first_name'];
                $last_name = $row_names['last_name'];
                // Create initials (e.g., J.D.)
                $initials = strtoupper(substr($first_name, 0, 1)) . '.' . strtoupper(substr($last_name, 0, 1)) . '.';
            } else {
                throw new Exception("Logged-in user's first or last name not found");
            }

            // Update the signature column in transactions table with initials
            $update_signature_sql = "UPDATE transactions SET signature = ? WHERE transaction_id = ?";
            $stmt_signature = $conn->prepare($update_signature_sql);
            if (!$stmt_signature) {
                throw new Exception("Prepare update signature failed: " . $conn->error);
            }
            $stmt_signature->bind_param("si", $initials, $transaction_id);

            if (!$stmt_signature->execute()) {
                throw new Exception("Failed to update transaction signature: " . $stmt_signature->error);
            }

            echo "Transaction signature updated successfully.";
        } else {
            echo "Payment status is not 'completed' (received: '$payment_status')<br>";
        }
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}

try {
    if ($service_name === 'Share Capital Deposit') {
        echo "Matched service_name: $service_name<br>";

        // Get user_id, ShareCapitalID, and amount from transactions
        $user_query = "SELECT user_id, ShareCapitalID, amount FROM transactions WHERE transaction_id = ?";
        $stmt_user = $conn->prepare($user_query);
        if (!$stmt_user) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt_user->bind_param("i", $transaction_id);

        if (!$stmt_user->execute()) {
            throw new Exception("Failed to fetch user and ShareCapitalID: " . $stmt_user->error);
        }

        $user_result = $stmt_user->get_result();
        if ($user_result->num_rows === 0) {
            throw new Exception("User or ShareCapitalID not found");
        }

        $user_row = $user_result->fetch_assoc();
        $user_id = $user_row['user_id'];
        $share_capital_id = $user_row['ShareCapitalID'];
        $amount = $user_row['amount'];

        echo "Fetched user_id: $user_id, ShareCapitalID: $share_capital_id, amount: $amount<br>";

        if (strtolower($payment_status) === 'completed') {
            // Update ShareCapital status
            $update_capital_query = "UPDATE share_capital 
                                     SET Status = 'Approved' 
                                     WHERE ShareCapitalID = ? AND MemberID = ? AND Status != 'Approved'";
            $stmt_capital = $conn->prepare($update_capital_query);
            if (!$stmt_capital) {
                throw new Exception("Prepare update share capital failed: " . $conn->error);
            }

            $stmt_capital->bind_param("ii", $share_capital_id, $user_id);

            if (!$stmt_capital->execute()) {
                throw new Exception("Failed to update share capital status: " . $stmt_capital->error);
            }

            echo "Rows affected by share capital update: " . $stmt_capital->affected_rows . "<br>";

            // Update user's share capital balance
            $update_balance_sql = "UPDATE users SET share_capital = share_capital + ? WHERE user_id = ?";
            $stmt_balance = $conn->prepare($update_balance_sql);
            if (!$stmt_balance) {
                throw new Exception("Prepare update share capital balance failed: " . $conn->error);
            }

            $stmt_balance->bind_param("di", $amount, $user_id);

            if (!$stmt_balance->execute()) {
                throw new Exception("Failed to update user's share capital: " . $stmt_balance->error);
            }

            echo "User's share capital updated successfully.<br>";

            // Check if the user's share capital is 5000 or more and update membership_type
            $check_capital_sql = "SELECT share_capital FROM users WHERE user_id = ?";
            $stmt_check_capital = $conn->prepare($check_capital_sql);
            if (!$stmt_check_capital) {
                throw new Exception("Prepare check capital failed: " . $conn->error);
            }
            $stmt_check_capital->bind_param("i", $user_id);
            $stmt_check_capital->execute();
            $result_capital = $stmt_check_capital->get_result();

            if ($result_capital->num_rows > 0) {
                $row_capital = $result_capital->fetch_assoc();
                if ($row_capital['share_capital'] >= 5000) {
                    $update_type_sql = "UPDATE users SET membership_type = 'Regular' WHERE user_id = ?";
                    $stmt_type = $conn->prepare($update_type_sql);
                    if (!$stmt_type) {
                        throw new Exception("Prepare update membership type failed: " . $conn->error);
                    }
                    $stmt_type->bind_param("i", $user_id);
                    if (!$stmt_type->execute()) {
                        throw new Exception("Failed to update membership type: " . $stmt_type->error);
                    }
                    echo "Membership type updated to Regular.<br>";
                }
            }

            // Get initials of the logged-in user
            if (!isset($_SESSION['user_id'])) {
                throw new Exception("Logged-in user ID not found in session");
            }

            $logged_in_user_id = $_SESSION['user_id'];

            $fetch_names_sql = "SELECT first_name, last_name FROM users WHERE user_id = ?";
            $stmt_names = $conn->prepare($fetch_names_sql);
            if (!$stmt_names) {
                throw new Exception("Prepare fetch name failed: " . $conn->error);
            }
            $stmt_names->bind_param("i", $logged_in_user_id);
            $stmt_names->execute();
            $result_names = $stmt_names->get_result();

            if ($result_names->num_rows > 0) {
                $row_names = $result_names->fetch_assoc();
                $initials = strtoupper(substr($row_names['first_name'], 0, 1)) . '.' . strtoupper(substr($row_names['last_name'], 0, 1)) . '.';
            } else {
                throw new Exception("Name not found for logged-in user");
            }

            // Update signature in transaction
            $update_signature_sql = "UPDATE transactions SET signature = ? WHERE transaction_id = ?";
            $stmt_signature = $conn->prepare($update_signature_sql);
            if (!$stmt_signature) {
                throw new Exception("Prepare signature update failed: " . $conn->error);
            }

            $stmt_signature->bind_param("si", $initials, $transaction_id);

            if (!$stmt_signature->execute()) {
                throw new Exception("Failed to update transaction signature: " . $stmt_signature->error);
            }

            echo "Transaction signature updated successfully.";
        } else {
            echo "Payment status is not 'completed' (received: '$payment_status')<br>";
        }
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}



        $conn->commit();
        $_SESSION['success'] = "Transaction updated successfully.";
        header("Location: transaction.php?success=1");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
        header("Location: transaction.php?success=0");
        exit();
    }

} else {
    $_SESSION['error'] = "Invalid access method.";
    header("Location: transaction.php?success=0");
    exit();
}
?>
