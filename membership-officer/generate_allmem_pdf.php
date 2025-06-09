<?php
require '../vendor/autoload.php';
include '../connection/config.php';

use Mpdf\Mpdf;

try {
    // Fetch members' data
     
    $sql = "SELECT 
    u.id, u.user_id, u.first_name, u.last_name, u.middle_name, u.email, 
    u.birthday, u.gender, u.age, u.mobile, u.street, u.barangay, 
    u.municipality, u.province, u.membership_type, u.membership_status,
    u.tin_number, u.tin_id_image, u.certificate_no,
    COALESCE(a.savings, 0) as savings,
    COALESCE(a.share_capital, 0) as share_capital,
    COALESCE(a.membership_fee, 0) as membership_fee,
    COALESCE(a.insurance, 0) as insurance,
    COALESCE(a.total_amount, 0) as total_amount
    FROM users u
    LEFT JOIN appointments a ON u.user_id = a.user_id
    WHERE u.user_type = 'Member'";
        $result = $conn->query($sql);
    
    // Initialize MPDF with A3 paper size
    $mpdf = new Mpdf(['format' => 'A3']);
    
    // Start the HTML content
    $html = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { width: 100%; }
            .text-center { text-align: center; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; }
            th { background-color: #f2f2f2; text-align: left; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="text-center">
                <img src="../dist/assets/images/pmpc-logo.png" alt="PMPC Logo" style="max-width: 200px;">
            </div>
            <h1>Members List</h1>';
    
    // Loop through each member
    while ($row = $result->fetch_assoc()) {
        $html .= '
            <table>
                <tr>
                    <th colspan="2">Personal Information</th>
                </tr>
                <tr>
                    <td><strong>First Name:</strong></td>
                    <td>' . htmlspecialchars($row['first_name']) . '</td>
                </tr>
                <tr>
                    <td><strong>Middle Name:</strong></td>
                    <td>' . htmlspecialchars($row['middle_name']) . '</td>
                </tr>
                <tr>
                    <td><strong>Last Name:</strong></td>
                    <td>' . htmlspecialchars($row['last_name']) . '</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>' . htmlspecialchars($row['email']) . '</td>
                </tr>
                <tr>
                    <td><strong>Phone Number:</strong></td>
                    <td>' . htmlspecialchars($row['mobile']) . '</td>
                </tr>
                <tr>
                    <td><strong>Age:</strong></td>
                    <td>' . htmlspecialchars($row['age']) . '</td>
                </tr>
                <tr>
                    <td><strong>Gender:</strong></td>
                    <td>' . htmlspecialchars($row['gender']) . '</td>
                </tr>
                <tr>
                    <td><strong>Birthday:</strong></td>
                    <td>' . htmlspecialchars($row['birthday']) . '</td>
                </tr>
                <tr>
                    <td><strong>Certificate No:</strong></td>
                    <td>' . htmlspecialchars($row['certificate_no']) . '</td>
                </tr>
                <tr>
                    <td><strong>Tin Number:</strong></td>
                    <td>' . htmlspecialchars($row['tin_number']) . '</td>
                </tr>
                <tr>
                    <td><strong>Tin ID Image:</strong></td>
                    <td>' . htmlspecialchars($row['tin_id_image']) . '</td>
                </tr>
                <tr>
                    <td><strong>Membership Type:</strong></td>
                    <td>' . htmlspecialchars($row['membership_type']) . '</td>
                </tr>
                <tr>
                    <td><strong>Savings:</strong></td>
                    <td>' . htmlspecialchars($row['savings']) . '</td>
                </tr>
                <tr>
                    <td><strong>Share Capital:</strong></td>
                    <td>' . htmlspecialchars($row['share_capital']) . '</td>
                </tr>
                <tr>
                    <td><strong>Membership Status:</strong></td>
                    <td>' . htmlspecialchars($row['membership_status']) . '</td>
                </tr>
                <tr>
                    <td><strong>Membership Fee:</strong></td>
                    <td>' . htmlspecialchars($row['membership_fee']) . '</td>
                </tr>

                <tr>
                    <td><strong>Insurance:</strong></td>
                    <td>' . htmlspecialchars($row['insurance']) . '</td>
                </tr>
               <tr>
                    <td><strong>Total Amount:</strong></td>
                    <td>' . htmlspecialchars($row['total_amount']) . '</td>
                </tr>
                <tr>
                    <th colspan="2">Address Information</th>
                </tr>
                <tr>
                    <td><strong>Street:</strong></td>
                    <td>' . htmlspecialchars($row['street']) . '</td>
                </tr>
                <tr>
                    <td><strong>Barangay:</strong></td>
                    <td>' . htmlspecialchars($row['barangay']) . '</td>
                </tr>
                <tr>
                    <td><strong>Municipality:</strong></td>
                    <td>' . htmlspecialchars($row['municipality']) . '</td>
                </tr>
                <tr>
                    <td><strong>Province:</strong></td>
                    <td>' . htmlspecialchars($row['province']) . '</td>
                </tr>
            </table>
            <div style="page-break-after: always;"></div>';
    }
    
    $html .= '
        </div>
    </body>
    </html>';

    // Create and output PDF
    $mpdf->SetTitle('Members Data Report');
    $mpdf->WriteHTML($html);
    $mpdf->Output('Members_AllData_Report.pdf', 'D'); // Download the PDF

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>