<?php
use Mpdf\Mpdf;
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../connection/config.php';

// Fetch the user's data from the "users" table based on the user ID
$id = $_SESSION['user_id'];
$sql = "SELECT id, username, first_name, last_name, mobile, email, street, barangay, municipality, province, uploadID, is_logged_in, savings, share_capital FROM users WHERE user_id = '$id'";
$result = $conn->query($sql);

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row["username"];
    $uploadID = $row["uploadID"];
    $first_name = $row["first_name"];
    $last_name = $row["last_name"];
    $mobile = $row["mobile"];
    $email = $row["email"];
    $street = $row["street"];
    $barangay = $row["barangay"];
    $municipality = $row["municipality"];
    $province = $row["province"];
    $user_db_id = $row["id"];
    $current_savings = $row["savings"];
    $current_share_capital = $row["share_capital"];
} else {
    // Default values if user data is not found
    $username = "Guest";
    $uploadID = "default_image.jpg";
    $first_name = "";
    $last_name = "";
    $mobile = "";
    $email = "";
    $street = "";
    $barangay = "";
    $municipality = "";
    $province = "";
    $user_db_id = 0;
    $current_savings = 0;
    $current_share_capital = 0;
}

// Assign the value of $username and $image to $_SESSION variables
$_SESSION['username'] = $name;
$_SESSION['uploadID'] = $uploadID;
$_SESSION['is_logged_in'] = $row['is_logged_in'];
$_SESSION['user_db_id'] = $user_db_id;

// Handle report generation requests
if (isset($_GET['report'])) {
    require '../vendor/autoload.php';

    
    try {
        $mpdf = new Mpdf(['format' => 'A4']);
        $report_type = $_GET['report'];
         $date_from_raw = $_GET['date_from'] ?? null;
        $date_to_raw = $_GET['date_to'] ?? null;

        // Format dates to YYYY-MM-DD
        $date_from = $date_from_raw ? date('Y-m-d', strtotime($date_from_raw)) : null;
        $date_to = $date_to_raw ? date('Y-m-d', strtotime($date_to_raw)) : null;

        // Get user DB ID from session or elsewhere
        $user_db_id = $_SESSION['user_db_id'] ?? null;

        if (!$user_db_id) {
            throw new Exception("User not found in session.");
        }

        // Fetch the user_id string based on the DB id
        $stmt = $conn->prepare("SELECT user_id, first_name, last_name FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_db_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            throw new Exception("User not found.");
        }

        $user_id = $user['user_id'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];

        // Generate appropriate report
        switch ($report_type) {
        case 'transaction_history':
            generateTransactionHistoryReport($mpdf, $user_id, $date_from, $date_to, $conn);
            break;
        case 'savings_summary':
            generateSavingsSummaryReport($mpdf, $user_db_id, $user_id, $date_from, $date_to, $conn, $current_savings);
            break;
        case 'share_capital':
            generateShareCapitalReport($mpdf, $user_id, $date_from, $date_to, $conn, $current_share_capital);
            break;    ;
            default:
                throw new Exception('Invalid report type');
        }

    

        $filename = $first_name . '_' . $last_name . '_' . ucwords(str_replace('_', ' ', $report_type)) . '.pdf';
        $mpdf->Output($filename, 'D');
        exit;

    } catch (Exception $e) {
        echo "<script>alert('Error generating report: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// Report Generation Functions
function generateTransactionHistoryReport($mpdf, $user_id, $date_from, $date_to, $conn) {
    $where_clause = "WHERE t.user_id = $user_id";

    if (!empty($date_from)) {
        $where_clause .= " AND DATE(t.updated_at) >= '$date_from'";
    }

    if (!empty($date_to)) {
        $where_clause .= " AND DATE(t.updated_at) <= '$date_to'";
    }

    $sql = "SELECT 
        t.updated_at as date,
        t.service_name as service,
        t.amount,
        t.control_number,
        t.payment_status
    FROM transactions t
    $where_clause
    ORDER BY t.updated_at DESC";

    $result = $conn->query($sql);
    $transactions = [];
    $total_amount = 0;

    if ($result) {
        while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;

    // Check if it's a withdrawal and subtract, otherwise add
    if (strtolower(trim($row['service'])) === 'savings withdrawal') {
        $total_amount -= $row['amount'];
    } else {
        $total_amount += $row['amount'];
    }
}
    }

    $user_info = getUserInfo($user_id, $conn);

    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12pt; }
            .container { width: 100%; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .report-title { font-size: 18pt; font-weight: bold; margin-bottom: 10px; }
            .report-period { margin-bottom: 20px; }
            .member-info { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .footer { margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="../dist/assets/images/pmpc-logo.png" alt="PMPC Logo" style="max-width: 150px;">
                <div class="report-title">Transaction History Report</div>
                <div class="report-period">' . 
                (!empty($date_from) || !empty($date_to) ? 
                'Period: ' . (!empty($date_from) ? $date_from : 'Start') . ' to ' . (!empty($date_to) ? $date_to : 'End') : 
                'All Transactions' 
                ) . '</div>
            </div>

            <div class="member-info">
                <div><strong>Member:</strong> ' . htmlspecialchars($user_info['first_name'] . ' ' . $user_info['last_name']) . '</div>
                <div><strong>Member ID:</strong> ' . htmlspecialchars($user_info['user_id']) . '</div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Service</th>
                        <th class="text-right">Amount</th>
                        <th>Control No.</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($transactions as $transaction) {
        $html .= '
                    <tr>
                        <td>' . date("F d, Y h:ia", strtotime($transaction['date'])) . '</td>
                        <td>' . htmlspecialchars($transaction['service']) . '</td>
                        <td class="text-right">' . 
                            (strtolower(trim($transaction['service'])) === 'savings withdrawal' ? 
                            '-₱' . number_format($transaction['amount'], 2) : 
                            '₱' . number_format($transaction['amount'], 2)) . 
                        '</td>
                        <td>' . htmlspecialchars($transaction['control_number']) . '</td>
                        <td>' . htmlspecialchars($transaction['payment_status']) . '</td>
                    </tr>';
    }

    // Total row
    $html .= '
                    <tr>
                        <td colspan="2"><strong>Total</strong></td>
                        <td class="text-right"><strong>₱' . number_format($total_amount, 2) . '</strong></td>
                        <td colspan="2"></td>
                    </tr>';

    $html .= '
                </tbody>
            </table>

            <div class="footer">
                <div>Generated on: ' . date('Y-m-d H:i:s') . '</div>
            </div>
        </div>
    </body>
    </html>';

    $mpdf->WriteHTML($html);
}

function generateSavingsSummaryReport($mpdf, $member_id, $user_id_str, $date_from, $date_to, $conn, $current_savings) {

    $where_clause = "WHERE s.MemberID = $member_id";
    if (!empty($date_from)) {
        $where_clause .= " AND DATE(s.TransactionDate) >= '$date_from'";
    }
    if (!empty($date_to)) {
        $where_clause .= " AND DATE(s.TransactionDate) <= '$date_to'";
    }

    // Get summary data
    $summary_sql = "SELECT 
                    SUM(CASE WHEN s.Type = 'Deposit' THEN s.Amount ELSE 0 END) as total_deposits,
                    SUM(CASE WHEN s.Type = 'Withdrawal' THEN s.Amount ELSE 0 END) as total_withdrawals,
                    COUNT(*) as transaction_count
                FROM savings s
                $where_clause";
    $summary_result = $conn->query($summary_sql);
    $summary = $summary_result->fetch_assoc();

    // Get transaction details
    $transactions_sql = "SELECT 
                        s.Amount,
                        s.Type,
                        s.TransactionDate as date,
                        s.Notes,
                        s.Status
                    FROM savings s
                    $where_clause
                    ORDER BY s.TransactionDate DESC";

    $transactions_result = $conn->query($transactions_sql);
    $transactions = [];
    while ($row = $transactions_result->fetch_assoc()) {
        $transactions[] = $row;
    }

    $user_info = getUserInfo($member_id, $conn);

    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12pt; }
            .container { width: 100%; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .report-title { font-size: 18pt; font-weight: bold; margin-bottom: 10px; }
            .report-period { margin-bottom: 20px; }
            .member-info { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .summary-box { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9; }
            .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
            .summary-label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="../dist/assets/images/pmpc-logo.png" alt="PMPC Logo" style="max-width: 150px;">
                <div class="report-title">Savings Summary Report</div>
                <div class="report-period">' . 
                (!empty($date_from) || !empty($date_to) ? 
                'Period: ' . (!empty($date_from) ? htmlspecialchars($date_from) : 'Start') . ' to ' . (!empty($date_to) ? htmlspecialchars($date_to) : 'End') : 
                'All Transactions') . '</div>
            </div>

            <div class="member-info">
                <div><strong>Member:</strong> ' . htmlspecialchars($user_info['first_name'] . ' ' . $user_info['last_name']) . '</div>
                <div><strong>Member ID:</strong> ' . htmlspecialchars($user_info['user_id']) . '</div>
            </div>

            <div class="summary-box">
                <div class="summary-row">
                    <span class="summary-label">Current Savings Balance:</span>
                    <span>₱' . number_format($current_savings, 2) . '</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Deposits:</span>
                    <span>₱' . number_format($summary['total_deposits'], 2) . '</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Withdrawals:</span>
                    <span>₱' . number_format($summary['total_withdrawals'], 2) . '</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Number of Transactions:</span>
                    <span>' . number_format($summary['transaction_count']) . '</span>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-right">Amount</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($transactions as $transaction) {
        $formatted_date = date("F d, Y h:ia", strtotime($transaction['date']));
        $html .= '
                    <tr>
                        <td>' . $formatted_date . '</td>
                        <td>' . htmlspecialchars($transaction['Type']) . '</td>
                        <td class="text-right">₱' . number_format($transaction['Amount'], 2) . '</td>
                        <td>' . htmlspecialchars($transaction['Notes']) . '</td>
                        <td>' . htmlspecialchars($transaction['Status']) . '</td>
                    </tr>';
    }

    $html .= '
                </tbody>
            </table>

            <div class="footer">
                <div>Generated on: ' . date('F d, Y h:ia') . '</div>
            </div>
        </div>
    </body>
    </html>';

    $mpdf->WriteHTML($html);
}


function generateShareCapitalReport($mpdf, $user_id, $date_from, $date_to, $conn, $current_share_capital) {
    $where_clause = "WHERE sc.MemberID = '$user_id'";
    if (!empty($date_from)) {
        $where_clause .= " AND DATE(sc.TransactionDate) >= '$date_from'";
    }
    if (!empty($date_to)) {
        $where_clause .= " AND DATE(sc.TransactionDate) <= '$date_to'";
    }
    
    // Get summary data
    $summary_sql = "SELECT 
                    SUM(CASE WHEN sc.Type = 'Deposit' THEN sc.Amount ELSE 0 END) as total_contributions,
                    COUNT(*) as transaction_count
                FROM share_capital sc
                $where_clause";
    
    $summary_result = $conn->query($summary_sql);
    $summary = $summary_result->fetch_assoc();
    
    // Get transaction details
    $transactions_sql = "SELECT 
                        sc.Amount,
                        sc.Type,
                        sc.TransactionDate as date,
                        sc.Notes,
                        sc.Status
                    FROM share_capital sc
                    $where_clause
                    ORDER BY sc.TransactionDate DESC";
    
    $transactions_result = $conn->query($transactions_sql);
    $transactions = [];
    while ($row = $transactions_result->fetch_assoc()) {
        $transactions[] = $row;
    }
    
    $user_info = getUserInfo($user_id, $conn);
    
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12pt; }
            .container { width: 100%; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .report-title { font-size: 18pt; font-weight: bold; margin-bottom: 10px; }
            .report-period { margin-bottom: 20px; }
            .member-info { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .summary-box { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9; }
            .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
            .summary-label { font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="../dist/assets/images/pmpc-logo.png" alt="PMPC Logo" style="max-width: 150px;">
                <div class="report-title">Share Capital Report</div>
                <div class="report-period">' . 
                (!empty($date_from) || !empty($date_to) ? 
                'Period: ' . (!empty($date_from) ? $date_from : 'Start') . ' to ' . (!empty($date_to) ? $date_to : 'End') : 
                'All Transactions' 
                ) . '</div>
            </div>
            
            <div class="member-info">
                <div><strong>Member:</strong> ' . htmlspecialchars($user_info['first_name'] . ' ' . $user_info['last_name']) . '</div>
                <div><strong>Member ID:</strong> ' . htmlspecialchars($user_info['user_id']) . '</div>
            </div>
            
            <div class="summary-box">
                <div class="summary-row">
                    <span class="summary-label">Current Share Capital Balance:</span>
                    <span>₱' . number_format($current_share_capital, 2) . '</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Total Contributions:</span>
                    <span>₱' . number_format($summary['total_contributions'], 2) . '</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Number of Transactions:</span>
                    <span>' . $summary['transaction_count'] . '</span>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-right">Amount</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
    
    foreach ($transactions as $transaction) {
        $html .= '
                    <tr>
                        <td>' . htmlspecialchars($transaction['date']) . '</td>
                        <td>' . htmlspecialchars($transaction['Type']) . '</td>
                        <td class="text-right">₱' . number_format($transaction['Amount'], 2) . '</td>
                        <td>' . htmlspecialchars($transaction['Notes']) . '</td>
                        <td>' . htmlspecialchars($transaction['Status']) . '</td>
                    </tr>';
    }
    
    $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <div>Generated on: ' . date('Y-m-d H:i:s') . '</div>
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
}

function generateLoanHistoryReport($mpdf, $user_id, $date_from, $date_to, $conn) {
    $where_clause = "WHERE ch.MemberID = $user_id";
    if (!empty($date_from)) {
        $where_clause .= " AND DATE(ch.created_at) >= '$date_from'";
    }
    if (!empty($date_to)) {
        $where_clause .= " AND DATE(ch.created_at) <= '$date_to'";
    }
    
    // Get loan summary
    $loans_sql = "SELECT 
                    ch.LoanID,
                    ch.LoanType,
                    ch.AmountRequested,
                    ch.InterestRate,
                    ch.LoanTerm,
                    ch.TotalPayable,
                    ch.ApprovalStatus,
                    ch.Status as loan_status,
                    ch.Balance,
                    ch.MaturityDate,
                    SUM(CASE WHEN lp.payment_status = 'Completed' THEN lp.amount_paid ELSE 0 END) as total_paid
                FROM credit_history ch
                LEFT JOIN loan_payments lp ON ch.LoanID = lp.LoanID
                $where_clause
                GROUP BY ch.LoanID
                ORDER BY ch.created_at DESC";
    
    $loans_result = $conn->query($loans_sql);
    $loans = [];
    while ($row = $loans_result->fetch_assoc()) {
        $loans[] = $row;
    }
    
    $user_info = getUserInfo($user_id, $conn);
    
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12pt; }
            .container { width: 100%; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .report-title { font-size: 18pt; font-weight: bold; margin-bottom: 10px; }
            .report-period { margin-bottom: 20px; }
            .member-info { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .loan-summary { margin-bottom: 30px; }
            .payment-details { margin-top: 30px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="../dist/assets/images/pmpc-logo.png" alt="PMPC Logo" style="max-width: 150px;">
                <div class="report-title">Loan History Report</div>
                <div class="report-period">' . 
                (!empty($date_from) || !empty($date_to) ? 
                'Period: ' . (!empty($date_from) ? $date_from : 'Start') . ' to ' . (!empty($date_to) ? $date_to : 'End') : 
                'All Loans' 
                ) . '</div>
            </div>
            
            <div class="member-info">
                <div><strong>Member:</strong> ' . htmlspecialchars($user_info['first_name'] . ' ' . $user_info['last_name']) . '</div>
                <div><strong>Member ID:</strong> ' . htmlspecialchars($user_info['user_id']) . '</div>
            </div>';
    
    foreach ($loans as $loan) {
        $html .= '
            <div class="loan-summary">
                <h3>Loan #' . $loan['LoanID'] . ' - ' . htmlspecialchars($loan['LoanType']) . '</h3>
                <table>
                    <tr>
                        <td width="30%"><strong>Amount Requested:</strong></td>
                        <td>₱' . number_format($loan['AmountRequested'], 2) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Interest Rate:</strong></td>
                        <td>' . $loan['InterestRate'] . '%</td>
                    </tr>
                    <tr>
                        <td><strong>Loan Term:</strong></td>
                        <td>' . $loan['LoanTerm'] . ' months</td>
                    </tr>
                    <tr>
                        <td><strong>Total Payable:</strong></td>
                        <td>₱' . number_format($loan['TotalPayable'], 2) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Amount Paid:</strong></td>
                        <td>₱' . number_format($loan['total_paid'], 2) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Outstanding Balance:</strong></td>
                        <td>₱' . number_format($loan['Balance'], 2) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>' . htmlspecialchars($loan['loan_status']) . '</td>
                    </tr>
                    <tr>
                        <td><strong>Maturity Date:</strong></td>
                        <td>' . htmlspecialchars($loan['MaturityDate']) . '</td>
                    </tr>
                </table>
            </div>';
        
        // Get payment details for this loan
        $payments_sql = "SELECT 
                            lp.payment_date,
                            lp.amount_paid,
                            lp.payment_status,
                            lp.receipt_number,
                            lp.is_late,
                            lp.days_late
                        FROM loan_payments lp
                        WHERE lp.LoanID = " . $loan['LoanID'] . "
                        ORDER BY lp.payment_date DESC";
        
        $payments_result = $conn->query($payments_sql);
        if ($payments_result->num_rows > 0) {
            $html .= '
            <div class="payment-details">
                <h4>Payment History</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th class="text-right">Amount Paid</th>
                            <th>Receipt #</th>
                            <th>Status</th>
                            <th>Late Payment</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            while ($payment = $payments_result->fetch_assoc()) {
                $html .= '
                        <tr>
                            <td>' . htmlspecialchars($payment['payment_date']) . '</td>
                            <td class="text-right">₱' . number_format($payment['amount_paid'], 2) . '</td>
                            <td>' . htmlspecialchars($payment['receipt_number']) . '</td>
                            <td>' . htmlspecialchars($payment['payment_status']) . '</td>
                            <td>' . ($payment['is_late'] ? 'Yes (' . $payment['days_late'] . ' days)' : 'No') . '</td>
                        </tr>';
            }
            
            $html .= '
                    </tbody>
                </table>
            </div>';
        }
    }
    
    $html .= '
            <div class="footer">
                <div>Generated on: ' . date('Y-m-d H:i:s') . '</div>
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
}

function getUserInfo($user_id, $conn) {
    $sql = "SELECT user_id, first_name, last_name FROM users WHERE id = $user_id";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reports | Member</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../dist/assets/vendors/feather/feather.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="../dist/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="../dist/assets/vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/mdi/css/materialdesignicons.min.css">
    <!--FONTAWESOME CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../dist/assets/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="../dist/assets/js/select.dataTables.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="../dist/assets/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="../dist/assets/images/ha.png" />
    <style>
        .card {
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .date-filter {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .report-section {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo me-5" href="index.php"><img src="../dist/assets/images/logo.png"
                        class="me-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.php"><img src="../dist/assets/images/pmpc-logo.png"
                        alt="logo" /></a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="icon-menu"></span>
                </button>
                <ul class="navbar-nav mr-lg-2">
                </ul>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
                            <img src="../dist/assets/images/user/<?php echo $_SESSION['uploadID']; ?>" alt="profile" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="settings.php">
                                <i class="ti-settings text-primary"></i> Settings </a>
                            <a class="dropdown-item" href="logout.php">
                                <i class="ti-power-off text-primary"></i> Logout </a>
                        </div>
                    </li>
                    <li class="nav-item nav-settings d-none d-lg-flex">
                        <a class="nav-link" href="#">
                            <i class="icon-ellipsis"></i>
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="icon-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <style>
                .nav-link i {
                    margin-right: 10px;
                }

                .btn-primary {
                    background-color: #03C03C !important;
                }

                .btn-primary:hover {
                    background-color: #00563B !important;
                }

                .btn-outline-primary:hover {
                    background-color: #00563B !important;
                }

                .page-item.active .page-link {
                    background-color: #00563B !important;
                    border-color: #00563B !important;
                }
                        .navbar {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
            </style>
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fa-solid fa-house"></i>
                        <span class="menu-title">Home</span>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="home.php">
                        <i class="fa-solid fa-gauge"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">
                            <i class="fa-brands fa-slack"></i>
                            <span class="menu-title">Services</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="appointments.php">
                        <i class="fa-solid fa-calendar"></i>
                        <span class="menu-title">Appointments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inbox.php">
                            <i class="fa-solid fa-comment"></i>
                            <span class="menu-title">Inbox</span>
                        </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="reports.php">
                        <i class="fa-solid fa-file-lines"></i>
                        <span class="menu-title">Reports</span>
                    </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-gear"></i>
                            <span class="menu-title">Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="font-weight-bold mb-0">Reports</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date Filter Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="date-filter">
                                <form id="reportFilterForm" method="get" class="form-inline">
                                    <input type="hidden" name="report" id="reportType">
                                    <div class="form-group mr-3">
                                        <label for="date_from" class="mr-2">From:</label>
                                                                                <input type="date" class="form-control" id="date_from" name="date_from">
                                    </div>
                                    <div class="form-group mr-3">
                                        <label for="date_to" class="mr-2">To:</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to">
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="display: none;" id="submitBtn">Generate Report</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Report Cards -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card" onclick="generateReport('transaction_history')">
                                <div class="card-body text-center">
                                    <div class="card-icon text-primary">
                                        <i class="fas fa-exchange-alt"></i>
                                    </div>
                                    <h4 class="card-title">Transaction History</h4>
                                    <p class="card-text">View all your financial transactions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" onclick="generateReport('savings_summary')">
                                <div class="card-body text-center">
                                    <div class="card-icon text-success">
                                        <i class="fas fa-piggy-bank"></i>
                                    </div>
                                    <h4 class="card-title">Savings Summary</h4>
                                    <p class="card-text">View your savings deposits and withdrawals</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" onclick="generateReport('share_capital')">
                                <div class="card-body text-center">
                                    <div class="card-icon text-info">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                    <h4 class="card-title">Share Capital</h4>
                                    <p class="card-text">View your share capital contributions</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card" onclick="generateReport('loan_history')">
                                <div class="card-body text-center">
                                    <div class="card-icon text-warning">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <h4 class="card-title">Loan History</h4>
                                    <p class="card-text">View your loan applications and payments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2023. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">PMPC-MIS</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- plugins:js -->
    <script src="../dist/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../dist/assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../dist/assets/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="../dist/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="../dist/assets/js/dataTables.select.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../dist/assets/js/off-canvas.js"></script>
    <script src="../dist/assets/js/hoverable-collapse.js"></script>
    <script src="../dist/assets/js/template.js"></script>
    <script src="../dist/assets/js/settings.js"></script>
    <script src="../dist/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="../dist/assets/js/dashboard.js"></script>
    <script src="../dist/assets/js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->

    <script>
        function generateReport(reportType) {
            document.getElementById('reportType').value = reportType;
            document.getElementById('submitBtn').click();
        }
    </script>
</body>
</html>