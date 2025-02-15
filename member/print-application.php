<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
include '../connection/config.php';

var_dump($_SESSION); // Check if user_id is actually set
var_dump($_GET); // Verify the id parameter is being passed

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../member-login.php");
    exit();
}

// Verify loan ID is provided
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$loan_id = $_GET['id'];

// Verify the loan belongs to the logged-in user
$query = "SELECT * FROM loanapplication WHERE userID = ? AND LoanID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $loan_id);
$stmt->execute();
$result = $stmt->get_result();
$loan = $result->fetch_assoc();

if (!$loan) {
    header("Location: index.php");
    exit();
}
// Create new PDF instance
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 15,
    'margin_bottom' => 15,
]);

// Set document metadata
$mpdf->SetTitle('Loan Application - ' . $loan['LoanID']);
$mpdf->SetAuthor('Your Company Name');

// Format the date properly before adding it to the HTML string
$formattedDate = date('F d, Y', strtotime($loan['DateOfLoan']));

// Start building the HTML content
$html = '
<style>
    body { font-family: arial; font-size: 12pt; }
    .header { text-align: center; margin-bottom: 20px; }
    .section { margin-bottom: 20px; }
    .section-title { background-color: #f0f0f0; padding: 5px; margin-bottom: 10px; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    table, th, td { border: 1px solid #ddd; }
    th, td { padding: 5px; text-align: left; }
    th { background-color: #f0f0f0; }
    .text-right { text-align: right; }
</style>

<div class="header">
    <h2>Loan Application Details</h2>
    <p>Application ID: ' . htmlspecialchars($loan['LoanID']) . '</p>
    <p>Date: ' . htmlspecialchars($formattedDate) . '</p>
</div>

<div class="section">
    <div class="section-title">Loan Information</div>
    <table>
        <tr>
            <td width="30%"><strong>Loan Type:</strong></td>
            <td>' . htmlspecialchars($loan['LoanType']) . '</td>
        </tr>
        <tr>
            <td><strong>Amount Requested:</strong></td>
            <td>₱' . number_format($loan['AmountRequested'], 2) . '</td>
        </tr>
        <tr>
            <td><strong>Loan Term:</strong></td>
            <td>' . htmlspecialchars($loan['LoanTerm']) . ' months</td>
        </tr>
        <tr>
            <td><strong>Purpose:</strong></td>
            <td>' . htmlspecialchars($loan['Purpose']) . '</td>
        </tr>
        <tr>
            <td><strong>Mode of Payment:</strong></td>
            <td>' . htmlspecialchars($loan['ModeOfPayment']) . '</td>
        </tr>
    </table>
</div>

<div class="section">
    <div class="section-title">Personal Information</div>
    <table>
        <tr>
            <td width="30%"><strong>Years at Present Address:</strong></td>
            <td>' . htmlspecialchars($loan['years_stay_present_address']) . '</td>
        </tr>
        <tr>
            <td><strong>Housing Status:</strong></td>
            <td>' . 
                ($loan['own_house'] === 'Yes' ? 'Own House' : 
                ($loan['renting'] === 'Yes' ? 'Renting' : 
                ($loan['living_with_relative'] === 'Yes' ? 'Living with Relative' : ''))) . 
            '</td>
        </tr>
        <tr>
            <td><strong>Marital Status:</strong></td>
            <td>' . htmlspecialchars($loan['marital_status']) . '</td>
        </tr>';

if ($loan['marital_status'] === 'Married') {
    $html .= '
        <tr>
            <td><strong>Spouse Name:</strong></td>
            <td>' . htmlspecialchars($loan['spouse_name']) . '</td>
        </tr>';
}

$html .= '
    </table>
</div>';

// Dependents Section
if ($loan['number_of_dependents'] > 0) {
    $html .= '
    <div class="section">
        <div class="section-title">Dependents Information</div>
        <p><strong>Number of Dependents:</strong> ' . htmlspecialchars($loan['number_of_dependents']) . '</p>
        <p><strong>Dependents in School:</strong> ' . htmlspecialchars($loan['dependents_in_school']) . '</p>
        
        <table>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Grade Level</th>
            </tr>';
    
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($loan["dependent{$i}_name"])) {
            $html .= '
            <tr>
                <td>' . htmlspecialchars($loan["dependent{$i}_name"]) . '</td>
                <td>' . htmlspecialchars($loan["dependent{$i}_age"]) . '</td>
                <td>' . htmlspecialchars($loan["dependent{$i}_grade_level"]) . '</td>
            </tr>';
        }
    }
    
    $html .= '
        </table>
    </div>';
}

// Employment Information
$html .= '
<div class="section">
    <div class="section-title">Employment/Business Information</div>
    <table>
        <tr>
            <td width="30%"><strong>Employer Name:</strong></td>
            <td>' . htmlspecialchars($loan['employer_name']) . '</td>
        </tr>
        <tr>
            <td><strong>Employer Address:</strong></td>
            <td>' . htmlspecialchars($loan['employer_address']) . '</td>
        </tr>
        <tr>
            <td><strong>Position:</strong></td>
            <td>' . htmlspecialchars($loan['present_position']) . '</td>
        </tr>
        <tr>
            <td><strong>Date Of Employment:</strong></td>
            <td>' . ((!empty($loan['date_of_employment'])) ? 
                htmlspecialchars(date('F j, Y', strtotime($loan['date_of_employment']))) : 
                '') . '</td>
        </tr>
        <tr>
            <td><strong>Monthly Income:</strong></td>
            <td>₱' . number_format($loan['monthly_income'], 2) . '</td>
        </tr>
        <tr>
            <td><strong>Contact Person:</strong></td>
            <td>' . htmlspecialchars($loan['contact_person']) . '</td>
        </tr>
        <tr>
            <td><strong>Contact Number:</strong></td>
            <td>' . htmlspecialchars($loan['contact_telephone_no']) . '</td>
        </tr>
        ' . ((!empty($loan['self_employed_business_type'])) ? '
        <tr>
            <td><strong>Business Type:</strong></td>
            <td>' . htmlspecialchars($loan['self_employed_business_type']) . '</td>
        </tr>
        <tr>
            <td><strong>Business Start Date:</strong></td>
            <td>' . ((!empty($loan['business_start_date'])) ? 
                htmlspecialchars(date('F j, Y', strtotime($loan['business_start_date']))) : 
                '') . '</td>
        </tr>
        ' : '') . '
    </table>
</div>

<div class="section">
    <div class="section-title">Financial Information</div>
    <table>
        <tr>
            <th colspan="2">Monthly Income</th>
        </tr>
        <tr>
            <td>Self Income (' . htmlspecialchars($loan['self_income']) . ')</td>
            <td class="text-right">₱' . number_format($loan['self_income_amount'], 2) . '</td>
        </tr>';

if (!empty($loan['other_income'])) {
    $html .= '
        <tr>
            <td>Other Income (' . htmlspecialchars($loan['other_income']) . ')</td>
            <td class="text-right">₱' . number_format($loan['self_other_income_amount'], 2) . '</td>
        </tr>';
}

if (!empty($loan['spouse_income'])) {
    $html .= '
        <tr>
            <td>Spouse Income (' . htmlspecialchars($loan['spouse_income']) . ')</td>
            <td class="text-right">₱' . number_format($loan['spouse_income_amount'], 2) . '</td>
        </tr>';
}

$html .= '
    </table>

    <table style="margin-top: 10px;">
        <tr>
            <th colspan="2">Monthly Expenses</th>
        </tr>
        <tr>
            <td>Food & Groceries</td>
            <td class="text-right">₱' . number_format($loan['food_groceries_expense'], 2) . '</td>
        </tr>
        <tr>
            <td>Transportation</td>
            <td class="text-right">₱' . number_format($loan['gas_oil_transportation_expense'], 2) . '</td>
        </tr>
        <tr>
            <td>Schooling</td>
            <td class="text-right">₱' . number_format($loan['schooling_expense'], 2) . '</td>
        </tr>
        <tr>
            <td>Utilities</td>
            <td class="text-right">₱' . number_format($loan['utilities_expense'], 2) . '</td>
        </tr>
        <tr>
            <td>Miscellaneous</td>
            <td class="text-right">₱' . number_format($loan['miscellaneous_expense'], 2) . '</td>
        </tr>
        <tr style="background-color: #f0f0f0;">
            <td><strong>Total Expenses</strong></td>
            <td class="text-right"><strong>₱' . number_format($loan['total_expenses'], 2) . '</strong></td>
        </tr>
    </table>
</div>';

// Bank Accounts Section
if ($loan['savings_account'] || $loan['current_account']) {
    $html .= '
    <div class="section">
        <div class="section-title">Bank Accounts</div>
        <table>';
    
    if ($loan['savings_account']) {
        $html .= '
            <tr>
                <th colspan="2">Savings Account</th>
            </tr>
            <tr>
                <td width="30%"><strong>Bank:</strong></td>
                <td>' . htmlspecialchars($loan['savings_bank']) . '</td>
            </tr>
            <tr>
                <td><strong>Branch:</strong></td>
                <td>' . htmlspecialchars($loan['savings_branch']) . '</td>
            </tr>';
    }
    
    if ($loan['current_account']) {
        $html .= '
            <tr>
                <th colspan="2">Current Account</th>
            </tr>
            <tr>
                <td width="30%"><strong>Bank:</strong></td>
                <td>' . htmlspecialchars($loan['current_bank']) . '</td>
            </tr>
            <tr>
                <td><strong>Branch:</strong></td>
                <td>' . htmlspecialchars($loan['current_branch']) . '</td>
            </tr>';
    }
    
    $html .= '
        </table>
    </div>';
}

// Assets Section
$has_assets = false;
for ($i = 1; $i <= 4; $i++) {
    if (!empty($loan["assets$i"])) {
        $has_assets = true;
        break;
    }
}

if ($has_assets) {
    $html .= '
    <div class="section">
        <div class="section-title">Assets</div>
        <ul>';
    
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($loan["assets$i"])) {
            $html .= '<li>' . htmlspecialchars($loan["assets$i"]) . '</li>';
        }
    }
    
    $html .= '
        </ul>
    </div>';
}

// Credit Information Section
$has_creditors = false;
for ($i = 1; $i <= 4; $i++) {
    if (!empty($loan["creditor{$i}_name"])) {
        $has_creditors = true;
        break;
    }
}

if ($has_creditors) {
    $html .= '
    <div class="section">
        <div class="section-title">Credit Information</div>
        <table>
            <tr>
                <th>Creditor Name</th>
                <th>Address</th>
                <th>Original Amount</th>
                <th>Present Balance</th>
            </tr>';
    
    for ($i = 1; $i <= 4; $i++) {
        if (!empty($loan["creditor{$i}_name"])) {
            $html .= '
            <tr>
                <td>' . htmlspecialchars($loan["creditor{$i}_name"]) . '</td>
                <td>' . htmlspecialchars($loan["creditor{$i}_address"]) . '</td>
                <td class="text-right">₱' . number_format($loan["creditor{$i}_original_amount"], 2) . '</td>
                <td class="text-right">₱' . number_format($loan["creditor{$i}_present_balance"], 2) . '</td>
            </tr>';
        }
    }
    
    $html .= '
        </table>
    </div>';
}

// Character References Section
$html .= '
<div class="section">
    <div class="section-title">Character References</div>
    <table>
        <tr>
            <th>Name</th>
            <th>Address</th>
            <th>Contact Number</th>
        </tr>';

for ($i = 1; $i <= 3; $i++) {
    if (!empty($loan["reference{$i}_name"])) {
        $html .= '
        <tr>
            <td>' . htmlspecialchars($loan["reference{$i}_name"]) . '</td>
            <td>' . htmlspecialchars($loan["reference{$i}_address"]) . '</td>
            <td>' . htmlspecialchars($loan["reference{$i}_contact_no"]) . '</td>
        </tr>';
    }
}

$html .= '
    </table>
</div>

<div class="section">
    <div class="section-title">Application Summary</div>
    <table>
        <tr>
            <td width="30%"><strong>Total Monthly Income:</strong></td>
            <td class="text-right">₱' . number_format($loan['net_family_income'], 2) . '</td>
        </tr>
        <tr>
            <td><strong>Total Monthly Expenses:</strong></td>
            <td class="text-right">₱' . number_format($loan['total_expenses'], 2) . '</td>
        </tr>
    </table>
</div>';

// Write PDF
$mpdf->WriteHTML($html);

// Output the PDF
$mpdf->Output('Loan_Application_' . $loan['LoanID'] . '.pdf', 'I');