<?php
session_start();
require_once '../connection/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$memberID = intval($_SESSION['user_id']); // Ensure user_id is an integer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $transactionType = $_POST['transaction_type']; // 'deposit' or 'withdrawal'
    $notes = $_POST['notes'] ?? '';
    $appointmentDate = $_POST['appointment_date'];
    $status = 'In Progress';

    // Set description and serviceID based on transaction type
    if ($transactionType === 'deposit') {
        $description = 'Savings Deposit';
        $serviceID = 14;
    } elseif ($transactionType === 'withdrawal') {
        $description = 'Savings Withdrawal';
        $serviceID = 21;
    } else {
        $description = 'Savings Transaction';
        $serviceID = 0;
    }

    // Validate appointment date format (YYYY-MM-DD)
    $dateObj = DateTime::createFromFormat('Y-m-d', $appointmentDate);
    if (!$dateObj || $dateObj->format('Y-m-d') !== $appointmentDate) {
        header("Location: home.php?error=invalid_date");
        exit();
    }

    // Insert into savings table
    $sqlSavings = "INSERT INTO savings (MemberID, Amount, Notes, Status, Type) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmtSavings = $conn->prepare($sqlSavings);
    $stmtSavings->bind_param("sdsss", $memberID, $amount, $notes, $status, $transactionType);

    if ($stmtSavings->execute()) {
        $savingsID = $conn->insert_id; // Get the inserted SavingsID
        $stmtSavings->close();

        // Fetch first_name, last_name, email using prepare and bind_result
        $sqlUser = "SELECT first_name, last_name, email FROM users WHERE user_id = ?";
        $stmtUser = $conn->prepare($sqlUser);
        $stmtUser->bind_param("i", $memberID);
        $stmtUser->execute();
        $stmtUser->bind_result($firstName, $lastName, $email);

        if ($stmtUser->fetch()) {
            $stmtUser->close();

            $statusAppointment = 'Pending';

            // Insert into appointments table (now including SavingsID)
            $sqlAppointments = "INSERT INTO appointments 
                (user_id, serviceID, description, payable_amount, appointmentdate, status, first_name, last_name, email, SavingsID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmtAppointments = $conn->prepare($sqlAppointments);
            if (!$stmtAppointments) {
                header("Location: home.php?error=stmt_error");
                exit();
            }

            $stmtAppointments->bind_param(
                "iisdsssssi",
                $memberID,
                $serviceID,
                $description,
                $amount,
                $appointmentDate,
                $statusAppointment,
                $firstName,
                $lastName,
                $email,
                $savingsID
            );

            if ($stmtAppointments->execute()) {
                $stmtAppointments->close();
                header("Location: home.php?success=1");
                exit();
            } else {
                $stmtAppointments->close();
                header("Location: home.php?error=appointment_fail");
                exit();
            }
        } else {
            $stmtUser->close();
            header("Location: home.php?error=user_fetch_fail");
            exit();
        }
    } else {
        $stmtSavings->close();
        header("Location: home.php?error=savings_fail");
        exit();
    }
}
?>
