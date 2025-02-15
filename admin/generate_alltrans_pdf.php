<?php
require_once '../vendor/autoload.php';
include '../connection/config.php';

try {
    // Modified SQL query to get all transactions
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
                u.certificate_no,
                s.name as services_name
            FROM transactions t
            LEFT JOIN users u ON t.user_id = u.user_id
            LEFT JOIN services s ON s.name = t.service_name
            ORDER BY t.created_at DESC";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    if ($result->num_rows === 0) {
        die("No transactions found");
    }

    // Create directory for PDFs
    $output_dir = 'C:\\xampp\\htdocs\\pmpc\\dist\\assets\\generated_transactions\\';
    if (!file_exists($output_dir)) {
        mkdir($output_dir, 0777, true);
    }

    // Create ZIP file
    $zip = new ZipArchive();
    $zip_filename = $output_dir . 'All_Transactions_' . date('Y-m-d_H-i-s') . '.zip';
    if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        throw new Exception("Could not create ZIP file");
    }

    // CSS styles remain the same
    $css = '
        .header { text-align: center; color: #333; padding-bottom: 20px; border-bottom: 2px solid #0056b3; margin-bottom: 20px; }
        .logo { max-width: 150px; margin-bottom: 10px; }
        .title { font-size: 24px; font-weight: bold; color: #0056b3; margin-bottom: 5px; }
        .subtitle { font-size: 16px; color: #666; }
        .section-title { background-color: #0056b3; color: white; padding: 8px; margin-top: 20px; margin-bottom: 10px; font-size: 14px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px; border: 1px solid #ddd; font-size: 12px; }
        th { background-color: #f5f5f5; font-weight: bold; color: #333; }
        .total-section { margin-top: 20px; border: 2px solid #0056b3; padding: 10px; background-color: #f8f9fa; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #666; padding: 10px 0; border-top: 1px solid #ddd; }
    ';

    $pdf_count = 0;
    $pdf_files = [];

    while ($row = $result->fetch_assoc()) {
        // Create new PDF for each transaction
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15
        ]);

        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

        // Generate HTML content
        $html = '
        <div class="header">
            <div class="title">Transaction Receipt</div>
            <div class="subtitle">Transaction #' . $row['transaction_id'] . '</div>
        </div>

        <div class="section-title">Personal Information</div>
        <table>
            <tr>
                <th width="30%">OR Number</th>
                <td width="70%">' . htmlspecialchars($row['control_number']) . '</td>
            </tr>
            <tr>
                <th>Member ID</th>
                <td>' . htmlspecialchars($row['user_id']) . '</td>
            </tr>
            <tr>
                <th>Certificate No.</th>
                <td>' . htmlspecialchars($row['certificate_no']) . '</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>' . htmlspecialchars($row['email']) . '</td>
            </tr>
        </table>

        ' . generate_service_tables($row, $row['amount']) . '
        ';

        $mpdf->WriteHTML($html);

        // Generate unique filename for each transaction
        $filename = 'Transaction_' . $row['transaction_id'] . '_' . date('Y-m-d_H-i-s') . '.pdf';
        $pdf_path = $output_dir . $filename;

        // Save PDF
        $mpdf->Output($pdf_path, 'F');
        $pdf_files[] = $pdf_path;
        
        // Add to ZIP
        $zip->addFile($pdf_path, $filename);
        $pdf_count++;
    }

    // Close ZIP
    $zip->close();

    // Clean up individual PDF files
    foreach ($pdf_files as $pdf_file) {
        if (file_exists($pdf_file)) {
            unlink($pdf_file);
        }
    }

    // Send ZIP file to browser
    if (file_exists($zip_filename)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zip_filename) . '"');
        header('Content-Length: ' . filesize($zip_filename));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($zip_filename);
        unlink($zip_filename); // Delete ZIP file after download
        exit;
    } else {
        throw new Exception("ZIP file not found");
    }

} catch (Exception $e) {
    error_log("PDF Generation Error: " . $e->getMessage());
    echo "Error generating PDFs: " . $e->getMessage();
    exit;
}

// Function to generate service tables HTML remains the same
function generate_service_tables($row, $total) {
    return '
    <div class="section-title">Financial Services</div>
    <table>
        <tr>
            <th width="50%">Service Type</th>
            <th width="50%">Amount</th>
        </tr>
        <tr>
            <th>Service</th>
            <td>' . htmlspecialchars($row['service_name']) . '</td>
        </tr>
        <tr>
            <th>Payment</th>
            <td>₱' . number_format($row['amount'], 2) . '</td>
        </tr>
        <tr>
            <th>Date Of Transaction</th>
            <td>' . date('F d, Y h:i A', strtotime($row['created_at'])) . '</td>
        </tr>
        <tr>
            <th>Payment Status</th>
            <td>' . htmlspecialchars($row['payment_status']) . '</td>
        </tr>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <th width="50%">Total Amount</th>
                <td width="50%" style="font-weight: bold; font-size: 14px;">₱' . number_format($total, 2) . '</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This is an official transaction record. Generated on ' . date('F d, Y h:i A') . '
    </div>';
}
?>