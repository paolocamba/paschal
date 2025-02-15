<?php
// calculate_loan.php
require_once '../connection/config.php';
require_once 'ml_model.php';
use App\ML\LoanMLSystem;

if (!isset($_GET['LoanID'])) {
    echo json_encode([
        'success' => false,
        'error' => 'Loan ID is required'
    ]);
    exit;
}

try {
    $loanID = $_GET['LoanID'];
    
    // Get loan details
    $stmt = $conn->prepare("
        SELECT 
            l.*,
            u.membership_type,
            u.membership_status,
            COALESCE(s.Amount, 0) as Savings,
            COALESCE(sc.Amount, 0) as ShareCapital
        FROM loanapplication l
        JOIN users u ON l.userID = u.user_id
        LEFT JOIN savings s ON u.user_id = s.MemberID AND s.Status = 'Approved'
        LEFT JOIN share_capital sc ON u.user_id = sc.MemberID AND sc.Status = 'Approved'
        WHERE l.LoanID = ?
    ");
    
    $stmt->bind_param("s", $loanID);
    $stmt->execute();
    $result = $stmt->get_result();
    $loanData = $result->fetch_assoc();
    
    if (!$loanData) {
        throw new Exception("Loan not found");
    }
    
    // Calculate loanable amount and eligibility
    $totalFunds = $loanData['Savings'] + $loanData['ShareCapital'];
    $monthlyIncome = $loanData['monthly_income'] ?? 0;
    
    // Initialize variables
    $loanableAmount = 0;
    $interestRate = 0;
    $eligibility = "Not Eligible";
    $eligibilityReason = "";
    
    if ($loanData['LoanType'] === 'Regular') {
        // Regular loan calculations
        if ($totalFunds > 0 && $loanData['membership_status'] === 'Active') {
            $loanableAmount = $totalFunds * 0.90;
            $maxLoanBasedOnIncome = $monthlyIncome * 12;
            $loanableAmount = min($loanableAmount, $maxLoanBasedOnIncome);
            $interestRate = 12.0;
            $eligibility = "Eligible";
            $eligibilityReason = "Meets regular loan requirements";
        } else {
            $eligibilityReason = "Insufficient funds or inactive membership";
        }
    } else {
        // Collateral loan calculations
        $appraisal_stmt = $conn->prepare("
            SELECT total_value, final_zonal_value 
            FROM land_appraisal 
            WHERE LoanID = ?
        ");
        $appraisal_stmt->bind_param("s", $loanID);
        $appraisal_stmt->execute();
        $appraisalData = $appraisal_stmt->get_result()->fetch_assoc();
        
        if ($appraisalData && $appraisalData['total_value'] > 0) {
            $loanableAmount = $appraisalData['total_value'] * 0.50;
            $interestRate = 14.0;
            $eligibility = "Eligible";
            $eligibilityReason = "Collateral value meets requirements";
        } else {
            $eligibilityReason = "Invalid or insufficient collateral value";
        }
    }
    
    // Calculate payment details
    $monthlyPayment = ($loanableAmount * (1 + ($interestRate/100))) / 12;
    $totalPayment = $loanableAmount * (1 + ($interestRate/100));
    
    echo json_encode([
        'success' => true,
        'prediction' => [
            'predicted_amount' => $loanableAmount,
            'interest_rate' => $interestRate,
            'payable_amount' => $monthlyPayment,
            'total_payment' => $totalPayment
        ],
        'eligibility' => $eligibility,
        'eligibility_reason' => $eligibilityReason
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>