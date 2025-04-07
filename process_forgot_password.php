<?php
session_start();
require_once 'connection/config.php'; // Database connection file
require 'vendor/autoload.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format";
        $_SESSION['message_type'] = "danger";
        header("Location: forgot-pass.php");
        exit();
    }

    // Check if email exists in database - using users table instead of members
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $updateStmt->bind_param("sss", $token, $expiry, $email);
        $updateStmt->execute();

        // Create reset link - update with your actual domain
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/paschal/reset_password.php?token=" . $token;

        // PHPMailer implementation
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'paschalmultipurposecooperative@gmail.com';
            $mail->Password = 'shga dxjh acgz qwfq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('paschalmultipurposecooperative@gmail.com', 'PASCHAL MULTIPURPOSE COOPERATIVE');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request - PASCHAL Multi-Purpose Cooperative';
            $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                    <h2 style='color: #0F4332;'>Password Reset Request</h2>
                    <p>We received a request to reset your password. Click the button below to set a new password:</p>
                    <p style='text-align: center;'>
                        <a href='{$resetLink}' style='display: inline-block; background: linear-gradient(135deg, #00FFAF, #0F4332); color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 20px 0;'>Reset Password</a>
                    </p>
                    <p>This link will expire in 1 hour.</p>
                    <p>If you didn't request this, please ignore this email.</p>
                    <br>
                    <p>Best regards,</p>
                    <p>PASCHAL Multi-Purpose Cooperative</p>
                </div>
            </body>
            </html>";

            $mail->send();
            $_SESSION['message'] = "Password reset instructions have been sent to your email";
            $_SESSION['message_type'] = "success";
        } catch (Exception $e) {
            $_SESSION['message'] = "Error sending email. Please try again later";
            $_SESSION['message_type'] = "danger";
        }
    } else {
        // Don't reveal if email exists or not for security
        $_SESSION['message'] = "If your email is registered, you will receive password reset instructions";
        $_SESSION['message_type'] = "info";
    }

    $stmt->close();
    $conn->close();

    header("Location: forgot-pass.php");
    exit();
}
?>