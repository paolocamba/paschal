<?php
include '../connection/config.php';
include '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $conn->begin_transaction();

        // Get loan application data
        $loan_sql = "SELECT * FROM loanapplication WHERE LoanID = ?";
        $loan_stmt = $conn->prepare($loan_sql);
        $loan_stmt->bind_param("s", $_POST['LoanID']);
        $loan_stmt->execute();
        $loan = $loan_stmt->get_result()->fetch_assoc();

        if (!$loan) {
            throw new Exception("Loan application not found");
        }

        // Update loan status and amount from form submission
        $status = $_POST['Status'];
        $loanable_amount = floatval($_POST['loanable_amount']);
        
        $update_sql = "UPDATE loanapplication SET Status = ?, loanable_amount = ? WHERE LoanID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sds", $status, $loanable_amount, $_POST['LoanID']);
        $update_stmt->execute();

        if ($status === 'Approved' || $status === 'Disapproved') {
            // Calculate payment details
            $interest_rate = ($loan['LoanType'] === 'Regular') ? 0.12 : 0.14;
            $interval = match ($loan['ModeOfPayment']) {
                'Monthly' => 1,
                'Semi-Annual' => 6,
                default => 12
            };

            $total_payments = $loan['LoanTerm'] / $interval;
            $payable_amount = ($loanable_amount * (1 + $interest_rate)) / $total_payments;

            $maturity_date = date('Y-m-d', strtotime($loan['DateOfLoan'] . ' + ' . $loan['LoanTerm'] . ' months'));
            $next_payment_date = date('Y-m-d', strtotime($loan['DateOfLoan'] . ' + ' . $interval . ' months'));

            // Get collateral value if applicable
            $collateral_value = null;
            if ($loan['LoanType'] === 'Collateral') {
                $collateral_sql = "SELECT total_value FROM land_appraisal WHERE LoanID = ?";
                $collateral_stmt = $conn->prepare($collateral_sql);
                $collateral_stmt->bind_param("s", $_POST['LoanID']);
                $collateral_stmt->execute();
                $collateral_result = $collateral_stmt->get_result()->fetch_assoc();
                $collateral_value = $collateral_result['total_value'] ?? null;
            }

            $existing_loans = ($status === 'Completed') ? 'Yes' : 'No';
            $loan_eligibility = ($status === 'Approved') ? 'Eligible' : 'Not Eligible';

            // Update credit history
            $credit_sql = "INSERT INTO credit_history (
                LoanID, MemberID, AmountRequested, LoanTerm, LoanType,
                InterestRate, loanable_amount, ApprovalStatus, MemberIncome,
                LoanEligibility, ExistingLoans, CollateralValue, PayableAmount,
                PayableDate, NextPayableAmount, NextPayableDate, Comaker,
                MaturityDate, ModeOfPayment
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                ApprovalStatus = VALUES(ApprovalStatus),
                loanable_amount = VALUES(loanable_amount),
                PayableAmount = VALUES(PayableAmount),
                NextPayableAmount = VALUES(NextPayableAmount),
                NextPayableDate = VALUES(NextPayableDate),
                MaturityDate = VALUES(MaturityDate)";

            $credit_stmt = $conn->prepare($credit_sql);
            $credit_stmt->bind_param(
                "ssiisddssssddssssss",
                $_POST['LoanID'],
                $loan['userID'],
                $loan['AmountRequested'],
                $loan['LoanTerm'],
                $loan['LoanType'],
                $interest_rate,
                $loanable_amount,
                $status,
                $loan['net_family_income'],
                $loan_eligibility,
                $existing_loans,
                $collateral_value,
                $payable_amount,
                $loan['DateOfLoan'],
                $payable_amount,
                $next_payment_date,
                $loan['comaker_name'],
                $maturity_date,
                $loan['ModeOfPayment']
            );
            $credit_stmt->execute();

            // Send email notification
            $email_sql = "SELECT u.email FROM users u
                         JOIN loanapplication l ON u.user_id = l.userID 
                         WHERE l.LoanID = ?";
            $email_stmt = $conn->prepare($email_sql);
            $email_stmt->bind_param("s", $_POST['LoanID']);
            $email_stmt->execute();
            $member_email = $email_stmt->get_result()->fetch_assoc()['email'];

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'paschalmultipurposecooperative@gmail.com';
            $mail->Password = 'shga dxjh acgz qwfq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('paschalmultipurposecooperative@gmail.com', 'Paschal Multipurpose Cooperative');
            $mail->addAddress($member_email);
            $mail->isHTML(true);

            $status_message = $status === 'Approved' ? 'approved' : 'disapproved';
            $mail->Subject = "Loan Application {$status_message}";
            
            $mail->Body = "
                <h2>Loan Application Status Update</h2>
                <p>Your loan application has been {$status_message}.</p>
                <p><strong>Details:</strong></p>
                <ul>
                    <li>Loan Type: {$loan['LoanType']}</li>
                    <li>Loanable Amount: PHP " . number_format($loanable_amount, 2) . "</li>
                    " . ($status === 'Approved' ? "
                    <li>Interest Rate: " . ($interest_rate * 100) . "%</li>
                    <li>Monthly Payment: PHP " . number_format($payable_amount, 2) . "</li>
                    <li>First Payment Due: " . date('F d, Y', strtotime($next_payment_date)) . "</li>
                    <li>Maturity Date: " . date('F d, Y', strtotime($maturity_date)) . "</li>
                    " : "") . "
                </ul>";

            $mail->send();
        }

        $conn->commit();
        header("Location: loans.php?success=1");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        error_log('Error updating loan: ' . $e->getMessage());
        header("Location: loans.php");
        exit();
    }
} else {
    header("Location: loans.php");
    exit();
}
?>