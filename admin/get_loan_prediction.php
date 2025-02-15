<?php
include '../connection/config.php';
require_once 'ml_model.php';
use App\ML\LoanMLSystem;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['LoanID'])) {
    try {
        // First, get all the loan information including approved amounts
        $loan_query = "
            SELECT 
                l.*,
                u.membership_type,
                u.membership_status,
                COALESCE(ch.loanable_amount, 0) as approved_loanable_amount,
                COALESCE(s.Amount, 0) as Savings,
                COALESCE(sc.Amount, 0) as ShareCapital,
                la.total_value as collateral_value,
                la.final_zonal_value,
                CASE 
                    WHEN l.LoanType = 'Regular' THEN 12
                    ELSE 14
                END as interest_rate
            FROM loanapplication l
            JOIN users u ON l.userID = u.user_id
            LEFT JOIN credit_history ch ON l.LoanID = ch.LoanID
            LEFT JOIN savings s ON u.user_id = s.MemberID AND s.Status = 'Approved'
            LEFT JOIN share_capital sc ON u.user_id = sc.MemberID AND sc.Status = 'Approved'
            LEFT JOIN land_appraisal la ON l.LoanID = la.LoanID
            WHERE l.LoanID = ?";

        $stmt = $conn->prepare($loan_query);
        $stmt->bind_param("s", $_POST['LoanID']);
        $stmt->execute();
        $loan_data = $stmt->get_result()->fetch_assoc();

        if (!$loan_data) {
            throw new Exception("Loan not found");
        }

        // Calculate loan amount based on type
        $loanable_amount = 0;
        $total_payment = 0;
        $periodic_payment = 0;

        if ($loan_data['Status'] === 'Approved') {
            // Use the approved amount for approved loans
            $loanable_amount = $loan_data['approved_loanable_amount'] > 0 ? 
                             $loan_data['approved_loanable_amount'] : 
                             $loan_data['AmountRequested'];
        } else {
            // Calculate new amount for non-approved loans
            if ($loan_data['LoanType'] === 'Regular') {
                $total_funds = $loan_data['Savings'] + $loan_data['ShareCapital'];
                $loanable_amount = $total_funds * 0.90;
            } else { // Collateral loan
                $loanable_amount = $loan_data['collateral_value'] ? 
                                 $loan_data['collateral_value'] * 0.50 : 
                                 $loan_data['AmountRequested'];
            }
        }

        // Calculate payments
        $interest_rate = $loan_data['interest_rate'] / 100;
        $payment_interval = match ($loan_data['ModeOfPayment']) {
            'Monthly' => 1,
            'Semi-Annual' => 6,
            'Annual' => 12,
            'Quarterly' => 3,
            default => 1
        };

        $loan_term_months = $loan_data['LoanTerm'] * 12;
        $number_of_payments = ceil($loan_term_months / $payment_interval);
        
        // Calculate total and periodic payments
        $total_payment = $loanable_amount * (1 + $interest_rate);
        $periodic_payment = $total_payment / $number_of_payments;

        // Determine eligibility
        $mlSystem = new LoanMLSystem($conn);
        $eligibility = $loan_data['Status'] === 'Approved' ? 
                      'Eligible' : 
                      $mlSystem->determineLoanEligibility($loan_data);

        // Prepare response
        $prediction = [
            'success' => true,
            'prediction' => [
                'predicted_amount' => round($loanable_amount, 2),
                'interest_rate' => $loan_data['interest_rate'],
                'periodic_payment' => round($periodic_payment, 2),
                'total_payment' => round($total_payment, 2),
                'payment_interval' => $payment_interval,
                'loan_eligibility' => $eligibility,
                'loan_type' => $loan_data['LoanType']
            ]
        ];

        if ($loan_data['LoanType'] === 'Collateral') {
            $prediction['prediction']['total_value'] = $loan_data['collateral_value'];
            $prediction['prediction']['zonal_value'] = $loan_data['final_zonal_value'];
        }

        echo json_encode($prediction);

    } catch (Exception $e) {
        error_log("Loan Prediction Error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    } finally {
        if (isset($stmt)) $stmt->close();
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request'
    ]);
}