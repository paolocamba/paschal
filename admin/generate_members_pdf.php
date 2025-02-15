<?php
require '../vendor/autoload.php';
include '../connection/config.php';

use Mpdf\Mpdf;

try {
    // Check if ID is provided
    if (!isset($_GET['id'])) {
        throw new Exception('Member ID is required');
    }

    $member_id = intval($_GET['id']); // Convert to integer for security

    // Fetch single member's data
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
    WHERE u.user_type = 'Member' AND u.id = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if member exists
    if (!$row) {
        throw new Exception('Member not found');
    }

    // Initialize MPDF with A4 paper size (changed from A3 for individual reports)
    $mpdf = new Mpdf(['format' => 'A3']);
    $mpdf->SetTitle('Member Data Report - ' . $row['first_name'] . ' ' . $row['last_name']);

    // Generate HTML content
    $html = '
    <html>
    <head>
        <style>
            body { 
                font-family: Arial, sans-serif;
                font-size: 12pt;
            }
            .container { 
                width: 100%;
                padding: 20px;
            }
            .text-center { 
                text-align: center;
                margin-bottom: 20px;
            }
            table { 
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            th, td { 
                border: 1px solid #ddd;
                padding: 8px;
            }
            th { 
                background-color: #f2f2f2;
                text-align: left;
            }
            .header {
                text-align: center;
                margin-bottom: 30px;
            }
            .member-name {
                font-size: 18pt;
                font-weight: bold;
                margin: 20px 0;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="../dist/assets/images/pmpc-logo.png" alt="PMPC Logo" style="max-width: 150px;">
                <div class="member-name">' . 
                    htmlspecialchars($row['first_name'] . ' ' . 
                    ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . 
                    $row['last_name']) . 
                '</div>
            </div>

            <table>
                <tr>
                    <th colspan="2">Personal Information</th>
                </tr>
                <tr>
                    <td width="30%"><strong>First Name:</strong></td>
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
                    <td><strong>Membership Type:</strong></td>
                    <td>' . htmlspecialchars($row['membership_type']) . '</td>
                </tr>
                <tr>
                    <td><strong>Savings:</strong></td>
                    <td>₱' . number_format(floatval($row['savings']), 2) . '</td>
                </tr>
                <tr>
                    <td><strong>Share Capital:</strong></td>
                    <td>₱' . number_format(floatval($row['share_capital']), 2) . '</td>
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
                
            </table>

            <table>
                <tr>
                    <th colspan="2">Address Information</th>
                </tr>
                <tr>
                    <td width="30%"><strong>Street:</strong></td>
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
        </div>
    </body>
    </html>';

 // Create filename from member's name
 $filename = trim($row['first_name']) . '_' . 
 ($row['middle_name'] ? trim($row['middle_name']) . '_' : '') . 
 trim($row['last_name']) . '_Report.pdf';

// Replace any spaces with underscores and remove special characters
$filename = preg_replace('/[^a-zA-Z0-9_.]/', '', str_replace(' ', '_', $filename));

// Generate and output PDF
$mpdf->WriteHTML($html);
$mpdf->Output($filename, 'D');

} catch (Exception $e) {
error_log("PDF Generation Error: " . $e->getMessage());
echo "An error occurred while generating the PDF. Please try again later.";
exit;
}
?>