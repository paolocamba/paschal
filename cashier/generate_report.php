<?php
use Mpdf\Mpdf;
session_start();

// Check if the user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require_once '../connection/config.php';

// Handle report generation requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../vendor/autoload.php';

    // Prepare the logo for the PDF
    $logo_path = '../dist/assets/images/pmpclogo.jpg';
    
    if (file_exists($logo_path)) {
        $logo_data = base64_encode(file_get_contents($logo_path));
        $logo_src = 'data:image/jpg;base64,' . $logo_data;
    } else {
        $logo_src = '';
    }
    
    try {
        $mpdf = new Mpdf(['format' => 'A4']);
        
        // Get form data
        $startDate = $_POST['startDate'] ?? null;
        $endDate = $_POST['endDate'] ?? null;
        $reportType = $_POST['reportType'] ?? null;

        if (!$startDate || !$endDate || !$reportType) {
            throw new Exception("Missing required parameters.");
        }

        // Format dates for display
        $formattedStartDate = date('F d, Y', strtotime($startDate));
        $formattedEndDate = date('F d, Y', strtotime($endDate));

        // Generate appropriate report
        switch ($reportType) {
            case 'daily_collection':
                generateDailyCollectionReport($mpdf, $startDate, $endDate, $formattedStartDate, $formattedEndDate, $conn, $logo_src);
                break;
            case 'member_contributions':
                generateMemberContributionsReport($mpdf, $startDate, $endDate, $formattedStartDate, $formattedEndDate, $conn, $logo_src);
                break;
            default:
                throw new Exception('Invalid report type');
        }

        $filename = 'PMPC_' . ucwords(str_replace('_', ' ', $reportType)) . '_' . date('Ymd') . '.pdf';
        $mpdf->Output($filename, 'I');
        exit;

    } catch (Exception $e) {
        echo "<script>alert('Error generating report: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit;
    }
}

// Report Generation Functions

function generateDailyCollectionReport($mpdf, $startDate, $endDate, $formattedStartDate, $formattedEndDate, $conn, $logo_src) {
    // Query for daily collection data
    $sql = "SELECT 
                DATE(created_at) as transaction_date,
                service_name,
                payment_status,
                COUNT(*) as transaction_count,
                SUM(amount) as total_amount
            FROM transactions
            WHERE DATE(created_at) BETWEEN ? AND ?
            GROUP BY DATE(created_at), service_name, payment_status
            ORDER BY transaction_date, service_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Organize data by date and service
    $dailyData = [];
    $serviceTotals = []; // To store service-wise totals across all dates
    $grandTotal = 0;
    
    while ($row = $result->fetch_assoc()) {
        $date = $row['transaction_date'];
        $service = $row['service_name'];
        
        if (!isset($dailyData[$date])) {
            $dailyData[$date] = [
                'date' => $date,
                'services' => [],
                'daily_total' => 0
            ];
        }
        
        if (!isset($serviceTotals[$service])) {
            $serviceTotals[$service] = [
                'name' => $service,
                'transaction_count' => 0,
                'total_amount' => 0
            ];
        }
        
        $dailyData[$date]['services'][] = [
            'service_name' => $service,
            'payment_status' => $row['payment_status'],
            'transaction_count' => $row['transaction_count'],
            'total_amount' => $row['total_amount']
        ];
        
        $dailyData[$date]['daily_total'] += $row['total_amount'];
        $serviceTotals[$service]['transaction_count'] += $row['transaction_count'];
        $serviceTotals[$service]['total_amount'] += $row['total_amount'];
        $grandTotal += $row['total_amount'];
    }
    
    // Generate HTML
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12pt; }
            .container { width: 100%; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .report-title { font-size: 18pt; font-weight: bold; margin-bottom: 10px; }
            .report-period { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .footer { margin-top: 20px; }
            .date-header { background-color: #e6e6e6; font-weight: bold; }
            .service-row { background-color: #f9f9f9; }
            .service-total-row { background-color: #e0e0e0; font-weight: bold; }
            .grand-total-row { background-color: #d0d0d0; font-weight: bold; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="' . $logo_src . '" alt="PMPC Logo" style="max-width: 150px;">
                <div class="report-title">Daily Transaction Collection Report</div>
                <div class="report-period">Period: ' . $formattedStartDate . ' to ' . $formattedEndDate . '</div>
            </div>';

    // Daily breakdown section
    foreach ($dailyData as $date => $data) {
        $formattedDate = date('F d, Y', strtotime($date));
        
        $html .= '
            <div>
                <h3>' . $formattedDate . '</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Service Type</th>
                            <th>Status</th>
                            <th class="text-right">No. of Transactions</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($data['services'] as $service) {
            $html .= '
                        <tr class="service-row">
                            <td>' . htmlspecialchars($service['service_name']) . '</td>
                            <td>' . htmlspecialchars($service['payment_status']) . '</td>
                            <td class="text-right">' . $service['transaction_count'] . '</td>
                            <td class="text-right">₱' . number_format($service['total_amount'], 2) . '</td>
                        </tr>';
        }
        
        $html .= '
                        <tr class="date-header">
                            <td colspan="3"><strong>Daily Total</strong></td>
                            <td class="text-right"><strong>₱' . number_format($data['daily_total'], 2) . '</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>';
    }
    
    // Grand Total section with service breakdown
    $html .= '
            <div style="margin-top: 30px;">
                <h3>Grand Total Summary</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Service Type</th>
                            <th class="text-right">Total Transactions</th>
                            <th class="text-right">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
    
    // Add each service's total
    foreach ($serviceTotals as $service) {
        $html .= '
                        <tr class="service-total-row">
                            <td>' . htmlspecialchars($service['name']) . '</td>
                            <td class="text-right">' . $service['transaction_count'] . '</td>
                            <td class="text-right">₱' . number_format($service['total_amount'], 2) . '</td>
                        </tr>';
    }
    
    // Add the final grand total row
    $html .= '
                        <tr class="grand-total-row">
                            <td><strong>GRAND TOTAL</strong></td>
                            <td class="text-right"><strong>' . array_sum(array_column($serviceTotals, 'transaction_count')) . '</strong></td>
                            <td class="text-right"><strong>₱' . number_format($grandTotal, 2) . '</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="footer">
                <div>Generated on: ' . date('F d, Y H:i:s') . '</div>
                <div>Generated by: ' . htmlspecialchars($_SESSION['username'] ?? 'System') . '</div>
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
}

function generateMemberContributionsReport($mpdf, $startDate, $endDate, $formattedStartDate, $formattedEndDate, $conn, $logo_src) {
    // Query for member contributions
    $sql = "SELECT 
                u.user_id,
                CONCAT(u.first_name, ' ', u.last_name) as member_name,
                t.service_name,
                COUNT(t.transaction_id) as transaction_count,
                SUM(t.amount) as total_amount
            FROM transactions t
            JOIN users u ON t.user_id = u.user_id
            WHERE t.payment_status = 'Completed' 
              AND DATE(t.created_at) BETWEEN ? AND ?
            GROUP BY u.user_id, t.service_name
            ORDER BY member_name, t.service_name";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Organize data by member
    $memberData = [];
    $grandTotal = 0;
    
    while ($row = $result->fetch_assoc()) {
        $userId = $row['user_id'];
        if (!isset($memberData[$userId])) {
            $memberData[$userId] = [
                'member_name' => $row['member_name'],
                'user_id' => $row['user_id'],
                'services' => [],
                'member_total' => 0
            ];
        }
        
        $memberData[$userId]['services'][] = [
            'service_name' => $row['service_name'],
            'transaction_count' => $row['transaction_count'],
            'total_amount' => $row['total_amount']
        ];
        
        $memberData[$userId]['member_total'] += $row['total_amount'];
        $grandTotal += $row['total_amount'];
    }
    
    // Generate HTML
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12pt; }
            .container { width: 100%; padding: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .report-title { font-size: 18pt; font-weight: bold; margin-bottom: 10px; }
            .report-period { margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
            .footer { margin-top: 20px; }
            .member-header { background-color: #e6e6e6; font-weight: bold; }
            .service-row { background-color: #f9f9f9; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="' . $logo_src . '" alt="PMPC Logo" style="max-width: 150px;">
                <div class="report-title">Member Contributions Report</div>
                <div class="report-period">Period: ' . $formattedStartDate . ' to ' . $formattedEndDate . '</div>
            </div>';

    foreach ($memberData as $memberId => $data) {
        $html .= '
            <div>
                <h3>' . htmlspecialchars($data['member_name']) . ' (ID: ' . htmlspecialchars($data['user_id']) . ')</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Service Type</th>
                            <th class="text-right">No. of Transactions</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>';
        
        foreach ($data['services'] as $service) {
            $html .= '
                        <tr class="service-row">
                            <td>' . htmlspecialchars($service['service_name']) . '</td>
                            <td class="text-right">' . $service['transaction_count'] . '</td>
                            <td class="text-right">₱' . number_format($service['total_amount'], 2) . '</td>
                        </tr>';
        }
        
        $html .= '
                        <tr class="member-header">
                            <td colspan="2"><strong>Member Total</strong></td>
                            <td class="text-right"><strong>₱' . number_format($data['member_total'], 2) . '</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>';
    }
    
    $html .= '
            <div style="margin-top: 20px;">
                <table>
                    <tr class="member-header">
                        <td colspan="2"><strong>Grand Total</strong></td>
                        <td class="text-right"><strong>₱' . number_format($grandTotal, 2) . '</strong></td>
                    </tr>
                </table>
            </div>
            
            <div class="footer">
                <div>Generated on: ' . date('F d, Y H:i:s') . '</div>
                <div>Generated by: ' . htmlspecialchars($_SESSION['username'] ?? 'System') . '</div>
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
}

// Redirect if accessed directly
header("Location: ../dashboard.php");
exit();
?>