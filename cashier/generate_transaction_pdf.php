<?php
require_once '../vendor/autoload.php';
include '../connection/config.php';

// Get the transaction_id from URL
$transaction_id = isset($_GET['transaction_id']) ? (int)$_GET['transaction_id'] : 0;

try {
    // Updated SQL query to match your exact database structure
    $sql = "SELECT 
                t.transaction_id,
                t.user_id,
                t.service_name,
                t.amount,
                t.control_number,
                t.payment_status,
                t.created_at,
                u.first_name,
                u.last_name,
                u.email,
                u.certificate_no
            FROM transactions t
            JOIN users u ON t.user_id = u.id
            WHERE t.transaction_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        die("No record found");
    }

    // Create new PDF instance
    $mpdf = new \Mpdf\Mpdf([
        'format' => 'A4',
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 15,
        'margin_bottom' => 15
    ]);

    // Define CSS styles
    $css = '
        .header {
            text-align: center;
            color: #333;
            padding-bottom: 20px;
            border-bottom: 2px solid #0056b3;
            margin-bottom: 20px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
        }
        .section-title {
            background-color: #0056b3;
            color: white;
            padding: 8px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
            width: 30%;
        }
        td {
            width: 70%;
        }
        .total-section {
            margin-top: 20px;
            border: 2px solid #0056b3;
            padding: 10px;
            background-color: #f8f9fa;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
    ';

    // Add CSS to mPDF
    $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

    // Generate HTML content
    $html = '
    <div class="header">
        <div class="title">Transaction Receipt</div>
        <div class="subtitle">Generated on ' . date('F d, Y') . '</div>
    </div>

    <div class="section-title">Transaction Information</div>
    <table>
        <tr>
            <th>Transaction ID</th>
            <td>' . htmlspecialchars($row['transaction_id']) . '</td>
        </tr>
        <tr>
            <th>OR Number</th>
            <td>' . htmlspecialchars($row['control_number']) . '</td>
        </tr>
        <tr>
            <th>Date</th>
            <td>' . date('F d, Y h:i A', strtotime($row['created_at'])) . '</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>' . htmlspecialchars($row['payment_status']) . '</td>
        </tr>
    </table>

    <div class="section-title">Member Information</div>
    <table>
        <tr>
            <th>Member ID</th>
            <td>' . htmlspecialchars($row['user_id']) . '</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>' . htmlspecialchars($row['email']) . '</td>
        </tr>
        <tr>
            <th>Certificate No.</th>
            <td>' . htmlspecialchars($row['certificate_no']) . '</td>
        </tr>
    </table>

    <div class="section-title">Service Details</div>
    <table>
        <tr>
            <th>Service</th>
            <td>' . htmlspecialchars($row['service_name']) . '</td>
        </tr>
        <tr>
            <th>Amount</th>
            <td>₱' . number_format($row['amount'], 2) . '</td>
        </tr>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <th>Total Amount</th>
                <td style="font-weight: bold; font-size: 14px;">₱' . number_format($row['amount'], 2) . '</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This is an official transaction record. Generated on ' . date('F d, Y h:i A') . '
    </div>';

    // Write HTML to the PDF
    $mpdf->WriteHTML($html);

    // Set filename
    $filename = 'Transaction_' . $row['transaction_id'] . '_' . date('Y-m-d') . '.pdf';

    // Output PDF
    $mpdf->Output($filename, 'D');

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>