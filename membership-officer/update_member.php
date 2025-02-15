<?php
require_once '../connection/config.php';
require_once '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn->begin_transaction();

        // Get the user_id based on the id
        $sql_get_user_id = "SELECT user_id FROM users WHERE id = ?";
        $stmt_get_user_id = $conn->prepare($sql_get_user_id);
        $stmt_get_user_id->bind_param("i", $_POST['id']);
        $stmt_get_user_id->execute();
        $user_id_result = $stmt_get_user_id->get_result();
        $user_row = $user_id_result->fetch_assoc();
        $user_id = $user_row['user_id'];
        $stmt_get_user_id->close();

        // Update the users table
        $sql_users = "UPDATE users SET 
            first_name = ?,
            last_name = ?,
            middle_name = ?,
            email = ?,
            birthday = ?,
            gender = ?,
            age = ?,
            mobile = ?,
            street = ?,
            barangay = ?,
            municipality = ?,
            province = ?,
            membership_type = ?,
            tin_number = ?,
            certificate_no = ?,
            membership_status = 'Active'
            WHERE id = ?";  // Changed from user_id to id

        $stmt_users = $conn->prepare($sql_users);
        
        // Ensure all variables are properly set before binding
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $middle_name = $_POST['middle_name'];
        $email = $_POST['email'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];
        $age = intval($_POST['age']);
        $mobile = $_POST['mobile'];
        $street = $_POST['street'];
        $barangay = $_POST['barangay'];
        $municipality = $_POST['municipality'];
        $province = $_POST['province'];
        $membership_type = $_POST['membership_type'];
        $tin_number = $_POST['tin_number'];
        $certificate_no = $_POST['certificate_no'];
        $id = $_POST['id'];

        $stmt_users->bind_param(
            "sssssssssssssssi",
            $first_name,
            $last_name,
            $middle_name,
            $email,
            $birthday,
            $gender,
            $age,
            $mobile,
            $street,
            $barangay,
            $municipality,
            $province,
            $membership_type,
            $tin_number,
            $certificate_no,
            $id
        );
        
        $stmt_users->execute();
        $stmt_users->close();

        // Get appointment details for the user
        $sql_appointment = "SELECT description, total_amount FROM appointments WHERE user_id = ?";
        $stmt_appointment = $conn->prepare($sql_appointment);
        $stmt_appointment->bind_param("s", $user_id);
        $stmt_appointment->execute();
        $appointment_result = $stmt_appointment->get_result();
        $appointment_data = $appointment_result->fetch_assoc();
        $stmt_appointment->close();

        if ($appointment_data) {
            // Get the next control number
            $sql_control = "SELECT MAX(CAST(control_number AS UNSIGNED)) as max_control FROM transactions";
            $result = $conn->query($sql_control);
            $row = $result->fetch_assoc();
            $next_control = str_pad((intval($row['max_control']) + 1), 5, '0', STR_PAD_LEFT);

            // Insert into transactions table
            $sql_transaction = "INSERT INTO transactions (
                user_id,
                service_name,
                amount,
                control_number,
                payment_status
            ) VALUES (?, ?, ?, ?, 'Completed')";

            $stmt_transaction = $conn->prepare($sql_transaction);
            $stmt_transaction->bind_param(
                "ssds",
                $user_id,
                $appointment_data['description'],
                $appointment_data['total_amount'],
                $next_control
            );
            $stmt_transaction->execute();
            $stmt_transaction->close();
        }

        // Email sending code
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'paschalmultipurposecooperative@gmail.com';
            $mail->Password = 'shga dxjh acgz qwfq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('paschalmultipurposecooperative@gmail.com', 'Paschal Multipurpose Cooperative');
            $mail->addAddress($email, $first_name . ' ' . $last_name);

            $mail->isHTML(true);
            $mail->Subject = 'Membership Information Updated';
            $mail->Body = '
                <html>
                <body>
                    <h2>Update Confirmation</h2>
                    <p>Dear ' . htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name) . ',</p>
                    <p>Your membership information has been successfully updated.</p>
                    <p>Your current membership details:</p>
                    <ul>
                        <li>Membership Type: ' . htmlspecialchars($membership_type) . '</li>
                        <li>Certificate No: ' . htmlspecialchars($certificate_no) . '</li>
                    </ul>
                    <p>Thank you for being a valued member of our cooperative!</p>
                    <br>
                    <p>Best regards,</p>
                    <p>Paschal Multipurpose Cooperative</p>
                </body>
                </html>
            ';
            $mail->AltBody = 'Your membership information has been successfully updated.';

            $mail->send();
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }

        $conn->commit();
        header("Location: member.php?success=1");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error: " . $e->getMessage());
        header("Location: member.php?error=1");
        exit();
    }
} else {
    header("Location: member.php");
    exit();
}
?>