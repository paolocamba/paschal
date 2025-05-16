<?php
session_start();
require_once '../connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberID = $_SESSION['user_id'];
    $amount = floatval($_POST['amount']);
    $paymentMethod = $_POST['payment_method'];
    $notes = $_POST['notes'] ?? '';
    $status = 'In Progress';
    
    // Insert into savings table
    $sqlSavings = "INSERT INTO savings (MemberID, Amount, PaymentMethod, Notes, Status) 
                   VALUES (?, ?, ?, ?, ?)";
    
    $stmtSavings = $conn->prepare($sqlSavings);
    $stmtSavings->bind_param("sdsss", $memberID, $amount, $paymentMethod, $notes, $status);
    
    if ($stmtSavings->execute()) {
        $stmtSavings->close();
        
        // Retrieve member details for the appointment table
        $sqlMember = "SELECT first_name, last_name, email FROM users WHERE id = ?";
        $stmtMember = $conn->prepare($sqlMember);
        $stmtMember->bind_param("i", $memberID);
        $stmtMember->execute();
        $stmtMember->bind_result($firstName, $lastName, $email);
        $stmtMember->fetch();
        $stmtMember->close();

        // Insert into appointments table
        $appointmentDate = $_POST['appointment_date']; // Assuming you send an appointment date from the form
        $serviceID = $_POST['service_id'] ?? null; // Optionally, a service ID
        $statusAppointment = 'Approved'; // Default status can be "Approved" based on your requirements

        $sqlAppointments = "INSERT INTO appointments 
                            (first_name, last_name, email, description, savings, membership_fee, insurance, 
                             total_amount, ModeOfPayment, payable_amount, payable_date, appointmentdate, 
                             user_id, serviceID, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtAppointments = $conn->prepare($sqlAppointments);
        $stmtAppointments->bind_param("ssssiiiddssss", 
            $firstName, $lastName, $email, 'Regular Loan Payment', 
            $amount, 0, 0, $amount, $paymentMethod, $amount, $appointmentDate, $appointmentDate, 
            $memberID, $serviceID, $statusAppointment);
        
        if ($stmtAppointments->execute()) {
            $stmtAppointments->close();
            header("Location: home.php?success=1");
            exit();
        } else {
            $stmtAppointments->close();
            header("Location: home.php?error=1");
            exit();
        }
    } else {
        $stmtSavings->close();
        header("Location: home.php?error=1");
        exit();
    }
}
?>
