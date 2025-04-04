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

        // Update loan status
        $status = $_POST['Status'];
        $loanable_amount = floatval($loan['loanable_amount']);
        
        $update_sql = "UPDATE loanapplication SET Status = ? WHERE LoanID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $status, $_POST['LoanID']);
        $update_stmt->execute();

        if ($status === 'Approved' || $status === 'Disapproved') {
            // Interest rate by loan type
            $interest_rate = ($loan['LoanType'] === 'Regular') ? 0.12 : 0.14;
        
            // Determine interval string for date math
            $interval_string = match (strtolower($loan['ModeOfPayment'])) {
                'weekly'       => '+7 days',
                'bi-monthly'   => '+15 days',
                'monthly'      => '+1 month',
                'quarterly'    => '+3 months',
                'semi-annual'  => '+6 months',
                default        => '+1 month'
            };
        
            // Calculate the Payable Date
            $payable_date = date('Y-m-d', strtotime($loan['DateOfLoan'] . ' ' . $interval_string));

            // Calculate the Maturity Date (Loan Term + Loan Date)
            $maturity_date = date('Y-m-d', strtotime($loan['DateOfLoan'] . ' + ' . $loan['LoanTerm'] . ' months'));

            // Determine number of payments
            $intervals_per_year = match (strtolower($loan['ModeOfPayment'])) {
                'weekly'       => 52,
                'bi-monthly'   => 24,
                'monthly'      => 12,
                'quarterly'    => 4,
                'semi-annual'  => 2,
                default        => 12
            };
        
            $loan_term_years = $loan['LoanTerm'] / 12;
            $total_payments = $intervals_per_year * $loan_term_years;
        
            // Calculate payment amounts
            $principal = $loan['AmountRequested'];
            $total_interest = $principal * $interest_rate * $loan_term_years;
            $balance = $principal + $total_interest;
            $payable_amount = $balance / $total_payments;

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

// Update the INSERT statement to match the number of parameters you're binding
$credit_sql = "INSERT INTO credit_history (
    LoanID, MemberID, AmountRequested, LoanTerm, LoanType,
    InterestRate, loanable_amount, ApprovalStatus, MemberIncome,
    LoanEligibility, ExistingLoans, CollateralValue, PayableAmount,
    PayableDate, Comaker,
    MaturityDate, ModeOfPayment, Balance, TotalPayable
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)  -- 19 parameters
ON DUPLICATE KEY UPDATE
    ApprovalStatus = VALUES(ApprovalStatus),
    loanable_amount = VALUES(loanable_amount),
    PayableAmount = VALUES(PayableAmount),
    PayableDate = VALUES(PayableDate),
    MaturityDate = VALUES(MaturityDate),
    Balance = VALUES(Balance),
    TotalPayable = VALUES(TotalPayable)";

$credit_stmt = $conn->prepare($credit_sql);
$credit_stmt->bind_param(
    "ssiisddssssddsssssd",  // Now 19 characters to match 19 parameters
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
    $payable_date,
    $loan['comaker_name'],
    $maturity_date,
    $loan['ModeOfPayment'],
    $balance,
    $balance // TotalPayable
);
            
            $credit_stmt->execute();
            
            // Get member email
            $email_sql = "SELECT u.email FROM users u
                          JOIN loanapplication l ON u.user_id = l.userID 
                          WHERE l.LoanID = ?";
            $email_stmt = $conn->prepare($email_sql);
            $email_stmt->bind_param("s", $_POST['LoanID']);
            $email_stmt->execute();
            $member_email = $email_stmt->get_result()->fetch_assoc()['email'];

            // Send email notification
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
                    <li>Total Payable (Principal + Interest): PHP " . number_format($balance, 2) . "</li>
                    <li>First Payment Due: " . date('F d, Y', strtotime($payable_date)) . "</li>
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
