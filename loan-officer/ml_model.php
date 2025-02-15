<?php
namespace App\ML;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../connection/config.php';

use mysqli;
use Exception;
use Phpml\Preprocessing\Normalizer;
use Phpml\Classification\Linear\LogisticRegression;
use Phpml\Regression\LeastSquares;

class LoanMLSystem
{
    private mysqli $conn;
    private Normalizer $normalizer;
    private LeastSquares $regularLoanModel;
    private LeastSquares $collateralLoanModel;
    private LogisticRegression $logisticModel;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->normalizer = new Normalizer(Normalizer::NORM_STD);
        $this->regularLoanModel = new LeastSquares();
        $this->collateralLoanModel = new LeastSquares();
        $this->logisticModel = new LogisticRegression();
    }

    public function prepareTrainingData(string $loanType): array
    {
        $sql = "SELECT 
                l.LoanID, l.userID, l.AmountRequested, l.LoanTerm, l.LoanType,
                l.ModeOfPayment, COALESCE(l.number_of_dependents, 0) as number_of_dependents, 
                COALESCE(l.self_income_amount, 0) as self_income_amount,
                COALESCE(l.self_other_income_amount, 0) as self_other_income_amount, 
                COALESCE(l.spouse_income_amount, 0) as spouse_income_amount, 
                COALESCE(l.spouse_other_income_amount, 0) as spouse_other_income_amount, 
                COALESCE(l.total_expenses, 0) as total_expenses,
                COALESCE(l.net_family_income, 0) as net_family_income, 
                COALESCE(l.monthly_income, 0) as monthly_income,
                COALESCE(m.Savings, 0) as Savings, 
                m.membership_type as TypeOfMember, 
                m.membership_status as MembershipStatus,
                COALESCE(sc.Amount, 0) as ShareCapital,
                ch.ApprovalStatus, ch.ExistingLoans";
                
        if ($loanType === 'Collateral') {
            $sql .= ", COALESCE(ci.square_meters, 0) as square_meters, 
                      ci.type_of_land, ci.location_name, 
                      COALESCE(ci.right_of_way, 0) as right_of_way,
                      COALESCE(ci.has_hospital, 0) as has_hospital, 
                      COALESCE(ci.has_clinic, 0) as has_clinic, 
                      COALESCE(ci.has_school, 0) as has_school,
                      COALESCE(ci.has_market, 0) as has_market, 
                      COALESCE(ci.has_church, 0) as has_church, 
                      COALESCE(ci.has_terminal, 0) as has_terminal,
                      COALESCE(la.final_zonal_value, 0) as final_zonal_value, 
                      COALESCE(la.EMV_per_sqm, 0) as EMV_per_sqm, 
                      COALESCE(la.total_value, 0) as total_value, 
                      COALESCE(la.loanable_amount, 0) as loanable_amount";
        }
                
        $sql .= " FROM loanapplication l
                  JOIN users m ON l.userID = m.user_id
                  LEFT JOIN share_capital sc ON m.user_id = sc.MemberID
                  LEFT JOIN credit_history ch ON l.LoanID = ch.LoanID";
                
        if ($loanType === 'Collateral') {
            $sql .= " LEFT JOIN collateral_info ci ON l.LoanID = ci.LoanID
                      LEFT JOIN land_appraisal la ON l.LoanID = la.LoanID";
        }
                
        $sql .= " WHERE l.LoanType = ? AND ch.ApprovalStatus IS NOT NULL";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $loanType);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $samples = [];
        $targets = [];
        
        while ($row = $result->fetch_assoc()) {
            $sample = [
                (float)$row['ShareCapital'],
                (float)$row['Savings'],
                (float)$row['self_income_amount'],
                (float)$row['net_family_income'],
                (float)$row['total_expenses'],
                (int)($row['TypeOfMember'] === 'Regular' ? 1 : 0),
                (int)$row['number_of_dependents'],
                (float)$row['monthly_income']
            ];
            
            if ($loanType === 'Collateral') {
                $sample = array_merge($sample, [
                    (float)$row['square_meters'],
                    (float)$row['final_zonal_value'],
                    (int)$row['right_of_way'],
                    (int)$row['has_hospital'],
                    (int)$row['has_clinic'],
                    (int)$row['has_school'],
                    (int)$row['has_market'],
                    (int)$row['has_church'],
                    (int)$row['has_terminal']
                ]);
                $targets[] = (float)$row['loanable_amount'];
            } else {
                $targets[] = (float)$row['AmountRequested'];
            }
            
            $samples[] = $sample;
        }
        
        if (empty($samples)) {
            throw new Exception("No training data available for $loanType loans");
        }
        
        $stmt->close();
        return [$samples, $targets];
    }

    public function trainModels(): void
    {
        try {
            [$regularSamples, $regularTargets] = $this->prepareTrainingData('Regular');
            if (!empty($regularSamples)) {
                $this->normalizer->fit($regularSamples);
                $this->normalizer->transform($regularSamples);
                $this->regularLoanModel->train($regularSamples, $regularTargets);
            }

            [$collateralSamples, $collateralTargets] = $this->prepareTrainingData('Collateral');
            if (!empty($collateralSamples)) {
                $this->normalizer->fit($collateralSamples);
                $this->normalizer->transform($collateralSamples);
                $this->collateralLoanModel->train($collateralSamples, $collateralTargets);
            }
        } catch (Exception $e) {
            error_log("Error training models: " . $e->getMessage());
            throw $e;
        }
    }
    private function calculateMonthlyPayment(array $loanData, float $loanableAmount): array
    {
        $interestRate = ($loanData['LoanType'] === 'Regular') ? 0.12 : 0.14;
        
        // Convert LoanTerm to number of payments based on ModeOfPayment
        $paymentInterval = match ($loanData['ModeOfPayment']) {
            'Monthly' => 1,
            'Semi-Annual' => 6,
            'Annual' => 12,
            default => 1
        };
        
        $numberOfPayments = ceil($loanData['LoanTerm'] / $paymentInterval);
        $totalPayment = $loanableAmount * (1 + $interestRate);
        $periodicPayment = $totalPayment / $numberOfPayments;
        
        return [
            'periodic_payment' => round($periodicPayment, 2),
            'total_payment' => round($totalPayment, 2),
            'interest_rate' => $interestRate * 100
        ];
    }

    public function calculateRegularLoanAmount(string $userID): array 
    {
        $stmt = $this->conn->prepare("
            SELECT 
                (SELECT COALESCE(SUM(Amount), 0) 
                 FROM savings 
                 WHERE MemberID = ? AND Status = 'Approved') as Savings,
                (SELECT COALESCE(SUM(Amount), 0) 
                 FROM share_capital 
                 WHERE MemberID = ? AND Status = 'Approved') as ShareCapital
        ");
        
        $stmt->bind_param("ss", $userID, $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $financialData = $result->fetch_assoc();
        
        $totalFunds = $financialData['Savings'] + $financialData['ShareCapital'];
        $loanableAmount = $totalFunds * 0.90;
        
        return [
            'predicted_amount' => $loanableAmount,
            'interest_rate' => 12.0,
            'total_funds' => $totalFunds
        ];
    }

    public function calculateCollateralLoanAmount(string $loanID): array 
    {
        $stmt = $this->conn->prepare("
            SELECT total_value, final_zonal_value 
            FROM land_appraisal 
            WHERE LoanID = ?
        ");
        
        $stmt->bind_param("s", $loanID);
        $stmt->execute();
        $result = $stmt->get_result();
        $appraisalData = $result->fetch_assoc();
        
        $totalValue = $appraisalData['total_value'] ?? 0;
        $loanableAmount = $totalValue * 0.50;
        
        return [
            'predicted_amount' => $loanableAmount,
            'interest_rate' => 14.0,
            'total_value' => $totalValue,
            'zonal_value' => $appraisalData['final_zonal_value'] ?? 0
        ];
    }

    public function predictLoanAmount(array $userData): array
    {
        $baseResult = ($userData['LoanType'] === 'Regular') ? 
            $this->calculateRegularLoanAmount($userData['userID']) :
            $this->calculateCollateralLoanAmount($userData['LoanID']);
        
        // Add payment calculations
        $paymentDetails = $this->calculateMonthlyPayment($userData, $baseResult['predicted_amount']);
        
        // Get eligibility
        $eligibility = $this->determineLoanEligibility($userData);
        
        return array_merge($baseResult, $paymentDetails, ['loan_eligibility' => $eligibility]);
    }
    public function generateAllDatasets(): void
    {
        try {
            if (!is_dir('dataset')) {
                mkdir('dataset', 0777, true);
            }
            
            $this->generateLoanApplicationCSV();
            $this->generateMemberDatasetCSV();
            $this->generateCollateralInfoCSV();
            $this->generateLandAppraisalCSV();
            $this->generateCreditHistoryCSV();
        } catch (Exception $e) {
            error_log("Error generating datasets: " . $e->getMessage());
            throw $e;
        }
    }
    public function determineLoanEligibility(array $userData): string 
{
    try {
        // Get credit history
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total_loans,
                   SUM(CASE WHEN Status = 'Approved' THEN 1 ELSE 0 END) as approved_loans,
                   SUM(CASE WHEN Status = 'Disapproved' THEN 1 ELSE 0 END) as disapproved_loans
            FROM loanapplication 
            WHERE userID = ? AND Status IN ('Approved', 'Disapproved')
        ");
        
        $stmt->bind_param("s", $userData['userID']);
        $stmt->execute();
        $credit_history = $stmt->get_result()->fetch_assoc();
        
        // Calculate debt-to-income ratio
        $monthly_income = floatval($userData['monthly_income']);
        $total_expenses = floatval($userData['total_expenses']);
        $debt_to_income = ($monthly_income > 0) ? ($total_expenses / $monthly_income) * 100 : 100;
        
        // Get savings and share capital
        $stmt = $this->conn->prepare("
            SELECT 
                COALESCE(SUM(s.Amount), 0) as total_savings,
                COALESCE(SUM(sc.Amount), 0) as total_share_capital
            FROM users u
            LEFT JOIN savings s ON u.user_id = s.MemberID AND s.Status = 'Approved'
            LEFT JOIN share_capital sc ON u.user_id = sc.MemberID AND sc.Status = 'Approved'
            WHERE u.user_id = ?
        ");
        
        $stmt->bind_param("s", $userData['userID']);
        $stmt->execute();
        $financial_data = $stmt->get_result()->fetch_assoc();
        
        // Eligibility criteria
        $is_eligible = true;
        $reasons = [];
        
        // Check membership type
        $membership_type = $userData['membership_type'] ?? '';
        if (!in_array($membership_type, ['Regular', 'Associate'])) {
            $is_eligible = false;
            $reasons[] = "Must be a regular or associate member";
        }
        
        // Check debt-to-income ratio (typically should be below 43%)
        if ($debt_to_income > 43) {
            $is_eligible = false;
            $reasons[] = "Debt-to-income ratio too high";
        }
        
        // Check if they have too many disapproved loans
        if ($credit_history['disapproved_loans'] > 2) {
            $is_eligible = false;
            $reasons[] = "Too many disapproved loan applications";
        }

        // Special checks for Collateral loans
        if ($userData['LoanType'] === 'Collateral') {
            $collateral_stmt = $this->conn->prepare("
                SELECT la.total_value, l.AmountRequested 
                FROM land_appraisal la 
                JOIN loanapplication l ON la.LoanID = l.LoanID 
                WHERE l.LoanID = ?
            ");
            
            $collateral_stmt->bind_param("s", $userData['LoanID']);
            $collateral_stmt->execute();
            $collateral_result = $collateral_stmt->get_result()->fetch_assoc();
            
            if ($collateral_result) {
                $loanable_amount = $collateral_result['total_value'] * 0.5;
                if ($collateral_result['AmountRequested'] > $loanable_amount) {
                    $reasons[] = "Loan amount exceeds maximum collateral percentage (50%)";
                }
            }
        }
        
        // Additional criteria for Regular loans
        if ($userData['LoanType'] === 'Regular') {
            $total_funds = $financial_data['total_savings'] + $financial_data['total_share_capital'];
            $loan_amount = floatval($userData['AmountRequested']);
            
            // Regular loans shouldn't exceed 90% of total funds
            if ($loan_amount > ($total_funds * 0.9)) {
                $is_eligible = false;
                $reasons[] = "Loan amount exceeds maximum savings percentage (90%)";
            }
        }
        
        return $is_eligible ? 'Eligible' : 'Not Eligible: ' . implode(', ', $reasons);
        
    } catch (Exception $e) {
        error_log("Error determining loan eligibility: " . $e->getMessage());
        throw $e;
    }
}
    
    private function generateCSV(string $sql, string $filename): void {
        try {
            $result = $this->conn->query($sql);
            if (!$result) {
                throw new Exception("Error executing query: " . $this->conn->error);
            }

            $dirPath = __DIR__ . '/dataset';
            if (!is_dir($dirPath)) {
                if (!mkdir($dirPath, 0777, true)) {
                    throw new Exception("Failed to create directory: $dirPath");
                }
            }

            $filePath = $dirPath . '/' . $filename;
            $file = @fopen($filePath, 'w');
            if (!$file) {
                throw new Exception("Could not open file: $filePath. Please check permissions.");
            }

            if ($result->num_rows > 0) {
                $headers = array_keys($result->fetch_assoc());
                fputcsv($file, $headers);
                
                $result->data_seek(0);
                while ($row = $result->fetch_assoc()) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
            $result->close();
        } catch (Exception $e) {
            error_log("Error generating CSV file {$filename}: " . $e->getMessage());
            throw $e;
        }
    }

    private function generateLoanApplicationCSV(): void
    {
        $sql = "SELECT 
                LoanID, userID AS MemberID, AmountRequested, LoanTerm, LoanType,
                ModeOfPayment, number_of_dependents, self_income_amount,
                self_other_income_amount, spouse_income_amount, 
                spouse_other_income_amount, total_expenses,
                net_family_income, monthly_income, Status, loanable_amount,
                DateOfLoan, comaker_name
                FROM loanapplication";
        
        $this->generateCSV($sql, 'loan_application_dataset.csv');
    }

    private function generateMemberDatasetCSV(): void
    {
        $sql = "SELECT 
                    u.user_id as MemberID,
                    COALESCE(s.Amount, 0) as Savings,
                    u.membership_type as TypeOfMember,
                    u.membership_status as MembershipStatus,
                    COALESCE(sc.Amount, 0) as ShareCapital
                FROM users u
                LEFT JOIN savings s ON u.user_id = s.MemberID
                LEFT JOIN share_capital sc ON u.user_id = sc.MemberID
                WHERE u.user_type = 'Member'";  // Filter to include only members
        
        $this->generateCSV($sql, 'member_dataset.csv');
    }


    private function generateCollateralInfoCSV(): void
    {
        $sql = "SELECT 
                LoanID,
                COALESCE(square_meters, 0) as square_meters,
                type_of_land,
                location_name,
                right_of_way,
                has_hospital,
                has_clinic,
                has_school,
                has_market,
                has_church,
                has_terminal
                FROM collateral_info";
        
        $this->generateCSV($sql, 'collateral_info_dataset.csv');
    }

    private function generateLandAppraisalCSV(): void
    {
        $sql = "SELECT 
                LoanID,
                COALESCE(final_zonal_value, 0) as final_zonal_value,
                COALESCE(EMV_per_sqm, 0) as EMV_per_sqm,
                COALESCE(total_value, 0) as total_value,
                COALESCE(loanable_amount, 0) as loanable_amount
                FROM land_appraisal";
        
        $this->generateCSV($sql, 'land_appraisal_dataset.csv');
    }

    private function generateCreditHistoryCSV(): void
    {
        $sql = "SELECT 
                ch.LoanID,
                l.userID as MemberID,
                ch.AmountRequested,
                ch.LoanTerm,
                ch.LoanType,
                ch.InterestRate,
                ch.loanable_amount,
                ch.ApprovalStatus,
                ch.MemberIncome,
                ch.LoanEligibility,
                ch.ExistingLoans,
                ch.CollateralValue,
                ch.PayableAmount,
                ch.PayableDate,
                ch.NextPayableAmount,
                ch.NextPayableDate,
                ch.Comaker,
                l.DateOfLoan as MaturityDate,
                ch.ModeOfPayment
                FROM credit_history ch
                JOIN loanapplication l ON ch.LoanID = l.LoanID";
        
        $this->generateCSV($sql, 'credit_history_dataset.csv');
    }
}

// Integration code
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['LoanID'])) {
    header('Content-Type: application/json');
    
    try {
        $mlSystem = new LoanMLSystem($conn);
        
        // Validate if training data exists first
        try {
            $mlSystem->trainModels();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'No training data available') !== false) {
                // If no training data, proceed with direct calculation without ML
                error_log("No training data available, proceeding with direct calculation");
            } else {
                // For other errors, throw the exception
                throw $e;
            }
        }
        
        $loan_stmt = $conn->prepare("SELECT * FROM loanapplication WHERE LoanID = ?");
        $loan_stmt->bind_param("s", $_POST['LoanID']);
        $loan_stmt->execute();
        $loan_data = $loan_stmt->get_result()->fetch_assoc();
        
        if (!$loan_data) {
            throw new Exception("Loan not found");
        }
        
        $loan_stmt->close();
        
        $member_stmt = $conn->prepare("SELECT u.*, s.Amount as Savings, sc.Amount as ShareCapital 
                                     FROM users u 
                                     LEFT JOIN savings s ON u.user_id = s.MemberID 
                                     LEFT JOIN share_capital sc ON u.user_id = sc.MemberID
                                     WHERE u.user_id = ?");
        $member_stmt->bind_param("s", $loan_data['userID']);
        $member_stmt->execute();
        $member_data = $member_stmt->get_result()->fetch_assoc();
        
        if (!$member_data) {
            throw new Exception("Member data not found");
        }
        
        $member_stmt->close();
        
        $userData = array_merge($loan_data, $member_data);
        
        if ($loan_data['LoanType'] === 'Collateral') {
            $collateral_stmt = $conn->prepare("SELECT * FROM collateral_info WHERE LoanID = ?");
            $collateral_stmt->bind_param("s", $_POST['LoanID']);
            $collateral_stmt->execute();
            $collateral_data = $collateral_stmt->get_result()->fetch_assoc();
            $collateral_stmt->close();
            
            if ($collateral_data) {
                $userData = array_merge($userData, $collateral_data);
            }
        }
        
        // Get the loan prediction
        $prediction = $mlSystem->predictLoanAmount($userData);
        
        // Get loan eligibility
        $eligibility = $mlSystem->determineLoanEligibility($userData);
        
        // Add eligibility to prediction array
        $prediction['loan_eligibility'] = $eligibility;
        
        echo json_encode([
            'success' => true,
            'prediction' => $prediction
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error: ' . $e->getMessage()
        ]);
    }
    exit; // Ensure no additional output
}

if (basename($_SERVER['PHP_SELF']) === 'loan.php') {
    try {
        $mlSystem = new LoanMLSystem($conn);
        $mlSystem->generateAllDatasets();
    } catch (Exception $e) {
        error_log('Error generating dataset: ' . $e->getMessage());
    }
}