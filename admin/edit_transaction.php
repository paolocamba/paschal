<?php
require_once '../connection/config.php';
session_start();

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
